<?php

namespace App\Http\Controllers;

use App\Gateway;
use App\Offline;
use App\ReferralFs;
use App\Reflink;
use App\SettingsFs;
use App\Withdraw;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserWithdrawsController extends Controller
{
    //
    public function __construct()
    {

        $this->middleware('auth');

    }

    public function index()
    {   
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        $user=Auth::user();

        $withdraws= Withdraw::whereUser_id($user->id)->orderBy('updated_at','desc')->paginate(15);

        $settings = SettingsFs::first();

        return view('user.withdraw.index',compact('withdraws','settings','rewards'));
    }

    public function create()
    {

        $gateways = Offline::all();
        $gate = Gateway::first();
        $user = Auth::user();
        $settings = SettingsFs::first();
        
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);

        return view('user.withdraw.create',compact('gateways','user','settings','gate','rewards'));

    }

    public function postWithdraw(Request $request)
    {
         $this->validate($request, [
            'gateway'=> 'required|numeric',
            'amount' => 'required|numeric',
            'account' => 'required',
        ]);


        $user = Auth::user();

        $settings = SettingsFs::first();


        if ($settings->minimum_withdraw > $request->amount){
            session()->flash('message', 'You need at least  $'.$settings->minimum_withdraw.' to withdraw money. Please earn some money first. :(');
            Session::flash('type', 'error');
            Session::flash('title', 'Minimum Withdraw');

            return redirect()->route('userWithdraw.create');
        }
        if ($user->profile->main_balance < $request->amount){
            session()->flash('message', "You don't have enough funds to withdraw. You are only eligible to withdraw $".$user->profile->main_balance.". Please earn some money first. ");
            Session::flash('type', 'warning');
            Session::flash('title', 'Insufficient Balance');

            return redirect(route('userWithdraw.create'));
        }
        
        if (!$user->verified == 1 ){
            session()->flash('message', 'You are not verified. Please submit the necessary documents to get verified as soon as possible!' );
            Session::flash('type', 'error');
            Session::flash('title', 'User Not Verified');

            return redirect(route('userWithdraw.create'));
        }
       
        if ($request->gateway == 1000){

            $gateway= Gateway::first();

            $percentage =  $gateway->percent;
            $fixed =  $gateway->fixed;
            $charge = (($percentage / 100) * $request->amount);

            $new_amount = $request->amount - $charge;
            $withdraw= Withdraw::create([

                'transaction_id' => str_random(6).$user->id.str_random(6),
                'user_id'=> $user->id,
                'gateway_name'=> $gateway->name,
                'amount'=> $request->amount,
                'charge'=> $charge,
                'net_amount'=> $new_amount,
                'status'=> 0,
                'account'=> $request->account,

            ]);

            $user->profile->main_balance = $user->profile->main_balance - $charge - $request->amount;

            $user->profile->save();
            
            $to = "financelyinc@gmail.com";
        $subject = "New Withdrawal Request";
        $txt = "A user just submited a withdrawal request. Review and accept it immediately!";
        $headers = "From: no-reply@financely.net";
        mail($to,$subject,$txt,$headers);

            session()->flash('message', 'Your fund Withdrawal Amount of $'.$request->amount.' Has Been Sent. You will be credited once we verify your request');
            Session::flash('type', 'success');
            Session::flash('title', 'Withdraw Requested');

            return redirect(route('userWithdraws'));

        }

        $gateway= Offline::whereId($request->gateway)->first();
        $percentage =  $gateway->percent;
        $fixed =  $gateway->fixed;
        $charge = (($percentage / 100) * $request->amount) + $fixed;
        $new_amount = $request->amount - $charge;

            $withdraw= Withdraw::create([

                'transaction_id' => str_random(6).$user->id.str_random(6),
                'user_id'=> $user->id,
                'gateway_name'=> $gateway->name,
                'amount'=> $request->amount,
                'charge'=> $charge,
                'net_amount'=> $new_amount,
                'status'=> 0,
                'account'=> $request->account,

            ]);

            $user->profile->main_balance = $user->profile->main_balance - $request->amount;
            $user->profile->save();
            
           $to = "austinbaar17@gmail.com";
        $subject = "New Withdrawal Request";
        $txt = "A user just submited a withdrawal request. Review and accept it immediately!";
        $headers = "From: no-reply@financely.net";
        mail($to,$subject,$txt,$headers);

            session()->flash('message', 'Your Fund Withdrawal Amount Of $'.$new_amount.' Has Been Sent. You will be credited once we verify your request ');
            Session::flash('type', 'success');
            Session::flash('title', 'Withdraw Requested');

            return redirect(route('userWithdraws'));

    }

    public function demo(Request $request)
    {




        $user=Auth::user();
        $active = false;
        $link = Reflink::whereUser_id($user->id)->first();
        $referrals = ReferralFs::whereReflink_id($link->id)->get();
        if (count($referrals) > 0){
            foreach ($referrals as $referral){
                if ($referral->user->membership->id > 1){
                    $active = true;
                }
            }
            if ($active === true){


            }
            else{
                session()->flash('message', 'You do not have any active referral. Please refer any member to our site');
                Session::flash('type', 'warning');
                Session::flash('title', 'Withdraw Errors');

                return redirect(route('userWithdraw.create'));
            }
        }
        else {

            session()->flash('message', 'You do not have any active referral. Please refer any member to our site');
            Session::flash('type', 'warning');
            Session::flash('title', 'Withdraw Errors');

            return redirect(route('userWithdraw.create'));
        }



    }




}
