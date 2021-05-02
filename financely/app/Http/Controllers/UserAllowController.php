<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\SettingsFs;
use App\Userallow;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserallowController extends Controller
{
      public function index()
    {
        //
         $settings = SettingsFs::first();

        return view('comingsoon/login',compact('settings'));
   }

  

}
