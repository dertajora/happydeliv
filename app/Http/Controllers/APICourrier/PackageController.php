<?php
namespace App\Http\Controllers\APICourrier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Deliveries;

class PackageController extends Controller
{
	public function __construct(){
	    ini_set('max_input_time', 6000);
        ini_set('max_execution_time', 100000);
        ini_set('memory_limit', '1024M');
	}

    public function add_package(Request $request){
        
        $data = json_decode($request->get('data'));

        if (empty($data->resi_number)) 
            return response()->json(['result_code' => 2, 'result_message' => 'Resi number is mandatory', 'data' => '']);

        $user_info = json_decode($request->get('user_info'));
        $user = User::where('phone', $user_info->phone)->where('token',$user_info->token)->select('company_id', 'id')->first('id');

        $package = DB::table('deliveries')->select('deliveries.id as delivery_id', 'deliveries.status' , 'courrier_id', 'package_id')
                            ->join('packages','packages.id','=','deliveries.package_id')->where('resi_number', $data->resi_number)
                            ->where('company_id', $user->company_id)
                            ->first();

        if (count($package) == 0) 
            return response()->json(['result_code' => 2, 'result_message' => 'Track package failed. Package not found!', 'data' => '']);
        
        if ($package->status != 1) 
            return response()->json(['result_code' => 2, 'result_message' => 'Add package failed. Package already delivered!', 'data' => '']);

        if ($package->courrier_id == $user->id) 
            return response()->json(['result_code' => 2, 'result_message' => 'Add package failed. Package already added in pending package list!', 'data' => '']);

        // update courrier on delivery
        Deliveries::where('package_id', $package->package_id)->update(['courrier_id' => $user->id, 'updated_at' => date('Y-m-d H:i:s')]);

        return response()->json(['result_code' => 1, 'result_message' => 'Add package success.', 'data' => '']);
         
    }

    public function detail_package(Request $request){
        $data = json_decode($request->get('data'));

        if (empty($data->track_id)) 
            return response()->json(['result_code' => 2, 'result_message' => 'Track ID is mandatory', 'data' => '']);

        $recipient_photo = url('/public/images/user-detail.png');
        $package = DB::table('packages')
                            ->select('packages.resi_number','deliveries.track_id', 'packages.recipient_name', 'packages.recipient_phone',
                                     'packages.recipient_address', 
                                     DB::raw('IF(deliveries.status = 1, "Pending", IF(deliveries.status = 2, "In-Progress", "Done")) as status'),  
                                     DB::raw('"'.$recipient_photo.'" as recipient_photo'))
                            ->join('deliveries','deliveries.package_id','=','packages.id')
                            ->where('deliveries.track_id', $data->track_id)
                            ->first();

        if (count($package) == 0) {
            return response()->json(['result_code' => 2, 'result_message' => 'Package not found.', 'data' => '']);
        }

        return response()->json(['result_code' => 1, 'result_message' => 'Detail package.', 'data' => $package]);
    }

    // update package status into in-progress
    public function process_package(Request $request){
        $data = json_decode($request->get('data'));

        if (empty($data->track_id)) 
            return response()->json(['result_code' => 2, 'result_message' => 'Track ID is mandatory', 'data' => '']);

        
        $delivery = DB::table('deliveries')->where('deliveries.track_id', $data->track_id)->first();

        if (count($delivery) == 0) {
            return response()->json(['result_code' => 2, 'result_message' => 'Package not found.', 'data' => '']);
        }

        // update package status to in-progress
        Deliveries::where('track_id', $data->track_id)->update(['status' => 2, 'updated_at' => date('Y-m-d H:i:s')]);

        return response()->json(['result_code' => 1, 'result_message' => 'Update status package to in-progress success.', 'data' => '']);
    }

    // update package status into finished
    public function finish_package(Request $request){
        $data = json_decode($request->get('data'));

        if (empty($data->track_id)) 
            return response()->json(['result_code' => 2, 'result_message' => 'Track ID is mandatory', 'data' => '']);

        
        $delivery = DB::table('deliveries')->where('deliveries.track_id', $data->track_id)->first();

        if (count($delivery) == 0) {
            return response()->json(['result_code' => 2, 'result_message' => 'Package not found.', 'data' => '']);
        }

        // update package status to in-progress
        Deliveries::where('track_id', $data->track_id)->update(['status' => 3, 'updated_at' => date('Y-m-d H:i:s'), 'delivered_at' => date('Y-m-d H:i:s')]);

        return response()->json(['result_code' => 1, 'result_message' => 'Update status package to delivered success.', 'data' => '']);
    }

