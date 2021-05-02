<?php

namespace App\Http\Controllers;

use App\Gateway;
use App\Offline;
use App\ReferralFs;
use App\Reflink;
use App\SettingsFs;
use App\Withdraw;
use App\Buycards;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserBuycardsController extends Controller
{
    //
    public function __construct()
    {

        $this->middleware('auth');

    }

    public function index()
    {
        $user=Auth::user();
        
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);

        $withdraws= buycards::whereUser_id($user->id)->orderBy('updated_at','desc')->paginate(15);

        $settings = SettingsFs::first();

        return view('user.giftcards.boughtcards',compact('withdraws','settings','rewards'));
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

        return view('user.giftcards.buycards',compact('gateways','user','settings','gate','rewards'));

    }

    public function postBuycards(Request $request)
    {
        $this->validate($request, [
            'gateway'=> 'required',
            'amount' => 'required',
            'account' => 'required',
        ]);

			$user = Auth::user();
			$settings = SettingsFs::first();
       		$dollar = $settings->dollar_rate;
            $new_amount = $request->amount;
            $remove = $dollar*$new_amount;
            $type = $request->gateway;
			$before = $user->profile->main_balance ;
			$after = $user->profile->main_balance - $remove;
         

        $settings = SettingsFs::first();

        if ($user->profile->main_balance < $remove){

            session()->flash('message', 'You need at least  '.$request->currency.''.$remove.' to purchase this gift card. Please deposit some money. ');
            Session::flash('type', 'error');
            Session::flash('title', 'Minimum Withdraw');

            return redirect(route('userBuycards'));


        }
      
        if ($request->gateway == 1000){

            $gateway= Gateway::first();

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

            session()->flash('message', 'After Charging Gateway Fee Your Total Withdraw Amount $ '.$new_amount.' Has Been Successfully Requested. Fund is automatically send to your account Once we verify ');
            Session::flash('type', 'success');
            Session::flash('title', 'Withdraw Requested');

            return redirect(route('user.giftcards.buycards'));

        }

       		$gateway= Offline::whereId($request->gateway)->first();
       		$settings = SettingsFs::first();
       		$dollar = $settings->dollar_rate;
            $new_amount = $request->amount;
            $remove = $dollar*$new_amount;
            $type = $request->gateway;
			$before = $user->profile->main_balance ;
			$after = $user->profile->main_balance - $remove;
            $withdraw= Buycards::create([
                 
                'transaction_id' => str_random(6).$user->id.str_random(6),
                'user_id'=> $user->id,
                'type'=> $request->gateway,
                'amount'=> $request->amount,
                'status'=> 0,
                'balance_before'=> $before,
                'equivalent'=> $remove,
                'balance_after'=> $after,
                'account'=> $request->account,

            ]);

            $user->profile->main_balance = $user->profile->main_balance - $request->amount;
            $user->profile->save();

            session()->flash('message', 'Your '.$type.' Purchase of $ '.$new_amount.' Was successful. Its equivalence of '.$remove.' in your local currency has been deducted from your account. Gift card will be automatically sent to your inbox and the delivery account details you provided once we verify payment ');
            Session::flash('type', 'success');
            Session::flash('title', 'Withdraw Requested');

            return redirect(route('userBoughtcards'));

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
