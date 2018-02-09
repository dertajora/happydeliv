<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Watchlist;
use App\Deliveries;

class TrackController extends Controller
{
	public function __construct(){
	    ini_set('max_input_time', 6000);
        ini_set('max_execution_time', 100000);
        ini_set('memory_limit', '1024M');
	}

    public function add_package(Request $request){

    	$data = json_decode($request->get('data'));
        $user_info = json_decode($request->get('user_info'));
        $user_id = User::where('phone', $user_info->phone)->where('token',$user_info->token)->value('id');

        
        $package = DB::table('deliveries')->select('packages.recipient_phone', 'deliveries.id as delivery_id')
                            ->join('packages','packages.id','=','deliveries.package_id')->where('track_id', $data->track_id)->first();

        $check_watchlist_user = DB::table('watchlist')->join('deliveries','deliveries.id','=','watchlist.delivery_id')
                        ->where('track_id', $data->track_id)->where('user_id', $user_id)->count();

        if ($check_watchlist_user > 0) 
            return response()->json(['result_code' => 2, 'result_message' => 'Track package failed. Package already tracked!', 'data' => '']);
        
        if (count($package) == 0) 
            return response()->json(['result_code' => 2, 'result_message' => 'Track package failed. Package not found!', 'data' => '']);
        
        if ($user_info->phone != $package->recipient_phone) 
            return response()->json(['result_code' => 2, 'result_message' => 'Track package failed. Recipient phone number is different with user phone number', 'data' => '']);

        
        $watchlist = new Watchlist; 
        $watchlist->delivery_id = $package->delivery_id;
        $watchlist->user_id = $user_id;
        $watchlist->save();

        return response()->json(['result_code' => 1, 'result_message' => 'Track package success.', 'data' => '']);
         
    }

    public function list_package(Request $request){

        $user_info = json_decode($request->get('user_info'));
        $user_id = User::where('phone', $user_info->phone)->where('token',$user_info->token)->value('id');
        
        $packages = DB::table('watchlist')
                            ->select('companies.name', 'companies.profile_photo', 'packages.resi_number', 'deliveries.track_id',
                                DB::raw('IF(deliveries.status = 1, "Pending", "On the way with courrier") as status') )
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
        
        $packages = DB::table('watchlist')
                            ->select('companies.name', 'companies.profile_photo', 'packages.resi_number', 'deliveries.track_id','delivered_at')
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
