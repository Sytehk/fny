<?php

namespace App\Http\Controllers;

use App\Gateway;
use App\Cryptocoins;
use App\Cryptocoins2;
use Carbon\Carbon;
use App\Offline;
use App\Buyunitcoins;
use App\Sellunitcoins;
use App\ReferralFs;
use App\Reflink;
use App\SettingsFs;
use App\Withdraw;
use App\Buycoins;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;

class UserBuyunitsController extends Controller
{
    //
    public function __construct()
    {

        $this->middleware('auth');

    }

  
    public function index()
    {

        $cryptocoins = Cryptocoins::whereStatus(1)->get();
        $user = Auth::user();
        $settings = SettingsFs::first();
         $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       

        return view('user.coin.buyunits',compact('cryptocoins','user','settings','rewards'));

    }

   public function index2()
    {
        $user=Auth::user();
        $withdraws= buyunitcoins::whereUser_id($user->id)->orderBy('updated_at','desc')->paginate(15);

        $settings = SettingsFs::first();
         $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       

        return view('user.coin.mycoins',compact('withdraws','settings','rewards'));
    }
    
      public function index3()
    {
        $user=Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        $cryptocoins3 = Cryptocoins::whereStatus(1)->get();
        $cryptocoins = Buyunitcoins::whereUser_id($user->id)->Where('units', '>', 0)->get();
		$cryptocoins2 = Buyunitcoins::whereUser_id($user->id)->Where('units', '>', 0)->get();
		$cryptocoins4 = Buyunitcoins::whereUser_id($user->id)->Where('units', '<', 0)->get();
		$count = (object) array(
			"done"=>Buyunitcoins::whereUser_id($user->id)->count(),
		);
        $settings = SettingsFs::first();

        return view('user.coin.sellunits',compact('count','cryptocoins2','cryptocoins3','cryptocoins','cryptocoins4','settings','rewards'));
    }

       public function index4()
    {
        $user=Auth::user();
		$cryptocoins = sellunitcoins::whereUser_id($user->id)->orderBy('updated_at','desc')->paginate(15);
		$cryptocoins2 = buyunitcoins::whereUser_id($user->id)->get();
        $settings = SettingsFs::first();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       

        return view('user.coin.soldunits',compact('cryptocoins2','cryptocoins','settings','rewards'));
    }


    public function submit(Request $request){

        $this->validate($request, [

            'coin'=> 'required||numeric',
            'unit' => 'required|numeric',

        ]);

        $plan = Cryptocoins::find($request->coin);
        $price = $plan->price;
        $id = $plan->id;
        $available = $plan->available;
        $cname = $plan->name;
        $coin = $request->coin;
        $money = $request->available;
        $units = $request->unit;
        $cur = $request->curr;
  	    $profile = Auth::user()->profile;
        $total=  $price*$units;

        if ($units > $available){

            session()->flash('message', "You tried purchasing coin above the available units. We only have ".$available." units left on ".$cname." ");
            Session::flash('type', 'error');
            Session::flash('title', 'Insufficient Balance');

            return redirect()->route('userBuyunits');
        }
        elseif ($total > $money){


            session()->flash('message', "Your coin purchase price is ".$cur."".$total.". The total purchase amount is greater than your balance.The available money in your account is ".$cur."".$money.".");
            Session::flash('type', 'error');
            Session::flash('title', 'Amount High');

            return redirect()->route('userBuyunits');

        }
      
        else{
             $user = Auth::user();
       
             $time = date('M j, Y  H:i:s', strtotime($user->bonus));
      		 $rewards = json_encode($time);
       
            $percentage =  $plan->percentage;
            $cycle =  $plan->repeat;
            $profit = (($percentage / 100) * $coin * $cycle);
            $cryptocoins = (object) array(
                "units"=>$request->unit,
                "available"=>$available,
                "name"=>$cname,
                "amount"=>$coin,
                "id"=>$id,
                "price"=>$price,
                "total"=>$request->unit * $price,
                "id" => $request->coin,
            );
	   return view('user.coin.preview',compact('cryptocoins','rewards'));



        }



    }
    
    
     public function submit2(Request $request){

        $this->validate($request, [

            'id'=> 'required||numeric',
            'units' => 'required|numeric',

        ]);
        $coinid = Buyunitcoins::where('id',$request->id)->firstOrfail();
		$id = $coinid->coinid;
		$available = $coinid->units;
	    $plan = Cryptocoins::find($id);
        $id = $plan->id;
        $cid = $request->id;
        $sell = $plan->sell;
        $units = $request->units;
        $cname = $request->name;
  	    $profile = Auth::user()->profile;
        $total=  $sell*$units;

        if ($units > $available){

            session()->flash('message', "You cant sell above ".$available." units available in your ".$cname." wallet ");
            Session::flash('type', 'error');
            Session::flash('title', 'Insufficient Balance');

            return redirect()->route('userSellunits');
        }
       
      
        else{
          
          $user = Auth::user();   
         $time = date('M j, Y  H:i:s', strtotime($user->bonus));
       	 $rewards = json_encode($time);
        

            $cryptocoins = (object) array(
                "units"=>$units,
                "id"=>$id,
                "cid"=>$cid,
                "available"=>$available,
                "name"=>$cname,
                "amount"=>$total,
                "price"=>$sell,
            );
	   return view('user.coin.previewsell',compact('cryptocoins','rewards'));



        }



    }
    public function confirm(Request $request){

      
        $plan = Cryptocoins::find($request->id);

        $user = Auth::user();
        $user->profile->deposit_balance = $user->profile->deposit_balance - $request->amount;

        $user->profile->save();
        $plan->available = $plan->available - $request->units;
        $plan->save();
        $withdraw= Buyunitcoins::create([

                'transaction_id' => str_random(6).$user->id.str_random(6),
                'user_id'=> $user->id,
                'name'=> $request->name,
                'amount'=> $request->amount,
                'units'=> $request->units,
                'coinid'=> $request->id,
                'status'=> 1,
               

        ]);

        $today = Carbon::now();
        session()->flash('message', 'You Have Successfully purchased '.$request->units.' units of '.$request->name.' You can monitor your coin trading status in order to sell when price goes high.');
        Session::flash('type', 'success');
        Session::flash('title', 'Invest Successful');

        return redirect()->route('userMycoins');
    }


    public function confirm2(Request $request){
       
        $this->validate($request, [

            'units' => 'required|numeric',

        ]);
        $plan = Cryptocoins::find($request->id2);
        $coin = buyunitcoins::find($request->cid);
		$sell = $plan->sell;
		$pay = $request->units * $sell;
		$cid = $request->cid;
        $user = Auth::user();
        $user->profile->main_balance = $user->profile->main_balance + $pay;
		$user->profile->save();
		$coin2 = $coin->units - $request->units;
		DB::table('buyunitcoins')
            ->where('id', $request->cid)
            ->update(['units' => $coin2]);
		
        $withdraw= Sellunitcoins::create([

                'transaction_id' => str_random(6).$user->id.str_random(6),
                'user_id'=> $user->id,
                'name'=> $request->name,
                'amount'=>$request->amount,
                'units'=> $request->units,
                'balance'=> $coin2,
                'status'=> 1,
               

        ]);
        session()->flash('message', 'You Have Successfully sold '.$request->units.' units of your '.$request->name.' Your coin sales equivalent has been credited into your account.');
        Session::flash('type', 'success');
        Session::flash('title', 'Invest Successful');

        return redirect()->route('userSoldunitcoins');
    }








}
