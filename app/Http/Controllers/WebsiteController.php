<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Companies;
use Illuminate\Support\Facades\Crypt;
use Auth;


class WebsiteController extends Controller
{
	public function __construct(){
	    ini_set('max_input_time', 6000);
        ini_set('max_execution_time', 100000);
        ini_set('memory_limit', '1024M');
	}

    public function landing_page(){
        return view('website.home');
    }

    public function registration_page(){
        return view('website.registration');
    }

    public function login_page(){
        return view('website.login');
    }

    public function login_handle(Request $request){
        $user_found = User::where('phone', $request->get('phone'))->where('role_id','!=',1)->count();
        if ($user_found == 0) 
            return redirect('login')->with('status', 'Login Failed. User Not Found!'); 

        $user = User::where('phone', $request->get('phone'))->first();
        $user_current_password = Crypt::decrypt($user->password);

        if ($user_current_password != $request->get('password')) 
            return redirect('login')->with('status', 'Login Failed. Wrong Password!'); 

        // auth user
        Auth::login($user);
        
        return redirect()->intended('/dashboard');

    }

    public function register_handle(Request $request){
        
        $user = User::where('email', $request->get('email'))->orwhere('phone',$request->get('phone'))->count();

        if ($user>0) 
            return redirect('register')->with('status', 'Registration Failed! Email or Phone Number Already Used!'); 
        
        // register company
        $data = new Companies;
        $data->name = $request->get('company_name');
        $data->save();

        // register PIC of company
        $lastInsertedId = $data->id;
        $user = new User;
        $user->phone = $request->get('phone');
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->company_id = $lastInsertedId ;
        $user->password = Crypt::encrypt($request->get('password'));
        $user->role_id = 4;
        $user->save(); 

        return redirect('login')->with('status', 'Registration Success! Please Login'); 
    }

    

	
}
