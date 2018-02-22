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

            //generate OTP from Telkom API (temporary because some minor bug)
            $token_telkom = $this->send_otp_to_user($data->phone); 

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
            	
                //generate OTP from Telkom API
                $token_telkom = $this->send_otp_to_user($data->phone);

                return response()->json(['result_code' => 1, 'result_message' => 'Authentification Success, OTP sent!', 'data' => ""]);
            }else{
                return response()->json(['result_code' => 2, 'result_message' => 'Password not valid!', 'data' => '']);
            }
            
        }
    }

    public function verify_otp(Request $request){

        $data = json_decode($request->get('data'));

        if (empty($data->otp) || empty($data->phone)) 
            return response()->json(['result_code' => 2, 'result_message' => 'Phone and OTP are mandatory!', 'data' => '']);
        
        // find user
        $user = User::where('phone', $data->phone)->where('role_id', 1)->first();

        // if user input unregistered phonenumber
        if (count($user) == 0) 
            return response()->json(['result_code' => 2, 'result_message' => 'Invalid Phone Number', 'data' => '']);

        // verify OTP entered by user to Telkom API
        $check_otp = $this->check_otp_to_telkom($data->phone, $data->otp);

        if ($check_otp == true) {

            // update token user
            $user = User::find($user->id);
            $user->token = $this->getToken(64);
            $user->last_login = date('Y-m-d H:i:s');
            $user->save(); 

            if (!empty($data->otp)) {
                $user = User::find($user->id);
                $user->firebase_token = $data->token_firebase;
                $user->save(); 
            }

            // return user detail
            $user = User::where('phone', $data->phone)->select('email','phone','name','token')->first();
            return response()->json(['result_code' => 1, 'result_message' => 'Authentification Success!', 'data' => $user]);
        }else{
            return response()->json(['result_code' => 1, 'result_message' => 'Authentification Failed, Invalid OTP!', 'data' => ""]);
        }
    }

    public function resend_otp(Request $request){

        $data = json_decode($request->get('data'));

        if (empty($data->phone)) 
            return response()->json(['result_code' => 2, 'result_message' => 'Phone is mandatory!', 'data' => '']);
        
        $user_found = User::where('phone', $data->phone)->where('role_id',1)->count();

        if ($user_found == 0) 
            return response()->json(['result_code' => 2, 'result_message' => 'User not found!', 'data' => '']);

        //generate OTP from Telkom API again
        $this->send_otp_to_user($data->phone);
                
        return response()->json(['result_code' => 1, 'result_message' => 'OTP has been sent!', 'data' => ""]);
        
    }

    public function user_information(Request $request){
        $data = json_decode($request->get('user_info'));
        $user = User::where('phone', $data->phone)->select('name','email','phone')->first();

        return response()->json(['result_code' => 1, 'result_message' => 'User found', 'data' => $user]);
    }

    // generate token for End User to use Android App
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

    public function check_otp_to_telkom($phone_number, $otp){
        // get token access
        $token_telkom = DB::table('token_configuration')->where('id',2)->value('token');
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.mainapi.net/smsotp/1.0.1/otp/".$phone_number."/verifications",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "otpstr=".$otp."&digit=4",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$token_telkom,
            "Cache-Control: no-cache",
            "Content-Type: application/x-www-form-urlencoded",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            DB::table('logs_telkom_api')->insert(
                ['response' => $err, 'type' => 2,'status' => 2, 'param' => 'phone_number='.$phone_number.'&otp='.$otp, 'created_at' => date('Y-m-d H:i:s')]
            );
            return false;
        } else {
            DB::table('logs_telkom_api')->insert(
                ['response' => $response, 'type' => 2,'status' => 1, 'param' => 'phone_number='.$phone_number.'&otp='.$otp, 'created_at' => date('Y-m-d H:i:s')]
            );

            $data = json_decode($response);
            return $data->status;

        }
    }

    public function send_otp_to_user($phone_number){
        // get token access
        $token_telkom = DB::table('token_configuration')->where('id',2)->value('token');

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.mainapi.net/smsotp/1.0.1/otp/".$phone_number,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "PUT",
          CURLOPT_POSTFIELDS => "phoneNum=".$phone_number."&digit=4&content=Hi%20from%20HappyDeliv.%20Here%20is%20your%20OTP%20%20%7B%7Botp%7D%7D%20%2C%20please%20submit%20in%20our%20App%20before%2060%20seconds",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$token_telkom,
            "Cache-Control: no-cache",
            "Content-Type: application/x-www-form-urlencoded"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            DB::table('logs_telkom_api')->insert(
                ['response' => $err, 'type' => 1,'status' => 2, 'param' => 'phone_number='.$phone_number, 'created_at' => date('Y-m-d H:i:s')]
            );
        } else {
            DB::table('logs_telkom_api')->insert(
                ['response' => $response, 'type' => 1,'status' => 1, 'param' => 'phone_number='.$phone_number, 'created_at' => date('Y-m-d H:i:s')]
            );
        }
    }


	
}
