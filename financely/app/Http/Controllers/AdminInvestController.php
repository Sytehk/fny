<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Invest;
use App\Cryptocoins;
use App\Buyunitcoins;
use App\Sellunitcoins;
use App\Style;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminInvestController extends Controller
{
    //
    public function index2()
    {

        $plans = Invest::all();

        return view('admin.plan.usersinvest', compact('plans'));

    }
    public function index()
    {

        $plans = Plan::all();

        return view('admin.plan.index', compact('plans'));

    }
    
     public function coinindex()
    {

        $coin = Cryptocoins::all();

        return view('admin.coin.index', compact('coin'));

    }

  public function coinsale2()
    {

        $coin = Sellunitcoins::all();

        return view('admin.coin.viewbuy', compact('coin'));

    }

   public function coinsale()
    {

        $coin = Buyunitcoins::all();

        return view('admin.coin.viewsell', compact('coin'));

    }


    public function edit($id)
    {

        $plan = Plan::find($id);

        $styles = Style::all();

        return view('admin.plan.edit', compact('plan','styles'));

    }

     public function coinedit($id)
    {

        $plan = Cryptocoins::find($id);

        return view('admin.coin.edit', compact('plan'));

    }

    public function store(Request $request)
    {

        $this->validate($request, [

            'name'=> 'required|max:100',
            'color'=> 'required|max:100',
            'style_id' => 'required|numeric|min:1|max:200',
            'minimum' => 'required|numeric|min:1',
            'maximum' => 'required|numeric|min:1',
            'percentage'=> 'required|numeric',
            'repeat' => 'required|numeric|min:1',
            'start_duration' => 'required|numeric',
            'status' => 'required|boolean',

        ]);

        $plan = new Plan;

        $plan->name = $request->name; 
        $plan->color = $request->color;
        $plan->style_id = $request->style_id;
        $plan->minimum = $request->minimum;
        $plan->maximum = $request->maximum;
        $plan->percentage = $request->percentage;
        $plan->repeat = $request->repeat;
        $plan->start_duration = $request->start_duration;
        $plan->status = $request->status;
        $plan->save();


        session()->flash('message', 'The Invest Plan Has Been Successfully Created.');
        Session::flash('type', 'success');
        Session::flash('title', 'Created Successful');


        return redirect()->route('adminInvest');




    }
    
    
     public function coinstore(Request $request)
    {

        $this->validate($request, [

            'name'=> 'required|max:100',
            'unit'=> 'required|max:100',
            'cost' => 'required|numeric|min:1|max:200',
            'sell' => 'required|numeric|min:1',
            'details' => 'required|',
            
        ]);

        $plan = new Cryptocoins;

        $plan->name = $request->name; 
        $plan->available = $request->unit;
        $plan->price = $request->cost;
        $plan->sell = $request->sell;
        $plan->details = $request->details;
        $plan->status = 1;
        $plan->save();


        session()->flash('message', 'New coin Units has been successfully created.');
        Session::flash('type', 'success');
        Session::flash('title', 'Created Successful');


        return redirect()->route('adminCoin');




    }
    
    public function create()
    {

        $styles = Style::all();
        return view('admin.plan.create', compact('styles'));


    }
    
     public function coincreate()
    {

        $styles = Style::all();
        return view('admin.coin.create', compact('styles'));


    }


    public function update(Request $request, $id)
    {

        $this->validate($request, [

            'name'=> 'required|max:100',
            'color'=> 'required|max:100',
            'style_id' => 'required|numeric|min:1|max:200',
            'minimum' => 'required|numeric|min:1',
            'maximum' => 'required|numeric|min:1',
            'percentage'=> 'required|numeric',
            'repeat' => 'required|numeric|min:1',
            'start_duration' => 'required|numeric',
            'status' => 'required|boolean',

        ]);

        $plan = Plan::find($id);

        $plan->name = $request->name;
        $plan->color = $request->color;
        $plan->style_id = $request->style_id;
        $plan->minimum = $request->minimum;
        $plan->maximum = $request->maximum;
        $plan->percentage = $request->percentage;
        $plan->repeat = $request->repeat;
        $plan->start_duration = $request->start_duration;
        $plan->status = $request->status;
        $plan->save();


        session()->flash('message', 'The Invest Plan Has Been Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Updated Successful');


        return redirect()->route('adminInvest');


    }

     public function coinupdate(Request $request, $id)
    {

        $this->validate($request, [

             'name'=> 'required|max:100',
            'unit'=> 'required|max:100',
            'cost' => 'required|numeric|min:1|max:200',
            'sell' => 'required|numeric|min:1',
            'details' => 'required|',
        ]);

        $plan = Cryptocoins::find($id);

        $plan->name = $request->name; 
        $plan->available = $request->unit;
        $plan->price = $request->cost;
        $plan->sell = $request->sell;
        $plan->details = $request->details;
        $plan->save();


        session()->flash('message', 'The Coin Has Been Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Updated Successful');


        return redirect()->route('adminCoin');


    }

    public function destroy($id)
    {

        $style = Plan::find($id);

        $style->delete();


        session()->flash('message', 'The Invest Plan Has Been Successfully Deleted.');
        Session::flash('type', 'success');
        Session::flash('title', 'Deleted Successful');

        return redirect()->route('adminInvest');


    }
    
     public function coindestroy($id)
    {

        $style = Cryptocoins::find($id);

        $style->delete();


        session()->flash('message', 'The Coin Has Been Successfully Deleted.');
        Session::flash('type', 'success');
        Session::flash('title', 'Deleted Successful');

        return redirect()->route('adminCoin');


    }

}
