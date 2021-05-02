<?php

namespace App\Http\Controllers;

use App\Interest;
use App\InterestLog;
use App\Invest;
use App\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserInterestController extends Controller
{
    //
    public function __construct()
    {

        $this->middleware('auth');


    }

    public function index(){
        
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        $invests = Plan::whereStatus(1)->get();

        return view('user.invest.index',compact('invests','rewards'));
    }
    public function investHistory(){
        
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        $user = Auth::user();
        $investments = Invest::whereUser_id($user->id)->latest()->paginate(20);

        return view('user.invest.invest',compact('investments','rewards'));
    }
    public function interestHistory(){
        
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        $user = Auth::user();
        $logs = InterestLog::whereUser_id($user->id)->latest()->get();

        return view('user.invest.log',compact('logs','rewards'));
    }



    public function submit(Request $request){

        $this->validate($request, [

            'amount'=> 'required||numeric',
            'plan_id' => 'required|numeric',

        ]);

        $plan = Plan::find($request->plan_id);
        $name = $request->name;
        $minimum = $plan->minimum;

        $maximum = $plan->maximum;
        
        $percent = $plan->percentage;

        $amount = $request->amount;

        $profile = Auth::user()->profile;

        if ($amount < $minimum){

            session()->flash('message', "Your intended Investment is below the minimum investment. You can only invest a minimum of $".$minimum." in this plan. ");
            Session::flash('type', 'error');
            Session::flash('title', 'Insufficient Balance');

            return redirect()->route('userInvestments');
        }
        elseif ($amount > $maximum){


            session()->flash('message', "Your intended Investment is above the maximum investment. You can only invest a maximum of $".$maximum." in this plan.");
            Session::flash('type', 'error');
            Session::flash('title', 'Amount High');

            return redirect()->route('userInvestments');

        }
        elseif ($amount > $profile->deposit_balance ){

            session()->flash('message', "You want to invest $".$amount.". But You have only $".$profile->deposit_balance." in your deposit balance. So Deposit money first or try transfer your all money to deposit balance using fund transfer.");
            Session::flash('type', 'error');
            Session::flash('title', 'Insufficient Funds');

            return redirect()->route('userInvestments');

        }
        else{

            $percentage =  $plan->percentage;
            $cycle =  $plan->repeat;
            
			$user = Auth::user();
			$time = date('M j, Y  H:i:s', strtotime($user->bonus));
			$rewards = json_encode($time);
            $profit = (($percentage / 100) * $amount * $cycle);
           
            $invest = (object) array(
                "profit"=>$profit,
                "amount"=>$amount,
                "name"=>$name,
                "total"=>$profit + $amount,
                "id" => $request->plan_id,
            );


            return view('user.invest.preview',compact('invest','rewards'));



        }



    }
    public function confirm(Request $request){

        $this->validate($request, [

            'plan_id'=> 'required|numeric',
            'amount' => 'required|numeric',
            'tos' => 'required|accepted',

        ]);

        $plan = Plan::find($request->plan_id);

        $user = Auth::user();
        
        $names = $request->plan;
        $user->profile->deposit_balance = $user->profile->deposit_balance - $request->amount;

        $user->profile->save();

        $delay = $plan->start_duration;

        $today = Carbon::now();

        $investment = new Invest();
        $investment->user_id = $user->id;
        $investment->name = $names;
        $investment->plan_id = $request->plan_id;
        $investment->reference_id = str_random(12);
        $investment->amount = $request->amount;
        $investment->start_time = $today->addHours($delay);
        $investment->status = 0;

        $investment->save();

        $interest = new Interest();
        $interest->invest_id = $investment->id;
        $interest->user_id = $user->id;
        
        $investment->name = $names;
        $interest->reference_id = str_random(12);
        $interest->start_time = $today->addHours($delay);
        $interest->total_repeat = 0;
        $interest->status = 0;

        $interest->save();


        session()->flash('message', 'You Have Successfully Invest $'.$request->amount.' You can monitor your investment progress from My Investment Yield Tab from the menu.');
        Session::flash('type', 'success');
        Session::flash('title', 'Invest Successful');

        return redirect()->route('userInvest.history');
    }

}
