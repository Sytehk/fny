<?php

namespace App\Http\Controllers;

use App\PageFs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $pages = PageFs::all();

        return view('admin.page.index', compact('pages'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $page = PageFs::find($id);

        return view('admin.page.edit', compact('page'));



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

            'title'=> 'required|min:5|max:100',
            'status' => 'required|boolean',
            'body' => 'required|min:200|max:50000',

        ]);

        $page= PageFs::find($id);
        $page->title = $request->title;
        $page->content = $request->body;
        $page->status = $request->status;
        $page->slug = str_slug($request->title);
        $page->save();


        session()->flash('message', 'The Website Page Has Been Successfully Edited.');
        Session::flash('type', 'success');
        Session::flash('title', 'Edited Successful');


        return redirect()->route('adminPages');


    }

    public function publish($id)
    {
        //
        $page = PageFs::find($id);

        $page->status = 1;

        $page->save();

        session()->flash('message', 'This Page Has Been Successfully Published.');
        Session::flash('type', 'success');
        Session::flash('title', 'Published Successful');

        return redirect()->route('adminPages');

    }


    public function unPublish($id)
    {
        //
        $page = PageFs::find($id);

        $page->status = 0;

        $page->save();

        session()->flash('message', 'This Page Has Been Successfully Un-Published.');
        Session::flash('type', 'success');
        Session::flash('title', 'Un-Published Successful');

        return redirect()->route('adminPages');

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
    }
}