    public function list_package(Request $request){

        $user_info = json_decode($request->get('user_info'));
        $user_id = User::where('phone', $user_info->phone)->where('token',$user_info->token)->value('id');
        $recipient_image = url('/')."/public/images/recipient.png";
        $packages = DB::table('deliveries')
                            ->select('deliveries.track_id', 'packages.recipient_name', 'packages.resi_number',
                                DB::raw('IF(deliveries.status = 1, "Pending", "In-Progress") as status'),
                                
                                DB::raw('"'.$recipient_image.'" as recipient_image'))
                            
                            ->join('packages','packages.id','=','deliveries.package_id')
                            ->whereIn('deliveries.status',[1,2])
                            ->where('deliveries.courrier_id', $user_id)
                            ->orderBy('deliveries.created_at','desc')
                            ->get();

        if (count($packages) == 0) 
            return response()->json(['result_code' => 2, 'result_message' => 'List deliveries not found', 'data' => array()]);

        return response()->json(['result_code' => 1, 'result_message' => 'List deliveries.', 'data' => $packages]);
         
    }

    public function best_route(Request $request){
        
        $data = json_decode($request->get('data'));

        $user_info = json_decode($request->get('user_info'));
        $user_id = User::where('phone', $user_info->phone)->where('token',$user_info->token)->value('id');
        
        if (empty($data->current_lat) || empty($data->current_longi)) 
            return response()->json(['result_code' => 2, 'result_message' => 'Current GPS is mandatory', 'data' => '']);  


        $packages = DB::table('deliveries')
                            ->select('deliveries.track_id', 'packages.recipient_name', 'packages.resi_number',
                                'packages.recipient_address', 'deliveries.destination_lat as lat_address',  
                                'deliveries.destination_longi as longi_address'
                                )
                            ->join('packages','packages.id','=','deliveries.package_id')
                            ->whereIn('deliveries.status',[1,2,3])
                            ->where('deliveries.courrier_id', $user_id)
                            ->orderBy('deliveries.created_at','desc')
                            ->get();

        if (count($packages) == 0) 
            return response()->json(['result_code' => 2, 'result_message' => 'List deliveries not found', 'data' => array()]);

        $i = 1;
        foreach($packages as $row){
            $row->sequence = $i;
            $i=$i+1;
        }

        return response()->json(['result_code' => 1, 'result_message' => 'List deliveries.', 'data' => $packages]);
         
    }


    public function list_history(Request $request){

        $user_info = json_decode($request->get('user_info'));
        $user_id = User::where('phone', $user_info->phone)->where('token',$user_info->token)->value('id');
        $recipient_image = url('/')."/public/images/recipient.png";
        $packages = DB::table('deliveries')
                            ->select('deliveries.track_id', 'packages.recipient_name', 'packages.resi_number', 'deliveries.delivered_at',
                                DB::raw('"'.$recipient_image.'" as recipient_image'))
                            ->join('packages','packages.id','=','deliveries.package_id')
                            ->whereIn('deliveries.status',[3])
                            ->where('deliveries.courrier_id', $user_id)
                            ->orderBy('deliveries.updated_at','desc')
                            ->get();

        if (count($packages) == 0) 
            return response()->json(['result_code' => 2, 'result_message' => 'History delivery not found', 'data' => array()]);

        return response()->json(['result_code' => 1, 'result_message' => 'List history delivered packages.', 'data' => $packages]);
         
    }

    public function set_destination(Request $request){
        $data = json_decode($request->get('data'));

        if (empty($data->track_id)) 
            return response()->json(['result_code' => 2, 'result_message' => 'Track ID is mandatory', 'data' => '']); 

        if (empty($data->destination_lat) || empty($data->destination_longi)) 
            return response()->json(['result_code' => 2, 'result_message' => 'GPS destination is mandatory', 'data' => '']);  

        // update package status to in-progress
        Deliveries::where('track_id', $data->track_id)->update([
                'destination_lat' => $data->destination_lat,
                'destination_longi' => $data->destination_longi, 
                'updated_at' => date('Y-m-d H:i:s')]); 

        return response()->json(['result_code' => 1, 'result_message' => 'Update GPS package success.', 'data' => '']);
    }
	
}
