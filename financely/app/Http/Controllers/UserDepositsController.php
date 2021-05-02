<?php

namespace App\Http\Controllers;

use App\Crypto;
use App\Deposit;
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

class UserDepositsController extends Controller
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
        
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);

        return view('user.deposit.index', compact('deposits','rewards'));
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
    public function cryptoConfirm(Request $request)
    {

        $gateway = Gateway::find(3);
        $user = User::findOrFail($request->nothing);
        $percentage = $gateway->percent;
        $fixed = $gateway->fixed;

        $charge = (($percentage / 100) * $request->amount) + $fixed;

        $new_amount = $request->amount - $charge;
        $publicKey=$gateway->val1;
        $privateKey=$gateway->val2;
        $cps = new CoinPayments();
        $cps->Setup($privateKey, $publicKey);
        $req = array(
            'amount' => $request->amount,
            'currency1' => 'USD',
            'currency2' => $request->currency2,
            'buyer_email' => $user->email,
            'buyer_name' => $user->name,
            'item_name' => 'Instant Deposit',
            'custom' => $request->nothing,
            'item_number' => $request->code.$user->id,
            'address' => '',
            'ipn_url' => route('userDepositCrypto'),
        );

        $result = $cps->CreateTransaction($req);

        if ($result['error'] == 'ok') {

            $deposit = Crypto::create([

                'amount' => $request->amount,
                'currency1' => 'USD',
                'currency2' => $request->currency2,
                'details' => 'Instant Deposit Via Crypto Gateways',
                'transaction_id' =>$request->code.$user->id,
                'user_id' => $user->id,
                'gateway_id' => $gateway->id,
                'charge' => $charge,
                'amount2'=>$result['result']['amount'],
                'net_amount' => $new_amount,
                'status' => 0,
                'payment' => 0,

            ]);

            return redirect($result['result']['status_url']);

        } else {

            print 'Error: '.$result['error']."\n";
        }
    }


    public function cryptoStatus(Request $request)
    {
        $gateway = Gateway::find(3);

        $settings = SettingsFs::first();

        $cp_merchant_id = $gateway->account;
        $cp_ipn_secret = $gateway->val3;
        $cp_debug_email = $settings->contact_email;
        function errorAndDie($error_msg) {
            global $cp_debug_email;
            if (!empty($cp_debug_email)) {
                $report = 'Error: '.$error_msg."\n\n";
                $report .= "POST Data\n\n";
                foreach ($_POST as $k => $v) {
                    $report .= "|$k| = |$v|\n";
                }
                mail($cp_debug_email, 'CoinPayments IPN Error', $report);
            }
            die('IPN Error: '.$error_msg);
        }
        if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
            errorAndDie('IPN Mode is not HMAC');
        }
        if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
            errorAndDie('No HMAC signature sent.');
        }
        $request = file_get_contents('php://input');
        if ($request === FALSE || empty($request)) {
            errorAndDie('Error reading POST data');
        }
        if (!isset($_POST['merchant']) || $_POST['merchant'] != trim($cp_merchant_id)) {
            errorAndDie('No or incorrect Merchant ID passed');
        }
        $hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));
        if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
            errorAndDie('HMAC signature does not match');
        }
        $txn_id = $_POST['txn_id'];
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $amount1 = floatval($_POST['amount1']);
        $amount2 = floatval($_POST['amount2']);
        $currency1 = $_POST['currency1'];
        $currency2 = $_POST['currency2'];
        $status = intval($_POST['status']);
        $status_text = $_POST['status_text'];
        $crypto = Crypto::whereTransaction_id($item_number)->first();
        $user = $crypto->user;
        $gateway = $crypto->gateway;
        $order_currency = $crypto->currency1;
        $order_total = $crypto->amount;
        if ($currency1 != $order_currency) {
            errorAndDie('Original currency mismatch!');
        }
        if ($amount1 < $order_total) {
            errorAndDie('Amount is less than order total!');
        }
        if ($status >= 100 || $status == 2) {

            if ($crypto->payment == 0 ){

                $crypto->status = $status;
                $crypto->payment = 1;
                $crypto->save();

                $deposit = Deposit::create([
                    'transaction_id' => $item_number,
                    'user_id' => $user->id,
                    'gateway_name' => $gateway->name,
                    'amount' => $request->amount,
                    'charge' => $crypto->charge,
                    'net_amount' => $crypto->charge,
                    'status' => 1,
                    'details' => 'Crypto Instant Deposit',
                ]);
                $user->profile->deposit_balance = $user->profile->deposit_balance + $crypto->amount;
                $user->profile->save();
            }
        } else if ($status < 0) {
            $crypto->status = $status;
            $crypto->save();
        } else {
            $crypto->status = $status;
            $crypto->save();
        }
        die('IPN OK');
    }
    public function stripeConfirm(Request $request)
    {


        $gateway = Gateway::find(2);

        Stripe::setApiKey($gateway->val2);

        $charge = Charge::create(array(
            "amount" => $request->amount * 100,
            "currency" => config('app.currency_code'),
            "description" => "Deposit via card TrX ID: " . $request->code . "",
            "source" => $request->stripeToken,
        ));

        $user = User::find($request->user_id);

        $percentage = $gateway->percent;
        $fixed = $gateway->fixed;

        $charge = (($percentage / 100) * $request->amount) + $fixed;

        $new_amount = $request->amount - $charge;

        $deposit = Deposit::create([

            'transaction_id' => str_random(6) . $user->id . str_random(6),
            'user_id' => $user->id,
            'gateway_name' => $gateway->name,
            'amount' => $request->amount,
            'charge' => $charge,
            'net_amount' => $new_amount,
            'status' => 1,
            'details' => 'PayPal Instant Deposit',

        ]);

        $user->profile->deposit_balance = $user->profile->deposit_balance + $new_amount;
        $user->profile->save();

        session()->flash('message', 'Cheers, Before Charging Gateway Fee Your Deposit Amount is $' . $request->amount . '. You Deposited Amount of $' . $new_amount . ' Has Been Successfully Add to Your Balance.');
        Session::flash('type', 'success');
        Session::flash('title', 'Deposit Successful');

        return redirect(route('userDashboard'));

    }
    
  
    public function PayPalConfirm(Request $request)
    {

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item1 = new Item();
        $item1->setName($request->item_name)
            ->setCurrency(config('app.currency_code'))
            ->setQuantity(1)
            ->setSku($request->item_number)// Similar to `item_number` in Classic API
            ->setPrice($request->amount);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $amount = new Amount();
        $amount->setCurrency($request->currency_code)
            ->setTotal($request->amount);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($request->custom);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('userDepositPayPal.status'))
            ->setCancelUrl(route('userDeposit.create'));

        //    session()->put('user_id', $request->custom);
        //   session()->put('user_id', $request->custom);

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->apiContext);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            die($ex);
        }

        $paymentLink = $payment->getApprovalLink();

        return redirect($paymentLink);

    }

    public function getPaypalStatus(Request $request)
    {

        if (empty($request->input('PayerID')) || empty($request->input('token'))){

            die('Payment Failed');


        }
        $paymentId = $request->get('paymentId');
        $payment = Payment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));

        $result = $payment->execute($execution, $this->apiContext);

        if ($result->getState() == 'approved'){

            $obj = json_decode($payment); // $obj contains the All Transaction Information.Some of them,I have displayed Below.

            $amount=$obj->transactions[0]->amount->total;

            $transaction_id = $obj->transactions[0]->item_list->items[0]->sku;

            $uref = $obj->transactions[0]->description;

            $user = User::find($uref);


            $gateway = Gateway::first();

            $percentage = $gateway->percent;
            $fixed = $gateway->fixed;

            $charge = (($percentage / 100) * $amount) + $fixed;

            $new_amount = $amount - $charge;

            $deposit = Deposit::create([

                'transaction_id' => $transaction_id,
                'user_id' => $user->id,
                'gateway_name' => $gateway->name,
                'amount' => $amount,
                'charge' => $charge,
                'net_amount' => $new_amount,
                'status' => 1,
                'details' => 'PayPal Instant Deposit',

            ]);

            $user->profile->deposit_balance = $user->profile->deposit_balance + $new_amount;
            $user->profile->save();



            session()->flash('message', 'Cheers, Before Charging Gateway Fee Your Deposit Amount is $' . $amount . '. After Charging Gateway Fee Your Total Deposit Amount $'.$new_amount.' Has Been Successfully Added to Your Balance.');
            Session::flash('type', 'success');
            Session::flash('title', 'Deposit Successful');

            return redirect(route('userDashboard'));

        }
        echo 'Payment Failed Again';

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paymentPreview(Request $request)
    {
        $this->validate($request, [

            'gateway' => 'required|numeric|max:200',
            'amount' => 'required|numeric',

        ]);

        $settings = SettingsFs::first();

        if ($settings->minimum_deposit > $request->amount) {

            session()->flash('message', 'Ammount inputed is lower than the minimum $' . $settings->minimum_deposit . ' required to deposit money. Please adjust value first. ');
            Session::flash('type', 'error');
            Session::flash('title', 'Minimum Deposit');

            return redirect(route('userDeposit.create'));


        }

        $gateway = Offline::find($request->gateway);

        $percentage = $gateway->percent;

        $fixed = $gateway->fixed;

        $charge = (($percentage / 100) * $request->amount) + $fixed;

        $new_amount = $request->amount - $charge;

        $user = Auth::user();

        
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        $deposit = (object)array(

            'amount' => $request->amount,
            'charge' => $charge,
            'net_amount' => $new_amount,
            'code' => str_random(10),
        );

        $user->d_code = $deposit->code;
        $user->save;


        return view('user.deposit.preview', compact('gateway', 'user', 'deposit','rewards'));

    }

    public function instantPreview(Request $request)
    {
        $this->validate($request, [

            'gateway' => 'required|numeric|max:200',
            'amount' => 'required|numeric',

        ]);

        $settings = SettingsFs::first();

        if ($settings->minimum_deposit > $request->amount) {

            session()->flash('message', 'Ammount inputed is lower than the minimum $' . $settings->minimum_deposit . ' required to deposit money. Please adjust value first. ');
            Session::flash('type', 'error');
            Session::flash('title', 'Minimum Deposit');

            return redirect(route('userDeposit.create'));


        }

        $gateway = Gateway::find($request->gateway);

        $percentage = $gateway->percent;

        $fixed = $gateway->fixed;

        $charge = (($percentage / 100) * $request->amount) + $fixed;

        $new_amount = $request->amount - $charge;

        $user = Auth::user();
        
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);


        $deposit = (object)array(

            'amount' => $request->amount,
            'charge' => $charge,
            'net_amount' => $new_amount,
            'code' => str_random(10),
        );


        $user->d_code = $deposit->code;
        $user->save;


        return view('user.deposit.instant', compact('gateway', 'user', 'deposit','rewards'));

    }


    public function offline(Request $request)
    {

              $this->validate($request, [

            'gateway' => 'required|numeric|max:30',
            'amount' => 'required|numeric',
            'reference' => 'required|max:50',
            'transid' => 'required|max:100',
            'name' => 'required|max:50',
            'paymentss' => 'required|image|mimes:jpg,jpeg,png,gif|max:5072',
        ]);
        
        $paymentss = $request->paymentss;
        $paymentss_new_name = time() . $paymentss->getClientOriginalName();
        $paymentss->move('uploads/paymentss', $paymentss_new_name);

        $user = Auth::user();
        $gateway = Offline::whereId($request->gateway)->first();

        $percentage = $gateway['percent'];
        $fixed = $gateway['fixed'];
        $cur = $request->cur;
        $charge = (($percentage / 100) * $request->amount) + $fixed;
        
        $transid = $request->transid;

        $new_amount = $request->amount - $charge;

        $deposit = Deposit::create([

            'transaction_id' => $request->reference,
            'user_id' => $user->id,
            'gateway_name' => $request->name,
            'amount' => $request->amount,
            'charge' => $charge,
            'transid' => $transid,
            'net_amount' => $new_amount,
            'status' => 0,
            'paymentss' => 'uploads/paymentss/' . $paymentss_new_name,
            'details' => 'No Details are Provided',

        ]);
        
        $to = "financelyinc@gmail.com";
        $subject = "New Offline Deposit";
        $txt = "A user just submited a deposit request. Review and accept it immediately!";
        $headers = "From: no-reply@financely.net";
        mail($to,$subject,$txt,$headers);


        session()->flash('message', 'Your Deposited Amount of $' . $new_amount . ' Has Been Successfully sent. Please wait while we verify your deposit ');
        Session::flash('type', 'success');
        Session::flash('title', 'Deposit Requested');

        return redirect()->route('userDeposits');

    }

 public function offline2(Request $request)
    {

        $this->validate($request, [

            'gateway' => 'required|numeric|max:30',
            'amount' => 'required|numeric',
            'reference' => 'required|max:50',
            'name' => 'required|max:50',
        ]);

        $user = Auth::user();
        $gateway = Offline::whereId($request->gateway)->first();

        $percentage = $gateway->percent;
        $fixed = $gateway->fixed;
        $cur = $request->cur;
        $charge = (($percentage / 100) * $request->amount) + $fixed;

        $new_amount = $request->amount;

        $deposit = Deposit::create([

            'transaction_id' => $request->reference,
            'user_id' => $user->id,
            'gateway_name' => $request->name,
            'amount' => $request->amount,
            'charge' => $charge,
            'net_amount' => $new_amount,
            'status' => 1,
            'details' => 'No Details are Provided',

        ]);

        $user->profile->deposit_balance = $user->profile->deposit_balance + $request->amount;
        $user->profile->save();
        
        session()->flash('message', 'Your Deposited Amount of ' . $cur . ' ' . $new_amount . ' Has Been Successfully Deposited Into Your Wallet.');
        Session::flash('type', 'success');
        Session::flash('title', 'Deposit Requested');

        return redirect()->route('userDeposits');

    }

}
