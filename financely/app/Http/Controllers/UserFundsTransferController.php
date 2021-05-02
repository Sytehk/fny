<?php

namespace App\Http\Controllers;

use App\Mail\BalanceTransferVerify;
use App\Notifications\RecivedMoneySuccess;
use App\Notifications\SendMoneySuccess;
use App\SettingsFs;
use App\Transfer;
use App\TransferLog;
use App\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UserFundsTransferController extends Controller
{
    //

    public function __construct()
    {

        $this->middleware('auth');


    }

    public function index(){
        $user=Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        

        $settings = SettingsFs::first();

        return view('user.transfer.index',compact('settings','rewards'));
    }
    public function history(){

        $user=Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       
        $logs = TransferLog::whereUser_id($user->id)->get();

        return view('user.transfer.log',compact('logs','rewards'));
    }

    public function self(Request $request){


        if ($request->account == 1){

            $this->validate($request, [

                'account' => 'required|min:1',
                'transfer'=> 'required|min:1|max:2',
                'amount'=> 'required|numeric',
            ]);
            
            $settings= SettingsFs::first();
            
            $user = Auth::user()->profile;
            $balance = $user->deposit_balance;
            $reamount = $request->amount;
            $mini = $settings->minimum_transfer;

            if ($reamount > $balance){

                session()->flash('message', "You don't have sufficient deposit balance. Please deposit money first.");
                Session::flash('type', 'error');
                Session::flash('title', 'Insufficient Balance');

                return redirect()->back();
            }
            
            
            $user->deposit_balance = $user->deposit_balance - $request->amount;
            $user->save();

            $settings= SettingsFs::first();

            $percentage =  $settings->self_transfer;
            $charge = (($percentage / 100) * $request->amount);
            $new_amount = $request->amount - $charge;

            if ($request->transfer == 1){

                $user->deposit_balance = $user->deposit_balance + $new_amount;
                $user->save();

                $log = new TransferLog();
                $log->reference = str_random(3).$user->id.str_random(3);
                $log->user_id = $user->id;
                $log->name = str_random(8);
                $log->email = 'dfgdsg@dgg.no';
                $log->amount =  $request->amount;
                $log->charge = $charge;
                $log->net_amount =$new_amount;
                $log->status = 1;
                $log->type = 3;
                $log->save();

            }
            else{

                $user->main_balance = $user->main_balance + $new_amount;
                $user->save();

                $log = new TransferLog();
                $log->reference = str_random(3).$user->id.str_random(3);
                $log->user_id = $user->id;
                $log->name = str_random(8);
                $log->email = 'dfgdsg@dgg.no';
                $log->amount =  $request->amount;
                $log->charge = $charge;
                $log->net_amount =$new_amount;
                $log->status = 1;
                $log->type = 3;
                $log->save();

            }

            session()->flash('message', 'Your Balance Transfer Has Been Successfully Completed with '.$percentage.'% Transfer Fee.');
            Session::flash('type', 'success');
            Session::flash('title', 'Completed');

            return redirect()->route('userFundsTransfer');

        }
        elseif ($request->account == 2){

            $this->validate($request, [

                'account' => 'required|min:1',
                'transfer'=> 'required|min:1|max:2',
                'amount'=> 'required|numeric',
            ]);

            $user = Auth::user()->profile;
            $balance = $user->main_balance;
            $reamount = $request->amount;

            if ($reamount > $balance){

                session()->flash('message', "You don't have sufficient account balance. Please earn money first.");
                Session::flash('type', 'error');
                Session::flash('title', 'Insufficient Balance');

                return redirect()->back();
            }
            $user->main_balance = $user->main_balance - $request->amount;
            $user->save();

            $settings= SettingsFs::first();

            $percentage =  $settings->self_transfer;
            $charge = (($percentage / 100) * $request->amount);
            $new_amount = $request->amount - $charge;

            if ($request->transfer == 1){

                $user->deposit_balance = $user->deposit_balance + $new_amount;
                $user->save();

                $log = new TransferLog();
                $log->reference = str_random(3).$user->id.str_random(3);
                $log->user_id = $user->id;
                $log->name = str_random(8);
                $log->email = 'dfgdsg@dgg.no';
                $log->amount =  $request->amount;
                $log->charge = $charge;
                $log->net_amount =$new_amount;
                $log->status = 1;
                $log->type = 3;
                $log->save();

            }
            else{

                $user->main_balance = $user->main_balance + $new_amount;
                $user->save();

                $log = new TransferLog();
                $log->reference = str_random(3).$user->id.str_random(3);
                $log->user_id = $user->id;
                $log->name = str_random(8);
                $log->email = 'dfgdsg@dgg.no';
                $log->amount =  $request->amount;
                $log->charge = $charge;
                $log->net_amount =$new_amount;
                $log->status = 1;
                $log->type = 3;
                $log->save();

            }

            session()->flash('message', 'Your Balance Transfer Has Been Successfully Completed with '.$percentage.'% Transfer Fee.');
            Session::flash('type', 'success');
            Session::flash('title', 'Completed');

            return redirect()->route('userFundsTransfer');



        }
        else{

            $this->validate($request, [

                'account' => 'required|min:1',
                'transfer'=> 'required|min:1|max:2',
                'amount'=> 'required|numeric',
            ]);

            $user = Auth::user()->profile;
            $balance = $user->referral_balance;
            $reamount = $request->amount;

            if ($reamount > $balance){

                session()->flash('message', "You don't have sufficient referral balance. Please earn money first.");
                Session::flash('type', 'error');
                Session::flash('title', 'Insufficient Balance');

                return redirect()->back();
            }
            $user->referral_balance = $user->referral_balance - $request->amount;
            $user->save();

            $settings= SettingsFs::first();

            $percentage =  $settings->self_transfer;
            $charge = (($percentage / 100) * $request->amount);
            $new_amount = $request->amount - $charge;

            if ($request->transfer == 1){

                $user->deposit_balance = $user->deposit_balance + $new_amount;
                $user->save();

                $log = new TransferLog();
                $log->reference = str_random(3).$user->id.str_random(3);
                $log->user_id = $user->id;
                $log->name = str_random(8);
                $log->email = 'dfgdsg@dgg.no';
                $log->amount =  $request->amount;
                $log->charge = $charge;
                $log->net_amount =$new_amount;
                $log->status = 1;
                $log->type = 3;
                $log->save();

            }
            else{

                $user->main_balance = $user->main_balance + $new_amount;
                $user->save();

                $log = new TransferLog();
                $log->reference = str_random(3).$user->id.str_random(3);
                $log->user_id = $user->id;
                $log->name = str_random(8);
                $log->email = 'dfgdsg@dgg.no';
                $log->amount =  $request->amount;
                $log->charge = $charge;
                $log->net_amount =$new_amount;
                $log->status = 1;
                $log->type = 3;
                $log->save();

            }

            session()->flash('message', 'Your Balance Transfer Has Been Successfully Completed with '.$percentage.'% Transfer Fee.');
            Session::flash('type', 'success');
            Session::flash('title', 'Completed');

            return redirect()->route('userFundsTransfer');



        }



    }


    public function verify($reference){
        $user=Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       

        $transfer = Transfer::where('reference',$reference)->firstOrfail();

        return view('user.transfer.verify',compact('transfer','rewards'));
    }
    public function resend($reference){
        
        $user=Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       
        $settings = SettingsFs::first();
        $transfer = Transfer::where('reference',$reference)->firstOrfail();
        $user = $transfer->user;
        $receiver = User::whereId($transfer->receipt)->firstOrfail();
        $today = Carbon::now();
        $expired = new Carbon($transfer->updated_at) ;
        $interval = $today->diffInMinutes($expired);
        $count=$transfer->counter;
        if ($interval < 15){

            session()->flash('message', 'Message Resent to your email. Please check your spam box if not found in inbox.');
            Session::flash('type', 'error');
            Session::flash('title', 'Sending Error');

            return redirect()->route('userFundsTransfer.verify',$transfer->reference);


        }
        else{

            $data = (object) array(

                "name"=>$user->name,
                "site_name"=>$settings->site_name,
                "contact"=>$settings->contact_email,
                "company"=>$settings->company_name,
                "code"=>$transfer->verify,
                "amount"=>$transfer->amount,
                "charge"=>$transfer->charge,
                "new_amount" =>$transfer->net_amount,
                "receipt"=>$receiver->email,
            );

            Mail::to($user)->send(new BalanceTransferVerify($data));
            $user=Auth::user();
			$time = date('M j, Y  H:i:s', strtotime($user->bonus));
			$rewards = json_encode($time);
       
            $transfer->counter = $count+1;
            $transfer->save();

            session()->flash('message', 'We have sent transfer OTP to your email.');
            Session::flash('type', 'warning');
            Session::flash('title', 'Sending Complete');

            return redirect()->route('userFundsTransfer.verify',$transfer->reference);
        }

    }
    public function check(Request $request, $reference){

        $this->validate($request, [
            'code' => 'required|numeric',
        ]);

        $transfer = Transfer::where('reference',$reference)->whereStatus(0)->firstOrfail();

        if ($transfer->verify == $request->code){

            $user = $transfer->user;
            $receiver = User::whereId($transfer->receipt)->firstOrfail();

            if ($transfer->type == 1){

                $balance = $user->profile->main_balance;
                $amount = $transfer->amount;
                $charge = $transfer->charge;
                $net_amount = $transfer->net_amount;

                if ($amount > $balance){
                    session()->flash('message', "You don't have sufficient balance in main account. Please earn money first.");
                    Session::flash('type', 'error');
                    Session::flash('title', 'Insufficient Balance');
                    return redirect()->route('userFundsTransfer');
                }

                $user->profile->main_balance = $user->profile->main_balance - $amount;
                $user->profile->save();

                $log = new TransferLog();
                $log->reference = $transfer->reference;
                $log->user_id = $user->id;
                $log->name = $receiver->name;
                $log->email = $receiver->email;
                $log->amount = $transfer->amount;
                $log->charge = $transfer->charge;
                $log->net_amount = $transfer->net_amount;
                $log->status = 1;
                $log->type = 1;
                $log->save();

                $receiver->profile->main_balance = $receiver->profile->main_balance + $net_amount;
                $receiver->profile->save();

                $log = new TransferLog();
                $log->reference = $transfer->reference;
                $log->user_id = $receiver->id;
                $log->name = $user->name;
                $log->email = $user->email;
                $log->amount = $transfer->amount;
                $log->charge = $transfer->charge;
                $log->net_amount = $transfer->net_amount;
                $log->status = 1;
                $log->type = 2;
                $log->save();

                $transfer->status = 1;
                $transfer->save();

                $data = (object) array(
                    "user_name"=>$user->name,
                    "amount"=>$amount,
                    "charge"=>$charge,
                    "new_amount" =>$net_amount,
                    "receiver_name"=>$receiver->name,
                    "receiver_email"=>$receiver->email,
                );

                $user->notify(new SendMoneySuccess($data));

                $data = (object) array(
                    "receiver_name"=>$receiver->name,
                    "sender_name"=>$user->name,
                    "amount"=>$net_amount,
                    "sender_email"=>$user->email,
                );
                $receiver->notify(new RecivedMoneySuccess($data));
                session()->flash('message', 'After Charging Transfer Fee Your Total Sending Amount $ '.$net_amount.' Has Been Successfully Send.');
                Session::flash('type', 'success');
                Session::flash('title', 'Send Successful');
                return redirect()->route('userFundsTransfer');

            }

            else{

                $balance = $user->profile->deposit_balance;
                $amount = $transfer->amount;
                $charge = $transfer->charge;
                $net_amount = $transfer->net_amount;

                if ($amount > $balance){
                    session()->flash('message', "You don't have sufficient balance in deposit account. Please deposit money first.");
                    Session::flash('type', 'error');
                    Session::flash('title', 'Insufficient Balance');
                    return redirect()->route('userFundsTransfer');
                }

                $user->profile->deposit_balance = $user->profile->deposit_balance - $amount;
                $user->profile->save();

                $log = new TransferLog();
                $log->reference = $transfer->reference;
                $log->user_id = $user->id;
                $log->name = $user->name;
                $log->email = $receiver->email;
                $log->amount = $transfer->amount;
                $log->charge = $transfer->charge;
                $log->net_amount = $transfer->net_amount;
                $log->status = 1;
                $log->type = 1;
                $log->save();

                $receiver->profile->deposit_balance = $receiver->profile->deposit_balance + $net_amount;
                $receiver->profile->save();

                $log = new TransferLog();
                $log->reference = $transfer->reference;
                $log->user_id = $receiver->id;
                $log->name = $user->name;
                $log->email = $user->email;
                $log->amount = $transfer->amount;
                $log->charge = $transfer->charge;
                $log->net_amount = $transfer->net_amount;
                $log->status = 1;
                $log->type = 2;
                $log->save();

                $transfer->status = 1;
                $transfer->save();

                $data = (object) array(
                    "user_name"=>$user->name,
                    "amount"=>$amount,
                    "charge"=>$charge,
                    "new_amount" =>$net_amount,
                    "receiver_name"=>$receiver->name,
                    "receiver_email"=>$receiver->email,
                );

                $user->notify(new SendMoneySuccess($data));

                $data = (object) array(
                    "receiver_name"=>$receiver->name,
                    "sender_name"=>$user->name,
                    "amount"=>$net_amount,
                    "sender_email"=>$user->email,
                );
                $receiver->notify(new RecivedMoneySuccess($data));
                session()->flash('message', 'After Charging Transfer Fee Your Total Sending Amount $ '.$net_amount.' Has Been Successfully Send.');
                Session::flash('type', 'success');
                Session::flash('title', 'Send Successful');
                return redirect()->route('userFundsTransfer');

            }

        }
        else{

            session()->flash('message', 'Your Verification code is wrong. Please check the code and try again.');
            Session::flash('type', 'warning');
            Session::flash('title', 'Sending Complete');

            return redirect()->route('userFundsTransfer.verify',$transfer->reference);

        }



    }
    public function others(Request $request){

        $this->validate($request, [
            'account' => 'required|min:1|max:2',
            'email'=> 'required|email',
            'amount'=> 'required|numeric|min:5',
        ]);
        $user = Auth::user();
        $settings = SettingsFs::first();
        $receiver_user = User::where('email',$request->email)->first();

        if ($receiver_user == null){
            session()->flash('message', "There is no valid user with email address '".$request->email."'. Please check your email and try again.");
            Session::flash('type', 'error');
            Session::flash('title', 'Invalid Email');
            return redirect()->route('userFundsTransfer');
        }


        $type = $request->account;

        if ($type == 1){

            $balance = $user->profile->main_balance;
            $reamount = $request->amount;

            if ($settings->minimum_transfer > $reamount){

                session()->flash('message', "Amount is lower than the minimum transfer limit. Allowed minimum amount is $".$settings->minimum_transfer.". Please increase funds first.");
                Session::flash('type', 'error');
                Session::flash('title', 'Minimum Withdraw');
                return redirect()->route('userFundsTransfer');
            }
            if ($reamount > $balance){
                session()->flash('message', "You don't have sufficient balance in your main account. Please earn money first.");
                Session::flash('type', 'error');
                Session::flash('title', 'Insufficient Balance');
                return redirect()->route('userFundsTransfer');
            }

            $percentage =  $settings->other_transfer;
            $charge = (($percentage / 100) * $reamount);
            $new_amount = $reamount - $charge;

            $transfer = new Transfer();
            $transfer-> user_id = $user->id;
            $transfer->reference = str_random(3).$user->id.str_random(3);
            $transfer->receipt = $receiver_user->id;
            $transfer->amount = $reamount;
            $transfer->charge = $charge;
            $transfer->net_amount = $new_amount;
            $transfer->status = 0;
            $transfer->type = 1;
            $transfer->verify = mt_rand(100000, 999999);
            $transfer->save();

            $data = (object) array(

                "name"=>$user->name,
                "site_name"=>$settings->site_name,
                "contact"=>$settings->contact_email,
                "company"=>$settings->company_name,
                "code"=>$transfer->verify,
                "amount"=>$reamount,
                "charge"=>$charge,
                "new_amount" =>$new_amount,
                "receipt"=>$receiver_user->email,
                );

            Mail::to($user)->send(new BalanceTransferVerify($data));

            session()->flash('message', 'We have sent a verification code your email address please check.  Write down that 6 digit code to verify and transfer your money.');
            Session::flash('type', 'warning');
            Session::flash('title', 'Verification Required');

            return redirect()->route('userFundsTransfer.verify',$transfer->reference);
        }

        elseif ($type == 2){

            $balance = $user->profile->deposit_balance;

            $reamount = $request->amount;

            if ($settings->minimum_transfer > $reamount){

                session()->flash('message', "Amount is lower than the minimum transfer limit. Allowed minimum amount is $".$settings->minimum_transfer.". Please increase funds first.");
                Session::flash('type', 'error');
                Session::flash('title', 'Minimum Withdraw');
                return redirect()->route('userFundsTransfer');
            }
            if ($reamount > $balance){

                session()->flash('message', "You don't have sufficient balance in your deposit account. Please deposit money first.");
                Session::flash('type', 'error');
                Session::flash('title', 'Insufficient Balance');

                return redirect()->route('userFundsTransfer');
            }


            $percentage =  $settings->other_transfer;
            $charge = (($percentage / 100) * $reamount);
            $new_amount = $reamount - $charge;

            $transfer = new Transfer();
            $transfer-> user_id = $user->id;
            $transfer->reference = str_random(3).$user->id.str_random(3);
            $transfer->receipt = $receiver_user->id;
            $transfer->amount = $reamount;
            $transfer->charge = $charge;
            $transfer->net_amount = $new_amount;
            $transfer->status = 0;
            $transfer->type = 2;
            $transfer->verify = mt_rand(100000, 999999);
            $transfer->save();

            $data = (object) array(

                "name"=>$user->name,
                "site_name"=>$settings->site_name,
                "contact"=>$settings->contact_email,
                "company"=>$settings->company_name,
                "code"=>$transfer->verify,
                "amount"=>$reamount,
                "charge"=>$charge,
                "new_amount" =>$new_amount,
                "receipt"=>$receiver_user->email,
            );

            Mail::to($user)->send(new BalanceTransferVerify($data));

            session()->flash('message', 'We have sent a verification code your email address please check.  Write down that 6 digit code to verify and transfer your money.');
            Session::flash('type', 'warning');
            Session::flash('title', 'Verification Required');

            return redirect()->route('userFundsTransfer.verify',$transfer->reference);

        }

        return redirect()->route('userFundsTransfer');
    }

}
