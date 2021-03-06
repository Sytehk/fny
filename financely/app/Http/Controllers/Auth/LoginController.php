<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\UserLoginNotify;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;



    protected function redirectTo()
    {

        $user = Auth::user();

        if ($user->active == 0){

            return '/verify/logout';

        }
        else {

            if ($user->ban == 0){

//                $user->notify(new UserLoginNotify($user));
                    $user->email_verified_at = Carbon::now();
                    $user->save();

            }

            return '/user/dashboard';
        }


    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



}
