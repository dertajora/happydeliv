<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Packages;
use App\Deliveries;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;


class PackageController extends Controller
{
	public function __construct(){
	    ini_set('max_input_time', 6000);
        ini_set('max_execution_time', 100000);
        ini_set('memory_limit', '1024M');
	}

    public function home(){

        // select all packages
        if (Auth::user()->role_id == 4) {
            $packages = DB::table('packages')
                        ->select('recipient_name','recipient_address','recipient_phone','resi_number','packages.created_at','users.name as created_by')
                        ->join('users','users.id','=','packages.created_by')
                        ->where('users.company_id', Auth::user()->company_id)->paginate(10);
        }else{
            $packages = DB::table('packages')->where('created_by', Auth::user()->id)->paginate(10);
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $data['number'] = 10 * ($currentPage - 1) + 1;
        
        $data['packages'] = $packages;
        $data['list_employees'] = DB::table('users')->select('id','name')->where('company_id', Auth::user()->company_id)->get();
        return view('package.index', $data);
    }

    public function add(){
        return view('package.add');
    }

    public function save(Request $request){

        $package = new Packages;
        $package->recipient_phone = $request->get('recipient_phone');
        $package->recipient_address = $request->get('recipient_address');
        $package->recipient_name = $request->get('recipient_name');
        $package->resi_number = $request->get('resi_number') ;
        $package->company_id = Auth::user()->company_id ;
        $package->created_by = Auth::user()->id;
        $package->created_at = date('Y-m-d H:i:s');
        $package->save(); 


        $random = time() . rand(10*45, 100*98);
        $random_id = substr($random, 5);

        $deliveries = new Deliveries;
        $deliveries->track_id = $random_id;
        $deliveries->package_id = $package->id;
        $deliveries->status = 1;
        $deliveries->save();
        
        // send the recipient TRACK ID via Telkom SMS Notification
        $this->send_sms_notification($request->get('recipient_phone') , $random_id);

        return redirect('manage_packages')->with('status', 'Package has been added');
    }

    public function deliveries(){
        $deliveries = DB::table('deliveries')
                        ->select('deliveries.track_id', 'packages.resi_number', 'deliveries.status','deliveries.courrier_id','users.name as courrier_name',
                                 'deliveries.current_lat','deliveries.current_longi', 'deliveries.delivered_at')
                        ->join('packages','packages.id','=','deliveries.package_id')
                        ->join('users','users.id','=','packages.created_by')
                        ->orderBy('deliveries.created_at','desc')
                        ->where('packages.company_id', Auth::user()->company_id);

        if (!empty($_GET['keyword'])) {
            $deliveries = $deliveries->WhereRaw('track_id = '.$_GET['keyword']. ' OR resi_number = '.$_GET['keyword'] );
        }

        $data['deliveries'] = $deliveries->paginate(10);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $data['number'] = 10 * ($currentPage - 1) + 1;

        return view('package.deliveries', $data);
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
