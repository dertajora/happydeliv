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

        $package = DB::table('deliveries')->select('packages.recipient_phone', 'deliveries.id as delivery_id')
                            ->join('packages','packages.id','=','deliveries.package_id')->where('track_id', $data->track_id)->first();

        if (count($package) == 0) 
            return response()->json(['result_code' => 2, 'result_message' => 'Track package failed. Package not found!', 'data' => '']);
        
        if ($user_info->phone != $package->recipient_phone) 
            return response()->json(['result_code' => 2, 'result_message' => 'Track package failed. Recipient phone number is different with user phone number', 'data' => '']);

        $user_id = User::where('phone', $user_info->phone)->where('token',$user_info->token)->value('id');
        $watchlist = new Watchlist; 
        $watchlist->delivery_id = $package->delivery_id;
        $watchlist->user_id = $user_id;
        $watchlist->save();

        return response()->json(['result_code' => 1, 'result_message' => 'Track package success.', 'data' => '']);
        
    }
	
}
