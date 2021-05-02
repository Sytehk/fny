<?php

namespace App\Http\Controllers;

use App\Inbox;
use App\Notice;
use App\Notifications\SendEmailToOutsider;
use App\Notifications\SendEmailToUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminEmailController extends Controller
{


    public function __construct()
    {

        $this->middleware('admin');

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $inboxes = Inbox::orderBy('updated_at','asc')->paginate(15);


        return view('admin.mails.index',compact('inboxes'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('admin.mails.create');


    }
    public function message()
    {
        //

        return view('admin.mails.notice');


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

            'status'=> 'required|min:1|max:2',
            'subject' => 'required|min:10|max:255',
            'body' => 'required|min:10|max:100000'

        ]);

        if ($request->status == 1){

            $this->validate($request, [

                'email' => 'required|email',
            ]);

            $user = User::whereEmail($request->email)->firstOrFail();


            $notice = new Notice();
            $notice->user_id = $user->id;
            $notice->title = $request->subject;
            $notice->priority = $request->priority;
            $notice->body = $request->body;
            $notice->status = 0;

            if ($request->hasFile('featured')){

                $featured = $request->featured;

                $featured_new_name = time().$featured->getClientOriginalName();

                $featured->move('uploads/posts', $featured_new_name);

                $notice->file = 'uploads/posts/'. $featured_new_name;

            }

            $notice->save();

            session()->flash('message', "Send Message to ".$user->name." Successful!");
            Session::flash('type', 'success');
            Session::flash('title', 'Message Send Success!');

            return redirect()->route('adminMessage.create');

        }
        else {

            $users = User::all();

            if ($request->hasFile('featured')){

                $featured = $request->featured;

                $featured_new_name = time().$featured->getClientOriginalName();

                $featured->move('uploads/posts', $featured_new_name);

                $attachment = 'uploads/posts/'. $featured_new_name;

            }

            foreach ($users as $user){

                $notice = new Notice();
                $notice->user_id = $user->id;
                $notice->title = $request->subject;
                $notice->priority = $request->priority;
                $notice->body = $request->body;
                $notice->status = 0;

                if ($request->hasFile('featured')){

                    $notice->file  = $attachment;

                }

                $notice->save();

            }


            session()->flash('message', "Send Message to all Users Successful!");
            Session::flash('type', 'success');
            Session::flash('title', 'Message Send Success!');

            return redirect()->route('adminMessage.create');



        }




    }

    public function send(Request $request)
    {

        $this->validate($request, [

            'status'=> 'required|min:1|max:2',
            'email' => 'required',
            'subject' => 'required|min:10|max:255',
            'body' => 'required|min:10|max:100000'

        ]);

        if ($request->status == 1){

            $receipts = explode(',',$request->email);

            foreach($receipts as $receipt)
            {
                $user = User::whereEmail($receipt)->first();

                $data = (object) array(

                    "user_name"=>$user->name,
                    "subject"=>$request->subject,
                    "content"=>$request->body,
                );

                $user->notify(new SendEmailToUser($data));
            }

            session()->flash('message', "Send Email to Users Successful!");
            Session::flash('type', 'success');
            Session::flash('title', 'Email Send Success!');

            return redirect()->route('adminEmail');


        }
        else{


            $receipts = explode(',',$request->email);

            foreach($receipts as $receipt)
            {

                $data = (object) array(

                    "subject"=>$request->subject,
                    "content"=>$request->body,
                );

                (new User)->forceFill([
                    'email' => $receipt,
                ])->notify(new SendEmailToOutsider($data));

            }
            session()->flash('message', "Send Email To Outsider Successful!");
            Session::flash('type', 'success');
            Session::flash('title', 'Email Send Success!');

            return redirect()->route('adminEmail');


        }



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

        $inbox = Inbox::find($id);

        $inbox->status = 1;

        $inbox->save();

        return view('admin.mails.show',compact('inbox'));

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
