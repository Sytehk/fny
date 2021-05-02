<?php

namespace App\Http\Controllers;
use App\Faq;
use App\SettingsFs;
use App\Inbox;
use App\Invest; 
use App\Buycards; 
use App\Notifications\AccountActiveSuccess;
use App\PageFs;
use App\Plan;
use App\Proof;
use App\Profile;
use App\Robi\CoinPayments;
use App\Testimonial;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Deposit;
use App\Post;
use App\Withdraw;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    //
    public function index()
    {

		$count = (object) array(

            "total"=>User::all()->count(),
            "active"=>User::whereActive(1)->count(),
            "banned"=>User::whereBan(1)->count(),
            "unverified"=>User::whereActive(0)->count(),
        ); 
        $deposits= Deposit::orderBy('created_at','desc')->take(10)->get();
		$invests= Plan::orderBy('created_at','desc')->take(10)->get();
		$depo= Deposit::orderBy('created_at','desc')->take(5)->get();
		$earn = Profile::select('main_balance')->sum('main_balance');
		$invest = Invest::select('amount')->sum('amount');
        $withdraws = Withdraw::orderBy('created_at','desc')->take(10)->get();
        $with = Withdraw::orderBy('created_at','desc')->take(5)->get();
        $card = Buycards::orderBy('created_at','desc')->take(5)->get();
        $inv = Invest::orderBy('created_at','desc')->take(5)->get();
        $faq = Faq::orderBy('created_at','desc')->take(10)->get();
        $withdraw = Withdraw::select('amount')->sum('amount');
           
        $deposit = Deposit::select('amount')->sum('amount');
        $testimonials=Testimonial::whereStatus(1)->inRandomOrder()->take(3)->get();

        return view('frontend.home',compact('faq','inv','card','with','depo','count','earn','deposits','deposit','invests','invest','withdraws','withdraw','testimonials'));
    }

     public function index2()
    {



       
         $settings = SettingsFs::first();
        return view('maintenance/login',compact('settings'));
    }
   public function index3()
    {



       
         $settings = SettingsFs::first();
        return view('maintenance/register',compact('settings'));
    }


    public function aboutMe()
    {

        return view('about');
    }
    
    public function tos()
    {

        return view('pages/tos');
    }
    public function privacy()
    {

        return view('pages/privacy');
    }
    public function kyc()
    {

        return view('pages/kyc');
    }
    
    public function demo()
    {
        $privateKey="B7aC70C99dbDBb1F9d9cce80E52B1bcdd715773e1cfE106bC19B9725D0Bf0483";
        $publicKey="452c06893ffc1721acf2ba6294ade2a180d02d0d9feb60754e008f182949b7f8";
        $merchantID="6455afcc372972ee88fa33cdfb40ce1b";
        $ipnSecret="AajfKgsfYkhdKhsjD";

        $cps = new CoinPayments();
        $cps->Setup($privateKey, $publicKey);

        $req = array(
            'amount' => 10.00,
            'currency1' => 'USD',
            'currency2' => 'BTC',
            'buyer_email' => 'your_buyers_email@email.com',
            'item_name' => 'Test Item/Order Description',
            'address' => '', // leave blank send to follow your settings on the Coin Settings page
            'ipn_url' => 'https://yourserver.com/ipn_handler.php',
        );
        // See https://www.coinpayments.net/apidoc-create-transaction for all of the available fields

        $result = $cps->CreateTransaction($req);
        if ($result['error'] == 'ok') {
            $le = php_sapi_name() == 'cli' ? "\n" : '<br />';
            print 'Transaction created with ID: '.$result['result']['txn_id'].$le;
            print 'Buyer should send '.sprintf('%.08f', $result['result']['amount']).' BTC'.$le;
            print 'Status URL: '.$result['result']['status_url'].$le;
        } else {
            print 'Error: '.$result['error']."\n";
        }
    }

    public function EmailContact(Request $request)
    {

        $this->validate($request, [

            'name'=> 'required|min:5|max:200',
            'subject' => 'required|min:10|max:200',
            'email' => 'required|email',
            'body' => 'required|min:200|max:3000',

        ]);

        $inbox = new Inbox();

        $inbox->name = $request->name;
        $inbox->email = $request->email;
        $inbox->subject = $request->subject;
        $inbox->details = $request->body;
        $inbox->status = 0;
        $inbox->save();


        session()->flash('message', 'Your Message Has Been Successfully Send to Support Agent.');
        Session::flash('type', 'success');
        Session::flash('title', 'Send Successful');


        return redirect()->back();

    }

    public function contact()
    {

        $faqs = Faq::all();


        return view('contact',compact('faqs'));
    }

    public function services()
    {


        return view('services');
    }


    public function tutorials()
    {

        $posts = Post::latest()->paginate(10);

        $user = User::whereAdmin(1)->first();


        return view('blog',compact('posts','user'));
    }

    public function verifyLogout()
    {

        session()->flash('message', 'Your account has been successfully created but not active yet. You have to activate your account. Please check your email for verify code.');
        session()->flash('type', 'success');
        Auth::logout();

        return redirect()->route('login');
    }
    public function banned()
    {

        session()->flash('message', 'You have no longer access to your account. Your account has been terminated by security department for fraud activity.<br><br> You have been flagged, So do not try to create a new account. Please contact with us to active your account again if you think it is mistakenly done by our department. Thanks for working with us.');
        session()->flash('type', 'danger');
        Auth::logout();

        return redirect()->route('login');
    }
    public function proof()
    {


        $withdraws = Withdraw::orderBy('created_at','desc')->paginate(30);


        return view('proof',compact('withdraws'));
    }

    public function verify($token)
    {

        $user = User::where('token',$token)->firstOrfail();

        $user->token = null;
        $user->active = 1;
        $user->save();

        $user->notify(new AccountActiveSuccess($user));

        session()->flash('message', 'Your Email Address Has Been Successfully Verified.');
        Session::flash('type', 'success');
        Session::flash('title', 'Verified Successful');


        return redirect()->route('userDashboard');
    }


    public function tutorialView($slug)
    {

        $post = Post::where('slug',$slug)->first();
        $user = User::whereAdmin(1)->first();
         $time = date('M j, Y  H:i:s', strtotime($user->bonus));
        $rewards = json_encode($time);
      

        return view('blogview', compact('post','user','rewards'));
    }

    public function pageView($slug)
    {

        $page = PageFs::where('slug',$slug)->first();

        return view('singlepage',compact('page'));
    }

}
