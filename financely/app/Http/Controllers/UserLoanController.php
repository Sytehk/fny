<?php

namespace App\Http\Controllers;

use App\Crypto;
use App\Deposit;
use App\Loan;
use App\Userloan;
use App\Loanpayment;
use App\Gateway;
use App\Offline;
use App\Robi\CoinPayments;
use App\SettingsFs;
use App\User;
use App\pay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PayPal\Api\PaymentExecution;
use Stripe\Charge;
use Stripe\Stripe;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class UserLoanController extends Controller
{

    private $apiContext;
    private $secret;
    private $clientId;

    public function __construct()
    {

        $pPay = Gateway::find(1);

        $this->clientId = $pPay->val1;
        $this->secret = $pPay->val2;

        if ($pPay->mode == 0){

            $this->settings = [

                'mode' => 'sandbox',
                'http.ConnectionTimeOut' => 3000,
                'log.LongEnabled' => true,
                'log.FileName' => storage_path().'/logs/paypal.log',
                'log.LogLevel' => 'DEBUG',
            ];

        }
        else
        {
            $this->settings = [

                'mode' => 'live',
                'http.ConnectionTimeOut' => 3000,
                'log.LongEnabled' => true,
                'log.FileName' => storage_path().'/logs/paypal.log',
                'log.LogLevel' => 'DEBUG',
            ];

        }

        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->clientId, $this->secret));
        $this->apiContext->setConfig($this->settings);

    }

    public function index()
    {
        $user = Auth::user();

        $deposits = Deposit::whereUser_id($user->id)->orderBy('updated_at', 'desc')->paginate(15);
        $loan = Loan::whereStatus(1)->orderBy('id', 'desc')->paginate(15);
        
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);

        return view('user.loan.create', compact('loan','rewards'));
    }
    
      public function myloan()
    {
        $user = Auth::user();

        $loan= Userloan::whereUser_id($user->id)->orderBy('created_at', 'desc')->paginate(15);
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);

        return view('user.loan.myloan', compact('loan','rewards'));
    }

  public function paylog()
    {
        $user = Auth::user();

        $loan= Loanpayment::whereUser_id($user->id)->orderBy('created_at', 'desc')->paginate(15);
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);

        return view('user.loan.paylog', compact('loan','rewards'));
    }

	  public function payloan()
		{
			$user = Auth::user();

			$loan= Userloan::whereUser_id($user->id)->wherestatus(2)->where('balance', '>', 0)->orderBy('created_at', 'desc')->paginate(15);
			$user = Auth::user();
			$time = date('M j, Y  H:i:s', strtotime($user->bonus));
			$rewards = json_encode($time);

			return view('user.loan.payloan', compact('loan','rewards'));
		}
		
	public function pay($id)
    {
        $user = Auth::user();
		$time = date('M j, Y  H:i:s', strtotime($user->bonus));
		$rewards = json_encode($time);
        $loan = Userloan::find($id);
	    return view('user.loan.pay', compact('loan','rewards','user'));

    }


    public function create()
    {

        $gateways = Gateway::whereStatus(1)->get();

        $local_gateways = Offline::whereStatus(1)->get();

        $user = Auth::user();

        $settings = SettingsFs::first();
		
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        return view('user.deposit.create', compact('gateways', 'user', 'settings', 'rewards','local_gateways'));
    }
   
   
       public function loanPreview(Request $request)
    {
        $this->validate($request, [

            'name' => 'required|max:200',
            'amount' => 'required|numeric',
            'tenure' => 'required|numeric',

        ]);

        $settings = SettingsFs::first();
        $user = Auth::user();
        $percent = $settings->loanpercent;
        $percentage = (($percent / 100) * $request->amount);

        if ($user->profile->main_balance < $percentage ){
            session()->flash('message', 'You must have at least ' . $settings->loanpercent . ' % of $'.$request->amount.' in your wallet to request for. $' . $request->amount .' on ' . $request->name.' plan' );
            Session::flash('type', 'error');
            Session::flash('title', 'Minimum Deposit');

            return redirect(route('userLoan'));


        }
        
        if (!$user->verified == 1 ){
            session()->flash('message', 'You are not yet verified! Please submit the necessary documents to get verified and start receiving loans!' );
            Session::flash('type', 'error');
            Session::flash('title', 'User Not Verified');

            return redirect(route('userLoan'));
        }
        
         

        $type = Loan::find($request->id);

        $percentage = $type->percentage;
		$user = Auth::user();
	    $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        $loan = (object)array(

            'amount' => $request->amount,
            'id' => $request->id,
            'name' => $request->name,
            'tenure' => $request->tenure,
            'percent' => $percentage,
            'code' => str_random(10),
        );

          return view('user.loan.preview', compact('loan', 'user','rewards'));

    }
    
     public function paid(Request $request, $id)
    {
        $user = Auth::user();
        $this->validate($request, [

            'amount'=> 'required|max:100',

        ]);
        
        
         $topay = $request->amount;
         $bal = $request->bal;

        if ($user->profile->main_balance < $topay ){
            session()->flash('message', 'You dont have enough fund in your wallet to pay off ' . $request->cur .'' . $request->amount .' from your loan plan' );
            Session::flash('type', 'error');
            Session::flash('title', 'Minimum Deposit');

             return redirect()->back();


        }
        
         if ($bal < $topay ){
            session()->flash('message', 'You are trying to pay off ' . $request->cur .'' . $request->amount .' which is greater thn your loan balance. Please try again later' );
            Session::flash('type', 'error');
            Session::flash('title', 'Minimum Deposit');

            return redirect()->back();


        }
        
        
        

        $paid = Userloan::find($id);
        $paid->paid = $paid->paid + $request->amount;
        $paid->balance = $paid->topay - $paid->paid;
        $paid->save();
        
       $log = Loanpayment::create([

            'loancode' => $request->code,
            'user_id' => $user->id,
            'amount' => $request->amount,
            'loanplan' => $request->name,
            'balance' => $paid->balance - $request->amount,
            'status' => 1,
            
        ]);


        session()->flash('message', 'You have paid '.$request->amount.' from your existing loan plan with loan number '.$request->code.'.');
        Session::flash('type', 'success');
        Session::flash('title', 'Updated Successful');


        return redirect()->route('userPayloan');


    }

    

    public function offline(Request $request)
    {

        $this->validate($request, [

            'amount' => 'required|numeric',
            'reference' => 'required|max:50',
        ]);
        $cur=date("Y-m-d");
        $user = Auth::user();
        $success = Userloan::create([

            'loancode' => $request->reference,
            'user_id' => $user->id,
            'loanid' => $request->id,
            'amount' => $request->amount,
            'topay' => $request->topay,
            'loanplan' => $request->name,
            'status' => 0,
            'paid' => 0,
            'balance' => $request->topay,
            'tenure'=>date('M j, Y  H:i:s', strtotime($cur. ' + '.$request->tenure.'days')),

        ]);
        
        $to = "financelyinc@gmail.com";
        $subject = "New Loan Request";
        $txt = "A user just submited a loan request. Review and accept it immediately!";
        $headers = "From: no-reply@financely.net";
        mail($to,$subject,$txt,$headers);

        session()->flash('message', 'Your loan request of  '.$request->cur.'' .$request->amount. ' on '.$request->name.' plan Was successful. Please wait while we verify your loan request ');
        Session::flash('type', 'success');
        Session::flash('title', 'Deposit Requested');

        return redirect()->route('userMyloan');

    }

 

}
