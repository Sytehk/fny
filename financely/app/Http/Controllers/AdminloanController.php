<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Userloan;
use App\User;
use App\Sellunitcoins;
use App\Style;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminloanController extends Controller
{
    //
    public function index()
    {

        $loan = Loan::all();

        return view('admin.loan.index', compact('loan'));

    }
     public function usersloan()
    {

        $loan = Userloan::wherestatus(0)->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.usersloan.index', compact('loan'));

    }
       public function usersloan2()
    {

        $loan = Userloan::wherestatus(2)->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.usersloan.activeloan', compact('loan'));

    }
    
     public function disburseloan()
    {

        $loan = Userloan::wherestatus(1)->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.usersloan.disburse', compact('loan'));

    }
    
   
    public function edit($id)
    {

        $plan = Loan::find($id);

        return view('admin.loan.edit', compact('plan'));

    }

    public function store(Request $request)
    {

        $this->validate($request, [

            'name'=> 'required|max:100',
            'minimum' => 'required|numeric|min:1',
            'maximum' => 'required|numeric|min:1',
            'percentage'=> 'required|numeric',

        ]);

        $plan = new Loan;

        $plan->name = $request->name; 
        $plan->minimum = $request->minimum;
        $plan->maximum = $request->maximum;
        $plan->percentage = $request->percentage;
        $plan->status = 1;
        $plan->save();


        session()->flash('message', 'The Loan Plan Has Been Successfully Created.');
        Session::flash('type', 'success');
        Session::flash('title', 'Created Successful');


        return redirect()->route('adminloan');




    }
    
    
     
    public function create()
    {

       
        return view('admin.loan.create');


    }
    
   
    public function update(Request $request, $id)
    {

        $this->validate($request, [

            'name'=> 'required|max:100',
            'minimum' => 'required|numeric|min:1',
            'maximum' => 'required|numeric|min:1',
            'percentage'=> 'required|numeric',
            
        ]);

        $plan = Loan::find($id);

        $plan->name = $request->name;
        $plan->minimum = $request->minimum;
        $plan->maximum = $request->maximum;
        $plan->percentage = $request->percentage;
        $plan->save();


        session()->flash('message', 'The Loan Plan Has Been Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Updated Successful');


        return redirect()->route('adminloan');


    }

  
    public function destroy($id)
    {
		session()->flash('message', 'The Loan Plan Has Been Successfully Deleted.');
        Session::flash('type', 'success');
        Session::flash('title', 'Deleted Successful');

        return redirect()->route('adminloan');


    }
    
     public function approveloan($id)
    {
        $loan = Userloan::find($id);
        $loan->status=1;
        $loan->save();

        session()->flash('message', 'The Users Loan Request Has Been Successfully Approved. Please Disburse Loan As Appropriate.');
        Session::flash('type', 'success');
        Session::flash('title', 'Permission Granted');

        return redirect()->back();

    }
      public function disburseloan2(Request $request, $id)
    {
        $loan = Userloan::find($id);
        $plan = Loan::find($request->id);
        $user = User::find($request->userid);
        
        $balance = $user->profile->main_balance;
        $percent = $plan->percentage;
        $percentage= $percent / 100;
        $percentage2= $percentage *  $request->amount;
        $topay = $percentage2 + $request->amount;
        
        $loan->status= $request->status;
        $loan->amount= $request->amount;
        $loan->topay= $topay;
        $loan->balance= $topay;
        $loan->save();
        
      	$user->profile->deposit_balance = $user->profile->deposit_balance +  $request->amount;
        $user->profile->save();



        session()->flash('message', ' '.$request->amount.' Has Been Successfully Disbursed To Users wallet.');
        Session::flash('type', 'success');
        Session::flash('title', 'Permission Granted');

        return redirect()->back();

    }
    
      public function rejectloan($id)
    {
        $loan = Userloan::find($id);
        $loan->status=3;
        $loan->save();

        session()->flash('message', 'The Users Loan Request Has Been Successfully rejected.');
        Session::flash('type', 'success');
        Session::flash('title', 'Permission Granted');

        return redirect()->back();

    }
    
    

}
