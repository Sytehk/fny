<?php

namespace App\Http\Middleware;

use App\Robi\Data;
use Closure;
use Illuminate\Support\Facades\Session;

class Verify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = Data::get();
        if ($data){
            switch ($data->status) {
                case 407:
                    session()->flash('message', 'Your Purchase Code is Valid. Please add your purchase code and domain name into Cron Lab Control Panel. Otherwise we will take a action for you and we will not take any liability of anything goes happen with your website or data.');
                    Session::flash('type', 'warning');
                    Session::flash('title', 'Error: 407 | Action Required');
                    return $next($request);
                    break;
                case 101:
                    session()->flash('message', 'This domain triggered as Inactive. Please active this domain in Cron Lab Official Control Panel.');
                    Session::flash('type', 'warning');
                    Session::flash('title', 'Error: 101 | Domain/Website Paused');
                    return $next($request);
                    break;
                case 404:
                    session()->flash('message', 'Your Purchase Code is Invalid. If you really purchased our script then check your purchase code. Otherwise we will take a action for you and we will not take any liability of anything goes happen with your website or data.');
                    Session::flash('type', 'error');
                    Session::flash('title', 'Error: 404 | Purchase Invalid');
                    return $next($request);
                    break;
                case 401:
                    session()->flash('message', 'You are not owner of this purchase code or you did not add this domain in our control panel. Please active this domain in Cron Lab Official Control Panel.');
                    Session::flash('type', 'warning');
                    Session::flash('title', 'Error: 401 | Domain Not Found');
                    return $next($request);
                    break;
                case 202:
                    return $next($request);
                    break;
                default:
                    return $next($request);
                    break;
            }
        }

        else {
            
            return $next($request);
        }
    }
}
