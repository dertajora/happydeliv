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
                        ->where('users.company_id', Auth::user()->company_id)->paginate(5);
        }else{
            $packages = DB::table('packages')->where('created_by', Auth::user()->id)->paginate(10);
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $data['number'] = 5 * ($currentPage - 1) + 1;
        
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

        return redirect('manage_packages')->with('status', 'Package has been added');
    }

    public function deliveries(){
        $deliveries = DB::table('deliveries')
                        ->select('deliveries.track_id', 'packages.resi_number', 'deliveries.status','deliveries.courrier_id','deliveries.current_lat','deliveries.current_longi', 'deliveries.delivered_at')
                        ->join('packages','packages.id','=','deliveries.package_id')
                        ->join('users','users.id','=','packages.created_by')
                        ->orderBy('deliveries.created_at','desc')
                        ->where('users.company_id', Auth::user()->company_id);

        if (!empty($_GET['keyword'])) {
            $deliveries = $deliveries->WhereRaw('track_id = '.$_GET['keyword']. ' OR resi_number = '.$_GET['keyword'] );
        }

        $data['deliveries'] = $deliveries->paginate(3);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $data['number'] = 5 * ($currentPage - 1) + 1;

        return view('package.deliveries', $data);
    }

    


	
}
