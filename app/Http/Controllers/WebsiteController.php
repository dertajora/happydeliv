<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Companies;
use App\Roles;
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

    public function login_guide(){
        return view('website.login_guide');
    }

    public function download_app_end_user(){
        echo "URL Coming Soon";
    }

    public function login_page(){
        return view('website.login');
    }

    public function login_handle(Request $request){
        $user_found = User::where('phone', $request->get('phone'))->where('role_id','!=',1)->count();
        if ($user_found == 0) 
            return redirect('login')->with('status', 'Login failed. User not found!'); 

        $user = User::where('phone', $request->get('phone'))->first();
        $user_current_password = Crypt::decrypt($user->password);

        if ($user_current_password != $request->get('password')) 
            return redirect('login')->with('status', 'Login failed. Invalid password!'); 

        if ($user->is_verified != 1) 
            return redirect('login')->with('status', 'Login failed. User is not verified!'); 

        // auth user
        Auth::login($user);
        $role = Roles::where('id', $user->role_id)->first();
        $company = Companies::where('id', $user->company_id)->first();
        session(['role_name' => $role->name]);
        session(['company_name' => $company->name]);
        session(['company_id' => $company->id]);
        // redirect to intended menu/url
        return redirect()->intended('/dashboard');

    }

    public function register_handle(Request $request){
        
        $user = User::where('email', $request->get('email'))->orwhere('phone',$request->get('phone'))->count();

        if ($user>0) 
            return redirect('register')->with('status', 'Registration failed! Email or phone number already Used!'); 
        
        // register company
        $data = new Companies;
        $data->name = $request->get('company_name');
        $data->save();

        $token = $this->getToken(50).date('Ymdhis');

        // register PIC of company
        $lastInsertedId = $data->id;
        $user = new User;
        $user->phone = $request->get('phone');
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->company_id = $lastInsertedId ;
        $user->password = Crypt::encrypt($request->get('password'));
        $user->is_verified = 0;
        $user->role_id = 4;
        $user->save(); 

        $lastInsertUserId = $user->id;

        DB::table('partner_verification')->insert(
                ['verification_code' =>  $token, 'user_id' => $lastInsertUserId, 'created_at' => date('Y-m-d H:i:s')]
            );

        $url_verification = url('/')."/partner_verification?code=".$token."&user_id=".$lastInsertUserId;
        
        // send email through Helio API
        // message should in one line because if its not would produce an error
        $message = "Dear Bapak/Ibu <b>".$request->get('name')."</b> dari <b>".$request->get('company_name')." </b>,<br><br><br>Terima kasih telah melakukan registrasi untuk menjadi partner HappyDeliv. <Br>Untuk dapat menggunakan seluruh fitur HappyDeliv, silahkan klik link berikut untuk melakukan verifikasi : <br><br> <a href='".$url_verification."'> ".$url_verification."</a><Br><Br><br> Apabila anda tidak merasa melakukan registrasi partner HappyDeliv, harap abaikan email ini. <br><br><br><br><b>HappyDeliv Team</b><br><br><br>Regards";

        $this->send_email($request->get('email'), $message);

        return redirect('login')->with('status', 'Registration success! Please check your email (including your spam mail directory) to verify your account.'); 
    }

    // verification after partner click verification link in their email
    public function partner_verification(){
        
        $request = DB::table('partner_verification')->where('user_id',$_GET['user_id'])->where('verification_code',$_GET['code'])->count();    
        
        // verification code request found
        if ($request > 0) {

            $verification_status = User::where('id', $_GET['user_id'])->value('is_verified');
            // if ($verification_status == 1) {
            //     return redirect('login')->with('status', 'Account already verified'); 
            // }

            // update status verification user
            $user = User::find($_GET['user_id']);
            $user->is_verified = 1;
            $user->save(); 

            $company_id = User::where('id', $_GET['user_id'])->value('company_id');

            DB::table('api_credentials')->insert(
                    ['company_id' => $company_id, 'client_id' => $this->getToken(32),'client_secret' => $this->getToken(32)]
                );

            return redirect('login')->with('status', 'Verification success! Now you ready to go.'); 
        }else{
            return redirect('login')->with('status', 'Verification failed! User not found.'); 
        }
    }

    public function laboratorium(){
        
    }

    // send email after partner register 
    function send_email($email, $message){
        $token_helio = $this->get_token_helio();
        if ($token_helio == false) {
            return false;
        }else{

            // get token access
            $token_telkom = DB::table('token_configuration')->where('id',2)->value('token');

            $token_access_helio = $token_helio->result->user->token;

            // use Helio API to send email verification

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.mainapi.net/helio/1.0.1/sendmail",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "{\n    \"token\":\"".$token_access_helio."\",\n    \"subject\":\"Verifikasi Akun Partner HappyDeliv\",\n    \"to\":\"".$email."\",\n    \"body\": \"".$message."\"\n}",
              CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$token_telkom,
                "Cache-Control: no-cache",
                "Content-Type: application/json"
              ),
            ));


            $response = curl_exec($curl);
            $data = json_decode($response);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) { 
                DB::table('logs_telkom_api')->insert(
                    ['response' => $err, 'type' => 5,'status' => 2, 'param' => $email, 'created_at' => date('Y-m-d H:i:s')]
                );
                return false;
            } else {
                // if sukses
                DB::table('logs_telkom_api')->insert(
                    ['response' => $response, 'type' => 5,'status' => 1, 'param' => $email, 'created_at' => date('Y-m-d H:i:s')]
                );
                return true;
            }
        }
    }

    // get token for sending email using Helio
    function get_token_helio(){

        // get token access
        $token_telkom = DB::table('token_configuration')->where('id',2)->value('token');

        // get password for Helio account
        $password_helio = DB::table('token_configuration')->where('id',3)->value('token');

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.mainapi.net/helio/1.0.1/login",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{\"email\":\"admin@happydeliv.com\",\"password\":\"".$password_helio."\"}",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$token_telkom,
            "Cache-Control: no-cache",
            "Content-Type: application/json"
          ),
        ));

        $response = curl_exec($curl);
        $data = json_decode($response);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err || $data->message != "ok") {
            DB::table('logs_telkom_api')->insert(
                ['response' => $err, 'type' => 4,'status' => 2, 'param' => '', 'created_at' => date('Y-m-d H:i:s')]
            );
            return false;
        } else {
            // if sukses
            DB::table('logs_telkom_api')->insert(
                ['response' => $response, 'type' => 4,'status' => 1, 'param' => '', 'created_at' => date('Y-m-d H:i:s')]
            );
            return $data;
        }

       
    }

    // generate unique token to create Verification link for partner candidate
    function getToken($length){
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
