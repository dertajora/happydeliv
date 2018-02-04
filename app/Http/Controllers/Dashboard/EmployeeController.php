<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Auth;
use Illuminate\Support\Facades\Crypt;


class EmployeeController extends Controller
{
	public function __construct(){
	    ini_set('max_input_time', 6000);
        ini_set('max_execution_time', 100000);
        ini_set('memory_limit', '1024M');
	}

    public function home(){
        // select all users from his company exclude him
        
        $users = DB::table('users')->where('users.company_id', Auth::user()->company_id)->where('users.id','!=', Auth::user()->id )
                    ->select('roles.name as role', 'users.id', 'users.name' , 'users.email', 'users.phone')
                    ->join('roles','roles.id','=','users.role_id')
                    ->paginate(10);
    
        $data['users'] = $users;
        return view('employee.index', $data);
    }

    public function add(){
        return view('employee.add');
    }

    public function save(Request $request){

        $user = new User;
        $user->phone = $request->get('phone');
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->company_id = Auth::user()->company_id ;
        $user->password = Crypt::encrypt('123456');
        $user->role_id = $request->get('role');
        $user->save(); 

        return redirect('manage_employees')->with('status', 'Employee has been added'); ;
    }

    


	
}
