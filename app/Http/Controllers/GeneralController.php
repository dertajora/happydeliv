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


class GeneralController extends Controller
{
	public function __construct(){
	    ini_set('max_input_time', 6000);
        ini_set('max_execution_time', 100000);
        ini_set('memory_limit', '1024M');
	}

    public function manage_credential(Request $request){
        $credentials = DB::table('api_credentials')->where('company_id', Auth::user()->company_id)->first();
        $data['credentials'] = $credentials; 
        return view('configuration.credentials', $data);
    }

    public function generate_token_api(Request $request){
        $client_id = $request->input('client_id');
        $client_secret = $request->input('client_secret');

        $data['token_api'] = $this->getToken(64);
        $data['token_generated_time'] = date('Y-m-d H:i:s'); 

        DB::table('api_credentials')->where('client_id',$client_id)->where('client_secret', $client_secret)->update($data); 
        return json_encode("sukses");
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
