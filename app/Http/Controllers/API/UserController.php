<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\User;

class UserController extends Controller
{
	public function __construct(){
	    ini_set('max_input_time', 6000);
        ini_set('max_execution_time', 100000);
        ini_set('memory_limit', '1024M');
	}

    public function register(Request $request){

    	$data = json_decode($request->get('data'));
       
        if (empty($data->email) || empty($data->password) || empty($data->phone) || empty($data->name)) {
            return response()->json(['result_code' => 2, 'result_message' => 'Registration failed. All field is mandatory!', 'data' => '']);
        }elseif (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['result_code' => 2, 'result_message' => 'Registration failed. Email is invalid!', 'data' => '']);
        }elseif (!is_numeric($data->phone)) {
            return response()->json(['result_code' => 2, 'result_message' => 'Registration failed. Phone number is invalid!', 'data' => '']);
        }elseif ($this->check_email_username($data->email,$data->phone)) {
            return response()->json(['result_code' => 2, 'result_message' => 'Registration failed. Email or phone number already used!', 'data' => '']);
        }else{
            $user = new User;
            $user->phone = $data->phone;
            $user->email = $data->email;
            $user->name = $data->name;
            $user->password = Crypt::encrypt($data->password);
            $user->role_id = 1;
            $user->save();  

            return response()->json(['result_code' => 1, 'result_message' => 'Registration success!', 'data' => '']); 
        } 
    }

    public function check_email_username($email, $phone){
        $user = User::where('email', $email)->orwhere('phone',$phone)->count();
        if ($user>0) {
            return true;
        }
        return false;
    }

    public function login(Request $request){
    	$data = json_decode($request->get('data'));
    	if ( empty($data->password) || empty($data->phone) )
    		return response()->json(['result_code' => 2, 'result_message' => 'Phone and Password are mandatory!', 'data' => '']);

    	$user_found = User::where('phone', $data->phone)->where('role_id',1)->count();

    	if ($user_found == 0) {
            return response()->json(['result_code' => 2, 'result_message' => 'User not found!', 'data' => '']);
        }else{
            $user = User::where('phone', $data->phone)->select('password','id')->first();
            $user_current_password = Crypt::decrypt($user->password);
            
            if($data->password == $user_current_password){
            	$user = User::find($user->id);
                $user->token = $this->getToken(64);
                $user->last_login = date('Y-m-d H:i:s');
                $user->save(); 

               	// return user detail
                $user = User::where('phone', $data->phone)
                        ->select('email','phone','name','token')->first();
                return response()->json(['result_code' => 1, 'result_message' => 'Authentification Success!', 'data' => $user]);
            }else{
                return response()->json(['result_code' => 2, 'result_message' => 'Password not valid!', 'data' => '']);
            }
            
        }
    }

    public function user_information(Request $request){
        $data = json_decode($request->get('user_info'));
        $user = User::where('phone', $data->phone)->select('name','email','phone')->first();

        return response()->json(['result_code' => 1, 'result_message' => 'User found', 'data' => $user]);
    }

    public function getToken($length){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }

        return $token;
    }


	
}
