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

        return response()->json(['result_code' => 200, 'result_message' => 'Add package success' , 'data' => $data]);
        
    }

    
	
}
