<?php

namespace App\Http\Controllers;

use App\Gateway;
use App\Offline;
use App\ReferralFs;
use App\Reflink;
use App\SettingsFs;
use App\Sellcoins;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserSellcoinsController extends Controller
{
    //
    public function __construct()
    {

        $this->middleware('auth');

    }

    public function index()
    {
        $user=Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       

        $withdraws= Sellcoins::whereUser_id($user->id)->orderBy('updated_at','desc')->paginate(15);

        $settings = SettingsFs::first();
        return view('user.cryptocurrency.soldcoins',compact('withdraws','settings','rewards'));
    }

  



}
