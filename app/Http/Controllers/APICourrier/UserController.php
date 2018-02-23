<?php
namespace App\Http\Controllers\APICourrier;
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

    public function login(Request $request){

    	$data = json_decode($request->get('data'));
    	if ( empty($data->password) || empty($data->phone) )
    		return response()->json(['result_code' => 2, 'result_message' => 'Phone and Password are mandatory!', 'data' => '']);

    	$user = User::where('phone', $data->phone)->whereNotIn('role_id',[1,3])->first();

    	if (count($user) == 0) {
            return response()->json(['result_code' => 2, 'result_message' => 'User not found!', 'data' => '']);
        }elseif ($user->is_verified != 1) {
            return response()->json(['result_code' => 2, 'result_message' => 'User is not verified!', 'data' => '']);
        }else{
            
            $user_current_password = Crypt::decrypt($user->password);
            
            if($data->password == $user_current_password){
            	// update token user
                $user = User::find($user->id);
                $user->token = $this->getToken(64);
                $user->last_login = date('Y-m-d H:i:s');
                $user->save(); 

                // return user detail
                $user = User::where('phone', $data->phone)->select('email','phone','name','token')->first();
                return response()->json(['result_code' => 1, 'result_message' => 'Authentification Success!', 'data' => $user]);
            }else{
                return response()->json(['result_code' => 2, 'result_message' => 'Password not valid!', 'data' => '']);
            }
            
        }
    }

    public function user_information(Request $request){
        $data = json_decode($request->get('user_info'));
        $user = User::where('phone', $data->phone)->select('name','email','phone')->first();
        $user = DB::table('users')
                    ->select('users.id','users.name', 'email', 'phone', 'companies.name as company')
                    ->join('companies','companies.id','=','users.company_id')
                    ->where('phone', $data->phone)
                    ->first();
                    
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
	
}
