<?php

namespace App\Http\Controllers;


use App\Userloan;
use App\Advert;
use App\AdvertPlan;
use App\KycFs;
use App\Sellcard;
use App\Sellcoins;
use App\Kyc2;
use App\Membership;
use App\Notice;
use App\Notifications\KYC2VerifyAccept;
use App\Notifications\KYCVerifyAccept;
use App\Notifications\SellCoinNot;
use App\Order;
use App\Post;
use App\Ptc;
use App\ReferralFs;
use App\Scheme;
use App\SettingsFs;
use App\Testimonial;
use App\User;
use App\UserAdvert;
use App\UserLog;
use App\Withdraw;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use function MongoDB\BSON\toJSON;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        $withdraw = Withdraw::whereUser_id($user->id)->whereStatus(1)->select('amount')->sum('amount');;
        $unpaid = Userloan::whereUser_id($user->id)->whereStatus(2)->where('balance','>',' 0')->select('topay')->sum('topay');;
        $paid = Userloan::whereUser_id($user->id)->whereStatus(2)->where('paid','>',' 0')->select('paid')->sum('paid');;
        $balance = Userloan::whereUser_id($user->id)->whereStatus(2)->where('balance','>',' 0')->select('balance')->sum('balance');;
        


        $posts = Post::inRandomOrder()->take(3)->get();

        $notify = Notice::whereUser_id($user->id)->whereStatus(0)->get();


        return view('user.index', compact('balance','paid', 'unpaid','user','posts', 'withdraw','notify','rewards'));
    }

    public function message()
    {
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       
        $inboxes = Notice::whereUser_id($user->id)->orderBy('created_at', 'desc')->paginate(20);

        return view('user.mails.index', compact('inboxes','rewards'));
    }

    public function daily()
    {
        $user = Auth::user();

        $now = Carbon::now();
        $settings = SettingsFs::first();
        if ($user->bonus < $now ){

            $user->bonus = $now->addHours(24);
            $user->save();
            $user->profile->main_balance = $user->profile->main_balance + $settings->daily_rewards;
            $user->profile->save();

            session()->flash('message', "You have successfully Claimed your ".$settings->daily_rewards." daily rewards.");
            Session::flash('type', 'success');
            Session::flash('title', 'Claimed Successful');

            return redirect()->route('userDashboard');
        }

        session()->flash('message', "You have Claimed your ".$settings->daily_rewards." daily rewards already.");
        Session::flash('type', 'warning');
        Session::flash('title', 'Claimed Already');

        return redirect()->route('userDashboard');
    }

    public function messageShow($id)
    {
        $inbox = Notice::find($id);
        $inbox->status = 1;
        $inbox->save();

        return view('user.mails.show', compact('inbox'));
    }

    public function messageDown($id)
    {
        $inbox = Notice::find($id);
        $file = $inbox->file;
            // Get parameters
            $file = urldecode($file); // Decode URL-encoded string
            $filepath = $file;

            // Process download
            if(file_exists($filepath)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filepath));
                flush(); // Flush system output buffer
                readfile($filepath);
                exit;
            }

    }

    public function uPlan()
    {

        $uPlans = Scheme::whereStatus(1)->get();
        $memberships = Membership::all();

        return view('user.advert.index', compact('uPlans', 'memberships'));
    }

    public function pShow($id)
    {

        $log = Order::findOrFail($id);
        return view('user.viewads.preads', compact('log'));

    }

    public function uPlanActive(Request $request, $id)
    {

        $user = Auth::user();

        $uPlan = Scheme::find($id);

        if ($uPlan->type == 1) {

            $this->validate($request, [
                'name' => 'required|min:1|max:199',
                'url' => 'required|url',
                'membership' => 'required|min:1',
            ]);
            $balance = $user->profile->deposit_balance;

            if ($uPlan->price > $balance) {

                session()->flash('message', "You don't have sufficient balance. Please deposit money first or earn money");
                Session::flash('type', 'error');
                Session::flash('title', 'Insufficient Balance');

                return redirect()->back();
            }

            $user->profile->deposit_balance = $user->profile->deposit_balance - $uPlan->price;

            $user->profile->save();

            $order = new Order();
            $order->scheme_id = $uPlan->id;
            $order->user_id = $user->id;
            $order->status = 0;
            $order->totalHit = 0;
            $order->url = $request->url;
            $order->title = $request->name;
            $order->membership_id = $request->membership;
            $order->type = 1;
            $order->save();

            session()->flash('message', 'Your Website Ads Request Has Been Successfully Submitted.');
            Session::flash('type', 'success');
            Session::flash('title', 'Request Successful');

            return redirect()->route('uPlanLog');

        } else {

            $this->validate($request, [
                'name' => 'required|min:1|max:199',
                'code' => 'required|min:1|max:4000',
            ]);
            $balance = $user->profile->deposit_balance;

            if ($uPlan->price > $balance) {

                session()->flash('message', "You don't have sufficient balance. Please deposit money first or earn money");
                Session::flash('type', 'error');
                Session::flash('title', 'Insufficient Balance');

                return redirect()->back();
            }

            $user->profile->deposit_balance = $user->profile->deposit_balance - $uPlan->price;

            $user->profile->save();
            $today = Carbon::today();

            $user->profile->deposit_balance = $user->profile->deposit_balance - $uPlan->price;

            $user->profile->save();

            $order = new UserAdvert;
            $order->name = $request->name;
            $order->advert_plan_id = $uPlan->id;
            $order->user_id = $user->id;
            $order->startTime = $today;
            $order->status = 0;
            $order->totalHit = 0;
            $order->code = $request->code;
            $order->type = 2;
            $order->save();

            session()->flash('message', 'Your Video Ads Request Has Been Successfully Submitted.');
            Session::flash('type', 'success');
            Session::flash('title', 'Request Successful');

            return redirect()->route('uPlanLog');

        }

    }

    public function uPlanLog()
    {
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       

        $logs = Order::whereUser_id($user->id)->get();

        return view('user.advert.log', compact('logs','rewards'));
    }

    public function kyc()
    {
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
        

        $result1 = KycFs::whereUser_id($user->id)->first();

        $result2 = Kyc2::whereUser_id($user->id)->first();
        
        $count = (object) array(
			"undone"=>Kyc2::whereUser_id($user->id)->count(),
			"done"=>Kyc2::whereUser_id($user->id)->whereStatus(0)->count(),
            "done2"=>Kyc2::whereUser_id($user->id)->whereStatus(1)->count(),
           
        ); 

        return view('user.verify.verify', compact('count', 'user', 'result1', 'result2','rewards'));
    }
    
    public function sellcard()
    {
        $user = Auth::user();
        
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
		$result1 = KycFs::whereUser_id($user->id)->first();
		$result2 = Kyc2::whereUser_id($user->id)->first();
		$result3 = Kyc2::whereUser_id($user->id)->get();
		
		

        return view('user.giftcards.sellcard', compact('user', 'result1','result3', 'result2','rewards'));
    }

    
     public function sellcoins()
    {
        $user = Auth::user();

        $result1 = KycFs::whereUser_id($user->id)->first();
        $result2= Kyc2::whereUser_id($user->id)->first();
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);


        return view('user.cryptocurrency.sellcoins', compact('user', 'result1', 'result2','rewards'));
    }   
    
     public function soldcoins()
    {
        $user = Auth::user();
        

        $result1 = Soldcoins::whereUser_id($user->id)->first();
        
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);


        return view('user.cryptocurrency.soldcoins', compact('user', 'result1', 'result2','rewards'));
    }



    public function kycSubmit(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [

            'name' => 'required|max:25',
            'front' => 'required|image|mimes:jpg,jpeg,png,gif|max:3072',
            'number' => 'required|max:50',
            'dob' => 'required|date',

        ]);

        if ($request->hasFile('back')) {

            $this->validate($request, [

                'back' => 'required|image|mimes:jpg,jpeg,png,gif|max:3072'
            ]);

            $back = $request->back;
            $back_new_name = time() . $back->getClientOriginalName();
            $back->move('uploads/verifications', $back_new_name);

            $front = $request->front;

            $front_new_name = time() . $front->getClientOriginalName();

            $front->move('uploads/verifications', $front_new_name);

            $kyc = KycFs::create([

                'name' => $request->name,
                'user_id' => $user->id,
                'number' => $request->number,
                'back' => 'uploads/verifications/' . $back_new_name,
                'front' => 'uploads/verifications/' . $front_new_name,
                'dob' => $request->dob,
                'status' => 0,

            ]);

  			
            $user->notify(new KYCVerifyAccept($user));

            session()->flash('message', 'Your Identity Verification Request Has Been Successfully Submitted.Please wait while we verify your identity.');
            Session::flash('type', 'success');
            Session::flash('title', 'Request Successful');

            return redirect()->route('userKyc');

        }

        $front = $request->front;

        $front_new_name = time() . $front->getClientOriginalName();

        $front->move('uploads/verifications', $front_new_name);

        $kyc = KycFs::create([

            'name' => $request->name,
            'user_id' => $user->id,
            'number' => $request->number,
            'back' => 'img/image_placeholder.jpg',
            'front' => 'uploads/verifications/' . $front_new_name,
            'dob' => $request->dob,
            'status' => 0,

        ]);

        $user->notify(new KYCVerifyAccept($user));

        session()->flash('message', 'Your Verify Request Has Been Successfully Submitted.');
        Session::flash('type', 'success');
        Session::flash('title', 'Request Successful');

        return redirect()->route('userKyc');
    }
    
    
     public function sellcardSubmit(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [

            'name' => 'required|max:25',
            'front' => 'required|image|mimes:jpg,jpeg,png,gif|max:3072',
            'number' => 'required',
            

        ]);

        if ($request->hasFile('back')) {

            $this->validate($request, [

                'back' => 'required|image|mimes:jpg,jpeg,png,gif|max:3072'
            ]);

            $back = $request->back;
            $back_new_name = time() . $back->getClientOriginalName();
            $back->move('giftcards', $back_new_name);

            $front = $request->front;

            $front_new_name = time() . $front->getClientOriginalName();

            $front->move('giftcards', $front_new_name);

            $kyc = Sellcard::create([

                'name' => $request->name,
                'user_id' => $user->id,
                'value' => $request->number,
                'back' => 'giftcards/' . $back_new_name,
                'front' => 'giftcards/' . $front_new_name,
                'date' => time(),
                'status' => 0,

            ]);

            $user->notify(new KYCVerifyAccept($user));

            session()->flash('message', 'Your '.$request->name.' sale has been sent to our admin for verification. The gift card value will be remitted to you wallet once approved . Please wait while we verify your card.');
            Session::flash('type', 'success');
            Session::flash('title', 'Request Successful');

            return redirect()->route('userSoldcards');

        }

        $front = $request->front;

        $front_new_name = time() . $front->getClientOriginalName();

        $front->move('giftcards', $front_new_name);

        $kyc = Sellcard::create([

                'name' => $request->name,
                'user_id' => $user->id,
                'value' => $request->number,
                'back' => 'giftcards/' . $back_new_name,
                'front' => 'giftcards/' . $front_new_name,
                'date' => time(),
                'status' => 0,

        ]);

        $user->notify(new KYCVerifyAccept($user));

        session()->flash('message', 'Your Verify Request Has Been Successfully Submitted.');
        Session::flash('type', 'success');
        Session::flash('title', 'Request Successful');

        return redirect()->route('userKyc');
    }
    

    public function kyc2Submit(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [

            'name' => 'required|max:35',
            'idnumber' => 'required|max:35',
            'ssn' => 'required|max:10',
            'fphoto' => 'required|image|mimes:jpg,jpeg,png,gif|max:3072',
            'bphoto' => 'required|image|mimes:jpg,jpeg,png,gif|max:3072',
            'expdate' => 'required|max:35',

        ]);

        $fphoto = $request->fphoto;
        $bphoto = $request->bphoto;

        $fphoto_new_name = time() . $fphoto->getClientOriginalName();
        $bphoto_new_name = time() . $bphoto->getClientOriginalName();

        $fphoto->move('uploads/verifications', $fphoto_new_name);
        $bphoto->move('uploads/verifications', $bphoto_new_name);

        $kyc2 = Kyc2::create([

            'name' => $request->name,
            'user_id' => $user->id,
            'status' => 0,
            'idnumber' => $request->idnumber,
            'ssn' => $request->ssn,
            'fphoto' => 'uploads/verifications/' . $fphoto_new_name,
            'bphoto' => 'uploads/verifications/' . $bphoto_new_name,
            'expdate' => $request->expdate,
        ]);
        
        $user->notify(new KYC2VerifyAccept($user));
        
        $to = "financelyinc@gmail.com";
        $subject = "New KYC Verification";
        $txt = "A user just submited a kyc request. Review and accept it immediately!";
        $headers = "From: no-reply@financely.net";
        mail($to,$subject,$txt,$headers);

        session()->flash('message', 'Your Account Verification Request Has Been Uploaded & Successfully Submitted.');
        Session::flash('type', 'success');
        Session::flash('title', 'Request Successful');

        return redirect()->route('userVerify');
    }


    public function sellcoinSubmit(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [

            'name' => 'required|max:25',
            'number' => 'required',
            'account' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg,png,gif|max:3072',

        ]);

        $photo = $request->photo;

        $photo_new_name = time() . $photo->getClientOriginalName();

        $photo->move('crypto/userscoin', $photo_new_name);

        $kyc2 = Sellcoins::create([

            'type' => $request->name,
            'trans_id' => $request->number,
            'pick' => $request->account,
            'amount' => $request->amount,
            'user_id' => $user->id,
            'photo' => 'crypto/userscoin/' . $photo_new_name,
            'status' => 0,

        ]);

        $user->notify(new SellCoinNot($user));
        
        $to = "financelyinc@gmail.com";
        $subject = "New Sell Coins Request";
        $txt = "A user just submited a request to sell crypto. Review and accept it immediately!";
        $headers = "From: no-reply@financely.net";
        mail($to,$subject,$txt,$headers);

        session()->flash('message', 'Your '.$request->name.' sales request of '.$request->amount.' units has been sent and received on our server. Please wait while we verify your sales. We will credit your account once approved');
        Session::flash('type', 'success');
        Session::flash('title', 'Request Successful');

        return redirect()->route('userSoldcoins');
    }


   
   

    public function earnHistory()
    {
        $user = Auth::user();
        $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
       

        $earns = UserLog::whereUser_id($user->id)->orderBy('created_at', 'desc')->paginate(20);


        return view('user.history.earn', compact('earns','rewards'));
    }

    public function review()
    {
        $user = Auth::user();

        $review = Testimonial::whereUser_id($user->id)->get();

        return view('user.testimonial', compact('review'));
    }

    public function reviewPost(Request $request)
    {
        $this->validate($request, [

            'title' => 'required|min:20|max:100',
            'comment' => 'required|min:50|max:500',

        ]);

        $user = Auth::user();

        $testionial = Testimonial::create([

            'title' => $request->title,
            'comment' => $request->comment,
            'user_id' => $user->id,
            'status' => 0,

        ]);

        session()->flash('message', 'Your Review Has Been Successfully Submitted.');
        Session::flash('type', 'success');
        Session::flash('title', 'Review Successful');

        return redirect()->route('userDashboard');


    }

}
