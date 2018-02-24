<?php
namespace App\Http\Controllers\APIPublic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Deliveries;
use App\Packages;

class PackageController extends Controller
{
	public function __construct(){
	    ini_set('max_input_time', 6000);
        ini_set('max_execution_time', 100000);
        ini_set('memory_limit', '1024M');
	}

    public function add_package(Request $request){
        // get header
        $headers = $request->header('Authorization');
        $header = explode(" ", $headers);
        
        $company_id = DB::table('api_credentials')->where('token_api',$header[1])->value('company_id');
        $data_json = json_decode(file_get_contents("php://input"), true);
        if (empty($data_json['recipient_phone']) || empty($data_json['recipient_address']) || empty($data_json['recipient_name']) || empty($data_json['resi_number']) ) {
            return response()->json(['result_code' => 100, 'result_message' => 'Missing parameter in package detail']);
        }

        if (!is_numeric($data_json['recipient_phone'])) {
            return response()->json(['result_code' => 100, 'result_message' => 'Invalid recipient phone']);
        }

        $package = new Packages;
        $package->recipient_phone = $data_json['recipient_phone'];
        $package->recipient_address = $data_json['recipient_address'];
        $package->recipient_name = $data_json['recipient_name'];
        $package->resi_number = $data_json['resi_number'];
        $package->company_id = $company_id;
        $package->created_by = 0;
        $package->created_at = date('Y-m-d H:i:s');
        $package->save(); 

        $random = time() . rand(10*45, 100*98);
        $random_id = substr($random, 5);

        $deliveries = new Deliveries;
        $deliveries->track_id = $random_id;
        $deliveries->package_id = $package->id;
        $deliveries->status = 1;
        $deliveries->save();

        $data['track_id'] = $random_id;

        $this->send_sms_notification($data_json['recipient_phone'] , $random_id);

        return response()->json(['result_code' => 200, 'result_message' => 'Add package success' , 'data' => $data]);
        
    }

    public function send_sms_notification($recipient_phone, $track_id){

        // get token access for second account
        $token_telkom = DB::table('token_configuration')->where('id',5)->value('token');

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.mainapi.net/smsnotification/1.0.0/messages",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "msisdn=".$recipient_phone."&content=Hi%20from%20HappyDeliv%2C%20here%20is%20your%20Track%20ID%20%3A%20".$track_id.".%20You%20can%20use%20your%20Track%20ID%20to%20monitor%20your%20package%20delivery%20in%20HappyDeliv%20mobile%20app.",
          CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
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
                ['response' => $err, 'type' => 3,'status' => 2, 'param' => 'phone_number='.$recipient_phone."&".$track_id, 'created_at' => date('Y-m-d H:i:s')]
            );
        } else {
            DB::table('logs_telkom_api')->insert(
                ['response' => $response, 'type' => 3,'status' => 1, 'param' => 'phone_number='.$recipient_phone."&".$track_id, 'created_at' => date('Y-m-d H:i:s')]
            );
        }

    }

    
	
}
