<?php

namespace App\Http\Controllers;

use App\Gateway;
use App\Offline;
use App\ReferralFs;
use App\Reflink;
use App\SettingsFs;
use App\Sellcard;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserSellcardController extends Controller
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

        $withdraws= Sellcard::whereUser_id($user->id)->orderBy('updated_at','desc')->paginate(15);

        $settings = SettingsFs::first();
        return view('user.giftcards.soldcards',compact('withdraws','settings','rewards'));
    }

  



}
