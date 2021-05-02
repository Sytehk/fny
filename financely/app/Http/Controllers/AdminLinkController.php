<?php

namespace App\Http\Controllers;

use App\Link;
use App\Membership;
use App\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminLinkController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $advertisements= Link::paginate(10);

        return view('admin.link.index', compact('advertisements'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $memberships= Membership::all();

        return view('admin.link.create', compact('memberships'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $this->validate($request, [

            'title'=> 'required|max:15',
            'details' => 'required|max:100',
            'membership_id' => 'required',
            'link' => 'required|url',
            'rewards' => 'required|numeric'

        ]);
        $ptc = Link::create([

            'title' => $request->title,
            'details' => $request->details,
            'rewards' => $request->rewards,
            'link' => $request->link,
            'membership_id' => $request->membership_id,
        ]);

        session()->flash('message', 'The Link Share Has Been Successfully Created.');
        Session::flash('type', 'success');
        Session::flash('title', 'Created Successful');


        return redirect()->route('admin.link.index');


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

        $advertisement = Link::find($id);
        $memberships= Membership::all();

        return view('admin.link.edit', compact('advertisement','memberships'));


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

            'title'=> 'required|max:15',
            'details' => 'required|max:100',
            'membership_id' => 'required',
            'link' => 'required|url',
            'rewards' => 'required|numeric'

        ]);


        Link::find($id)->update([

            'title' => $request->title,
            'details' => $request->details,
            'rewards' => $request->rewards,
            'link' => $request->link,
            'membership_id' => $request->membership_id,

        ]);

        session()->flash('message', 'The Link Share Has Been Successfully Updated.');
        Session::flash('type', 'success');
        Session::flash('title', 'Updated Successful');


        return redirect()->route('admin.link.index');


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
        $advertisement = Link::find($id);

        $advert = Share::wherePtc_id($advertisement->id);
        $advert->delete();
        $advertisement->delete();

        session()->flash('message', 'The Link Share Advertisements Has Been Successfully Deleted.');
        Session::flash('type', 'success');
        Session::flash('title', 'Deleted Successful');

        return redirect()->route('admin.link.index');

    }
}
