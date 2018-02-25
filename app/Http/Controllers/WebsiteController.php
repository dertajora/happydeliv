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
 
        $locations[] = array(
            'name' => 'Moxy',
            'lat' => -6.900079, 
            'lng' => 107.612222
        );

        $locations[] = array(
            'name' => 'KPAD Gegerkalong',
            'lat' => -6.867916,  
            'lng' => 107.586257
        );

        $locations[] = array(
            'name' => 'Telkom Gegerkalong',
            'lat' => -6.871925, 
            'lng' => 107.588573
        );

        $locations[] = array(
            'name' => 'Cups Coffe',
            'lat' => -6.901317, 
            'lng' => 107.613553
        );

        $locations[] = array(
            'name' => 'ITB',
            'lat' => -6.891587, 
            'lng' => 107.610691
        );

        $new_list[0] = $locations[0];
        
        array_splice($locations,0,1);
        $locations_absolute = $locations;
        $next = 1;
        for ($i=0; $i < count($locations_absolute); $i++) { 
            
            print_r($new_list);
            
            $nearest = $this->get_nearest_position($new_list[$i], $locations);
            
            $new_list[$next] = $locations[$nearest];
            array_splice($locations,$nearest,1);
            $next = $next + 1;
            
        }
       
    }

    public function get_nearest_position($start_position, $list_locations){
        for ($i=0; $i < count($list_locations) ; $i++) { 
            $list_locations[$i]['distance'] = $this->haversine_method($start_position['lat'], $start_position['lng'], $list_locations[$i]['lat'], $list_locations[$i]['lng']);
        }

        $index_nearest = $this->minOfKey($list_locations, "distance");
        return $index_nearest;
        
    }

    function minOfKey($array, $key) {
        if (!is_array($array) || count($array) == 0) return false;
        $min = $array[0][$key];
        $x = 0;
        $key_array = 0;
        foreach($array as $a) {
            if($a[$key] < $min) {
                   $min = $a[$key];
                   $key_array = $x;
            }
            $x = $x+1;
        }
        return $key_array;
    }

    function haversine_method($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
          $earthRadius = 6371000;
          // convert from degrees to radians
          $latFrom = deg2rad($latitudeFrom);
          $lonFrom = deg2rad($longitudeFrom);
          $latTo = deg2rad($latitudeTo);
          $lonTo = deg2rad($longitudeTo);

          $latDelta = $latTo - $latFrom;
          $lonDelta = $lonTo - $lonFrom;

          $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
          return $angle * $earthRadius;
    }

    public function laboratorium_b(){

        $locations[] = array(
            'name' => 'Moxy',
            'lat' => -6.900079, 
            'lng' => 107.612222
        );
        
        $locations[] = array(
            'name' => 'Gedung DPR',
            'lat' => -6.210345, 
            'lng' => 106.800041
        );

        $locations[] = array(
            'name' => 'ITB',
            'lat' => -6.891587, 
            'lng' => 107.610691
        );

        $locations[] = array(
            'name' => 'Taman',
            'lat' => -6.213302, 
            'lng' => 106.808372
        );
        

        try {
            
            // Use libcurl to connect and communicate
            $ch = curl_init(); // Initialize a cURL session
            curl_setopt($ch, CURLOPT_URL, 'https://api.routexl.nl/tour'); // Set the URL
            curl_setopt($ch, CURLOPT_HEADER, 0); // No header in the output
            curl_setopt($ch, CURLOPT_POST, 1); // Do a regular HTTP POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'locations=' . json_encode($locations)); // Add the locations
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the output as a string
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate'); // Compress
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // Basic authorization
            curl_setopt($ch, CURLOPT_USERPWD, 'dertajora:garenaindonesia'); // Your credentials
            
            // Do not use this!
            if (false) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Unsafe!
            
            // Execute the given cURL session
            $output = curl_exec($ch); // Get the output
            $this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Last received HTTP code
            $this->error = curl_error($ch); // Get the last error
            curl_close($ch); // Close the connection
            dd(json_decode($output));
            // Decode the output
            if(json_decode($output)) {
                $this->result = json_decode($output);
            }else{
                $this->result = $output;
            }
            
        } catch(exception $e) {
            
            $this->error = $e->getMessage();
            return false;
            
        } 
        
       

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
