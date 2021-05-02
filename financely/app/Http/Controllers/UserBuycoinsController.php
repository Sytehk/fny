<?php

namespace App\Http\Controllers;

use App\Gateway;
use App\Offline;
use App\ReferralFs;
use App\Reflink;
use App\SettingsFs;
use App\Withdraw;
use App\Buycoins;
use Illuminate\Http\Request;
use App\Notifications\SellCoinNot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserBuycoinsController extends Controller
{
    //
    public function __construct()
    {

        $this->middleware('auth');

    }

    public function index()
    {
        $user=Auth::user();

        $withdraws= buycoins::whereUser_id($user->id)->orderBy('updated_at','desc')->paginate(15);

        $settings = SettingsFs::first();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       

        return view('user.cryptocurrency.boughtcoins',compact('withdraws','settings','rewards'));
    }

    public function create()
    {

        $gateways = Offline::all();
        $gate = Gateway::first();
        $user = Auth::user();
        $settings = SettingsFs::first();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       

        return view('user.cryptocurrency.buycoins',compact('gateways','user','settings','gate','rewards'));

    }

    public function postBuycoins(Request $request)
    {
        $this->validate($request, [
            'gateway'=> 'required',
            'amount' => 'required',
            'account' => 'required',
        ]);

        $user = Auth::user();

        $settings = SettingsFs::first();

   
        if ($user->profile->main_balance < $request->amount){

            session()->flash('message', "You don't have enough funds to purchase $request->amount. You have only $".$user->profile->main_balance.". Please deposit some money into your wallet. ");
            Session::flash('type', 'warning');
            Session::flash('title', 'Insufficient Balance');

            return redirect(route('userBuycoins'));


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

            return redirect(route('userBuycoins'));

        }

        $gateway= Offline::whereId($request->gateway)->first();
       
            $new_amount = $request->amount;
            $type = $request->gateway;

            $withdraw= Buycoins::create([

                'transaction_id' => str_random(6).$user->id.str_random(6),
                'user_id'=> $user->id,
                'type'=> $request->gateway,
                'amount'=> $request->amount,
                'status'=> 0,
                'account'=> $request->account,

            ]);

            $user->profile->main_balance = $user->profile->main_balance - $request->amount;
            $user->profile->save();
            
            $user->notify(new SellCoinNot($user));
        
        $to = "financelyinc@gmail.com";
        $subject = "New Buy Coins Request";
        $txt = "A user just submited a request to buy crypto. Review and accept it immediately!";
        $headers = "From: no-reply@financely.net";
        mail($to,$subject,$txt,$headers);

            session()->flash('message', 'Your '.$type.' Purchase of $'.$new_amount.' Was successful. Coin equivalent will be automatically sent to the specified wallet address once we verify payment ');
            Session::flash('type', 'success');
            Session::flash('title', 'Withdraw Requested');

            return redirect(route('userBoughtcoins'));

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
