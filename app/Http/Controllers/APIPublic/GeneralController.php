<?php
namespace App\Http\Controllers\APIPublic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Deliveries;

class GeneralController extends Controller
{
	public function __construct(){
	    ini_set('max_input_time', 6000);
        ini_set('max_execution_time', 100000);
        ini_set('memory_limit', '1024M');
	}

    public function get_token(Request $request){
        
        $headers = $request->header('Authorization');
        if (empty($headers)) {
            return response()->json(['result_code' => 100, 'result_message' => 'Invalid Authorization', 'data' => '']);
        }

        $headers = explode(" ", $headers);
        
        if (count($headers) != 2 || $headers[0] != "Basic") {
            return response()->json(['result_code' => 100, 'result_message' => 'Invalid Authorization', 'data' => '']);
        }

        $decoded_string = base64_decode($headers[1]);
        $credentials = explode(":", $decoded_string);

        if (count($credentials) != 2) {
            return response()->json(['result_code' => 100, 'result_message' => 'Invalid Authorization', 'data' => '']);
        }

        $user = DB::table('api_credentials')->where('client_id',$credentials[0])->where('client_secret',$credentials[1])->count();

        if ($user == 0) {
            return response()->json(['result_code' => 100, 'result_message' => 'Invalid Credential', 'data' => '']);
        }

        $data['token_api'] = $this->getToken(64);
        $data['token_generated_time'] = date('Y-m-d H:i:s'); 

        DB::table('api_credentials')->where('client_id',$credentials[0])->where('client_secret', $credentials[1])->update($data); 

        $response['token'] = $this->getToken(64);
        $response['token_expired_time'] = date('Y-m-d H:i:s ', strtotime($data['token_generated_time'].' + 1 hours'));
        return response()->json(['result_code' => 200, 'result_message' => 'Generate token success', 'data' => $response]);

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
