<?php

namespace App\Http\Controllers;

use App\ReferralFs;
use App\ReferralLink;
use App\ReferralRelationship;
use App\Reflink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersReferralController extends Controller
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
        

        $code= $user->reflink->link;

        $link = url('register') . '?ref=' . $code;

        $reflink = Reflink::where('user_id',$user->id)->first();

        $referrals = ReferralFs::where('reflink_id','=',$reflink->id)->get();


        return view('user.myreferral',compact('referrals','link','rewards'));
    }
    public function newRefer()
    {
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        

        $code= $user->reflink->link;

        $link = url('register') . '?ref=' . $code;

        $reflink = Reflink::where('user_id',$user->id)->first();

        $referrals = ReferralFs::whereReflink_id($reflink->id)->get();


        return view('user.newRef',compact('referrals','link','user','rewards'));
    }

}
