<?php
namespace App\Http\Controllers\APIPublic;
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
        
        $header_authorization = $request->header('Authorization');
        dd($header_authorization); 
    }

    
	
}
