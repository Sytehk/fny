<?php

namespace App\Http\Controllers;

use App\Membership;
use App\Order;
use App\Ptc;
use App\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminAdvertPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $styles = Scheme::all();

        return view('admin.AdvertPlan.index', compact('styles'));


    }
    public function allAds()
    {
        //
        $logs = Order::whereNotNull('turn')->get();

        return view('admin.AdvertPlan.user', compact('logs'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }
    public function request()
    {

        $logs= Order::whereNull('turn')->get();

        return view('admin.AdvertPlan.order',compact('logs'));

    }
    public function pause($id)
    {

        $order= Order::find($id);

        if ($order->status == 1){

            $order->status = 0;
            $order->ptc->status = 0;
            $order->ptc->save();
            $order->save();

            session()->flash('message', 'User Ads Has Been Successfully Paused.');
            Session::flash('type', 'success');
            Session::flash('title', 'Paused Successful');

            return redirect()->route('admin.user.advertAll');

        }
        else {

            $order->status = 1;
            $order->ptc->status = 1;
            $order->ptc->save();
            $order->save();

            session()->flash('message', 'User Ads Has Been Successfully Resumed.');
            Session::flash('type', 'success');
            Session::flash('title', 'Resumed Successful');

            return redirect()->route('admin.user.advertAll');

        }

    }
    public function approve($id)
    {

        $order= Order::find($id);

        if (!empty( $order->turn)) {

            session()->flash('message', 'User Ads Request Has Been Already Approved.');
            Session::flash('type', 'success');
            Session::flash('title', 'Request Successful');

            return redirect()->route('admin.user.advert');

        }
        $estimate = $order->scheme->price / $order->scheme->hit;

        $rewards = round($estimate, 2);

        $ptc = Ptc::create([

            'title' => $order->title,
            'details' => "Sponsored Ads",
            'duration' => $order->scheme->duration,
            'rewards' => $rewards,
            'ad_link' => $order->url,
            'user_id' => $order->user->id,
            'order_id' => $order->id,
            'status' => 1,
            'type' => 2,
            'hit' => $order->scheme->hit,
            'count' => 0,
            'membership_id' => $order->membership->id,
        ]);

        $order->turn = 1;
        $order->status = 1;
        $order->save();

        session()->flash('message', 'User Ads Request Has Been Successfully Approved.');
        Session::flash('type', 'success');
        Session::flash('title', 'Request Successful');

        return redirect()->route('admin.user.advert');





    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //


        $this->validate($request, [

            'name'=> 'required|min:1|max:199',
            'price' => 'required|numeric',
            'type'=>'required|min:1|max:2',
            'duration'=>'required|min:1|max:60000',
            'hit' => 'required|numeric'

        ]);

        $plan = new Scheme();

        $plan->name = $request->name;
        $plan->price = $request->price;
        $plan->hit = $request->hit;
        $plan->type = $request->type;
        $plan->duration = $request->duration;
        $plan->status = 1;
        $plan->save();



        session()->flash('message', 'The User Advert Plan Has Been Successfully Created.');
        Session::flash('type', 'success');
        Session::flash('title', 'Created Successful');


        return redirect()->route('admin.advert.planIndex');



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $style = Scheme::find($id);

        return view('admin.AdvertPlan.edit', compact('style'));


    }
    public function orderEdit($id)
    {
        //
        $advertisement = Order::find($id);

        $memberships= Membership::all();
        return view('admin.AdvertPlan.editorder', compact('advertisement','memberships'));


    }

    public function orderEditsubmit(Request $request, $id)
    {

        $this->validate($request, [

            'title'=> 'required|max:200',
            'details' => 'required|max:100',
            'duration' => 'required|numeric',
            'hit' => 'required|numeric',
            'membership_id' => 'required',
            'ad_link' => 'required|url',
            'rewards' => 'required|numeric',
            'status' => 'required|boolean',

        ]);

       Order::find($id)->update([

            'title' => $request->title,
            'url' => $request->ad_link,
            'status' => $request->status,
            'membership_id' => $request->membership_id,

        ]);
        $ptc = Ptc::whereOrder_id($id)->first();
        $ptc->update([

            'title' => $request->title,
            'details' => $request->details,
            'duration' => $request->duration,
            'rewards' => $request->rewards,
            'ad_link' => $request->ad_link,
            'status' => $request->status,
            'hit' => $request->hit,
            'membership_id' => $request->membership_id,

        ]);

        session()->flash('message', 'The Paid To Click Has Been Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Updated Successful');

        return redirect()->route('admin.user.advertAll');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //


        $this->validate($request, [

            'name'=> 'required|min:1|max:199',
            'price' => 'required|numeric',
            'hit' => 'required|numeric',
            'type'=>'required|min:1|max:2',
            'duration'=>'required|min:1|max:60000',
            'status'=> 'required|boolean'

        ]);

        $plan = Scheme::find($id);

        $plan->name = $request->name;
        $plan->price = $request->price;
        $plan->hit = $request->hit;
        $plan->status = $request->status;
        $plan->type = $request->type;
        $plan->duration = $request->duration;
        $plan->save();



        session()->flash('message', 'The User Advert Plan Has Been Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Created Successful');


        return redirect()->route('admin.advert.planIndex');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $plan = Scheme::findOrFail($id);
        $plan->delete();

        session()->flash('message', 'The Advert Plan Has Been Successfully Deleted.');
        Session::flash('type', 'success');
        Session::flash('title', 'Deleted Successful');

        return redirect()->route('admin.advert.planIndex');


    }
}
