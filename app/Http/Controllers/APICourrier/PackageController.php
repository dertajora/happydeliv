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

    public function list_package(Request $request){

        $user_info = json_decode($request->get('user_info'));
        $user_id = User::where('phone', $user_info->phone)->where('token',$user_info->token)->value('id');
        $base_url = url('/')."/public/partners/";
        $packages = DB::table('watchlist')
                            ->select('companies.name', 'packages.resi_number', 'deliveries.track_id',
                                DB::raw('IF(deliveries.status = 1, "Pending", "On the way with courrier") as status'),
                                DB::raw('IFNULL(concat("'.$base_url.'", companies.profile_photo), "'.$base_url.'company-dummy.png'.'" ) as profile_photo') )
                            ->join('deliveries','deliveries.id','=','watchlist.delivery_id')
                            ->join('packages','packages.id','=','deliveries.package_id')
                            ->join('companies', 'companies.id','=','packages.company_id')
                            ->where('watchlist.user_id', $user_id)
                            ->whereIn('deliveries.status',[1,2])
                            ->get();

        if (count($packages) == 0) 
            return response()->json(['result_code' => 2, 'result_message' => 'Packages not found', 'data' => '']);

        return response()->json(['result_code' => 1, 'result_message' => 'List tracked packages.', 'data' => $packages]);
         
    }


    public function list_history(Request $request){

        $user_info = json_decode($request->get('user_info'));
        $user_id = User::where('phone', $user_info->phone)->where('token',$user_info->token)->value('id');
        $base_url = url('/')."/public/partners/";
        $packages = DB::table('watchlist')
                            ->select('companies.name', 'packages.resi_number', 'deliveries.track_id','delivered_at',
                                    DB::raw('IFNULL(concat("'.$base_url.'", companies.profile_photo), "'.$base_url.'company-dummy.png'.'" ) as profile_photo') )
                            ->join('deliveries','deliveries.id','=','watchlist.delivery_id')
                            ->join('packages','packages.id','=','deliveries.package_id')
                            ->join('companies', 'companies.id','=','packages.company_id')
                            ->where('watchlist.user_id', $user_id)
                            ->whereIn('deliveries.status',[3])
                            ->get();

        if (count($packages) == 0) 
            return response()->json(['result_code' => 2, 'result_message' => 'History tracked packages not found', 'data' => '']);

        return response()->json(['result_code' => 1, 'result_message' => 'List history tracked packages.', 'data' => $packages]);
         
    }
	
}
