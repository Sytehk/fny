<?php

namespace App\Http\Controllers;

use App\SettingsFs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function __construct()
    {

        $this->middleware('admin');
        

    }



    //

    public function index(){

        $settings = SettingsFs::first();

        return view('admin.settings.index',compact('settings'));
    }
    
    public function menu(){

        $settings = SettingsFs::first();

        return view('admin.settings.feature',compact('settings'));
    }

	 public function user(){

        $settings = SettingsFs::first();

        return view('admin.settings.user',compact('settings'));
    }
	
	 public function earnings(){

        $settings = SettingsFs::first();

        return view('admin.settings.earnings',compact('settings'));
    }


    public function generalSettings(Request $request, $id){


        $this->validate($request, [

            'site_name'=> 'required',
            'site_title' => 'required',
            'company_name' => 'required',
            'contact_email' => 'required|email',
            'contact_number' => 'required',
            'app_contact' => 'required|email',
            'address' => 'required',

        ]);


        $settings = SettingsFs::find($id);

        $settings->site_name = $request->site_name;
        $settings->site_title = $request->site_title;
        $settings->company_name = $request->company_name;
        $settings->contact_email = $request->contact_email;
        $settings->contact_number = $request->contact_number;
        $settings->app_contact = $request->app_contact;
        $settings->address = $request->address;
        $settings->save();


        session()->flash('message', ''.$request->company_name.' System Settings Has been Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Update Successful');


        return redirect()->back();


    }

    public function featuresSettings(Request $request, $id){


        $this->validate($request, [

            'latest_deposit' => 'required|boolean',
            'transfer' => 'required|boolean',
            'deposit' => 'required|boolean',
            'invest' => 'required|boolean',
            'withdraw' => 'required|boolean',
            'coin' => 'required|boolean',
            'coinsell' => 'required|boolean',
            'daily_login' => 'required|boolean',
            'card' => 'required|boolean',
            'crypto' => 'required|boolean',
            'loan' => 'required|boolean',
            'autouser' => 'required|boolean',
            'autodraw' => 'required|boolean',
            'autodepo' => 'required|boolean',
            'latest_deposit' => 'required|boolean',
        ]);


        $settings = SettingsFs::find($id);

        $settings->transfer = $request->transfer;
        $settings->loan = $request->loan;
        $settings->deposit = $request->deposit;
        $settings->invest = $request->invest;
        $settings->withdraw = $request->withdraw;
        $settings->coinsystem = $request->coin;
        $settings->coinsell = $request->coinsell;
        $settings->latest_deposit = $request->latest_deposit;
        $settings->giftsystem = $request->card;
        $settings->cryptosystem = $request->crypto;
        $settings->auto_user = $request->autouser;
        $settings->auto_draw = $request->autodraw;
        $settings->auto_depo = $request->autodepo;

        $settings->login = $request->daily_login;
        $settings->save();


        session()->flash('message', 'System Features Has been Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Update Successful');


        return redirect()->back();


    }

    public function earningsSettings(Request $request, $id){


        $this->validate($request, [

            'minimum_deposit'=> 'required|min:0',
            'minimum_withdraw' => 'required|min:0',
            'minimum_transfer' => 'required|min:0',
            'signup_bonus'=> 'required|min:0',
            'referral_signup' => 'required|min:0',
            'referral_deposit' => 'required|min:0',
            'daily_rewards' => 'required|min:0',
            'giftcard' => 'required|min:0',
            'loan' => 'required|min:0',
            'crypto' => 'required|min:0',
        ]);


        $settings = SettingsFs::find($id);

        $settings->minimum_deposit = $request->minimum_deposit;
        $settings->minimum_withdraw = $request->minimum_withdraw;
        $settings->signup_bonus = $request->signup_bonus;
        $settings->referral_signup = $request->referral_signup;
        $settings->referral_deposit = $request->referral_deposit;
        $settings->card_percent = $request->giftcard;
        $settings->coin_percent = $request->crypto;
        $settings->dollar_rate = $request->rate;
        $settings->daily_rewards = $request->daily_rewards;
        $settings->loanpercent = $request->loan;
        $settings->minimum_transfer = $request->minimum_transfer;
        $settings->save();


        session()->flash('message', 'System Earnings Settings Has been Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Update Successful');


        return redirect()->back();


    }

 public function usersSettings(Request $request, $id){


        $this->validate($request, [

            'login'=> 'required|min:0',
            'register' => 'required|min:0',
            'refer' => 'required|min:0',
            'verify' => 'required|min:0',
            
        ]);


        $settings = SettingsFs::find($id);

        $settings->verify = $request->verify;
        $settings->refer = $request->refer;
        $settings->userlogin = $request->login;
        $settings->userregister = $request->register;
        $settings->save();


        session()->flash('message', 'Users Settings Has been Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Update Successful');


        return redirect()->back();


    }


}
