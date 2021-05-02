<?php

namespace App\Http\Controllers;

use App\Deposit;
use App\Userloan;
use App\Invest;
use App\Transfer_log;
use App\KycFs;
use App\Kyc2;
use App\Profile;
use App\Sellcard;
use App\Buycards;
use App\Sellcoins;
use App\Buycoins;
use App\SettingsFs;
use App\Testimonial;
use App\User;
use App\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    //
    public function __construct()
    {

        $this->middleware('admin');

    }

    public function index()
    {

        $deposit_notify = Deposit::whereStatus(0)->get();
        $withdraw_notify = Withdraw::whereStatus(0)->get();
//        $kyc_notify = KycFs::whereStatus(0)->get();
        $kyc2_notify = Kyc2::whereStatus(0)->get();

        $earn = Profile::select('main_balance')->sum('main_balance');
        $deposit = Profile::select('deposit_balance')->sum('deposit_balance');
        $invest = Invest::select('amount')->sum('amount');

        $investplan = Invest::whereStatus(1)->count();
        $pending = Withdraw::whereStatus(0)->select('amount')->sum('amount');

        $unpaid = Userloan::whereStatus(2)->where('balance','>',' 0')->select('topay')->sum('topay');;
        $paid = Userloan::whereStatus(2)->where('paid','>',' 0')->select('paid')->sum('paid');;
        $balance = Userloan::whereStatus(2)->where('balance','>',' 0')->select('balance')->sum('balance');;
        $loanamount= Userloan::whereStatus(2)->where('balance','>',' 0')->select('amount')->sum('amount');;
        $unprocessed= Userloan::whereStatus(0)->where('balance','>',' 0')->select('amount')->sum('amount');;


        $count = (object) array(

            "total"=>User::all()->count(),
            "active"=>User::whereActive(1)->count(),
            "banned"=>User::whereBan(1)->count(),
            "unverified"=>User::whereActive(0)->count(),
        );

        $loaner = (object) array(

             "total"=>Userloan::whereStatus(2)->count(),
             "unprocessed"=>Userloan::whereStatus(0)->count(),
        );

        return view('admin.index',compact('investplan','loaner','unprocessed',
            'loanamount','balance','paid', 'unpaid','deposit_notify','withdraw_notify',
            'kyc2_notify','earn','deposit','invest','pending','count'));
    }

    public function userDeposits()
    {
        $deposits= Deposit::where('status', '<=', 3)->orderBy('updated_at','desc')->get();

        $settings = SettingsFs::first();

        return view('admin.deposit.index',compact('deposits','settings'));


    }

      public function fundlog()
    {
        $deposits= Transfer_log::where('status', '>=', 1)->orderBy('updated_at','desc')->get();

        $settings = SettingsFs::first();

        return view('admin.transfer.index',compact('deposits','settings'));


    }

    public function userWithdraws()
    {
        $withdraws= Withdraw::where('status', '>=', 1)->orderBy('updated_at','desc')->paginate(20);

        $settings = SettingsFs::first();

        return view('admin.withdraw.index',compact('withdraws','settings'));


    }
    public function userWithdrawsRequest()
    {
        $withdraws= Withdraw::whereStatus(0)->orderBy('created_at','desc')->paginate(20);

        $settings = SettingsFs::first();

        return view('admin.withdraw.request',compact('withdraws','settings'));


    }

    public function localDeposits()
    {
        $deposits= Deposit::whereStatus(0)->orderBy('created_at','desc')->get();

        $settings = SettingsFs::first();

        return view('admin.deposit.local',compact('deposits','settings'));


    }

    public function localDepositsUpdate($id)
    {


        $deposit= Deposit::find($id);

        $user = $deposit->user;

        $user->profile->deposit_balance = $user->profile->deposit_balance +  $deposit->net_amount;

        $user->profile->save();

        $deposit->status = 1;

        $deposit->save();



        session()->flash('message', 'User Deposit Request Has Been Successfully Approved');
        Session::flash('type', 'success');
        Session::flash('title', 'Deposit Approved');

        return redirect()->back();


    }

    public function localDepositsFraud($id)
    {


        $deposit= Deposit::find($id);
        $deposit->status = 2;
        $deposit->save();



        session()->flash('message', 'User Deposit Has Been Successfully Set As Fraud');
        Session::flash('type', 'success');
        Session::flash('title', 'Fraud Successfully');

        return redirect()->back();
    }

    public function cardFraud($id)
    {


        $withdraw= Sellcard::find($id);
        $withdraw->status = 2;
        $withdraw->save();



        session()->flash('message', 'Gift Card Sale Has Been Disapproved. Please message seller on the reason for disapproving card');
        Session::flash('type', 'success');
        Session::flash('title', 'Fraud Successfully');

        return redirect()->back();
    }

    public function cardApprove($id)
    {

		$settings = SettingsFs::first();
        $withdraw= Sellcard::find($id);
        $withdraw->status = 1;
        $withdraw->save();
        $card = $withdraw->value;
        $percent = $settings->card_percent;
        $dollar = $settings->dollar_rate;
        $payable = $percent/100*$card;
        $total = $card - $payable;
        $topay = $total*$dollar;
        $user =  $withdraw->user;
		$user->profile->main_balance = $user->profile->main_balance + $topay;
		$user->profile->save();

        session()->flash('message', 'Gift Card Sale Has Been Approved. Card equivalent of '.$topay.' in your local currency has been credited to sellers account');
        Session::flash('type', 'success');
        Session::flash('title', 'Fraud Successfully');

        return redirect()->back();
    }

	 public function cardFraud2($id)
    {


        $withdraw= Buycards::find($id);
        $withdraw->status = 2;
        $withdraw->save();

        session()->flash('message', 'Gift Card Purchase Has Been Disapproved. Please message seller on the reason for disapproving purchase');
        Session::flash('type', 'success');
        Session::flash('title', 'Fraud Successfully');

        return redirect()->back();
    }



	 public function cryptoApprove2($id)
    {

    	$withdraw= Buycoins::find($id);
		$withdraw->status = 1;
        $withdraw->save();

		session()->flash('message', 'Cryptocurrency Purchase Has Been Approved. Please ensure you deliver cryptocurrency to receiver as required');
        Session::flash('type', 'success');
        Session::flash('title', 'Fraud Successfully');

        return redirect()->back();
    }


    public function payment($id)
    {
        $withdraw= Withdraw::find($id);

        $withdraw->status = 1;

        $withdraw->save();

        session()->flash('message', 'User Withdraw Request Has Been Successfully Approved');
        Session::flash('type', 'success');
        Session::flash('title', 'Withdraw Approved');

        return redirect()->back();
    }

    public function withdrawFraud($id)
    {
        $withdraw= Withdraw::find($id);


        $withdraw->status = 2;
        $withdraw->save();

        $user =  $withdraw->user;

        $user->profile->main_balance = $user->profile->main_balance + $withdraw->amount;

        $user->profile->save();

        session()->flash('message', 'User Withdraw Has Been Successfully Refund');
        Session::flash('type', 'success');
        Session::flash('title', 'Refund Successfully');

        return redirect()->back();
    }
    public function review()
    {
        $reviews= Testimonial::latest()->paginate(20);

        return view('admin.testimonials.index',compact('reviews'));
    }

    public function reviewPublish($id)
    {
        $review= Testimonial::find($id);

        $review->status = 1;

        $review->save();

        session()->flash('message', 'User Review Has Been Successfully Published');
        Session::flash('type', 'success');
        Session::flash('title', 'Published Successfully');

        return redirect()->back();
    }
    public function reviewUnPublish($id)
    {
        $review= Testimonial::find($id);

        $review->status = 0;

        $review->save();

        session()->flash('message', 'User Review Has Been Successfully Un-Published');
        Session::flash('type', 'success');
        Session::flash('title', 'Un-Published Successfully');

        return redirect()->back();
    }



}
