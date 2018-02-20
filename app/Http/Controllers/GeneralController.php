<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Companies;
use App\Roles;
use Illuminate\Support\Facades\Crypt;
use Auth;


class GeneralController extends Controller
{
	public function __construct(){
	    ini_set('max_input_time', 6000);
        ini_set('max_execution_time', 100000);
        ini_set('memory_limit', '1024M');
	}

    public function test_push_notif(Request $request){

        $registration_id = $_GET['id'];
       
        #API access key from Google API's Console
            // API access key from Google API's Console
            define( 'API_ACCESS_KEY', 'AIzaSyAdMZMj0ejfLwev-kBm1R98Br_1I8zG8fM' );
            $registrationIds = $_GET['id'];
        #prep the bundle
             $msg = array
                  (
                'body'  => 'Body  Of Notification',
                'title' => 'Title Of Notification',
                        'icon'  => 'myicon',/*Default Icon*/
                        'sound' => 'mySound'/*Default sound*/
                  );
            $fields = array
                    (
                        'to'        => $registrationIds,
                        'notification'  => $msg
                    );
            
            
            $headers = array
                    (
                        'Authorization: key=' . API_ACCESS_KEY,
                        'Content-Type: application/json'
                    );
        #Send Reponse To FireBase Server    
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                $result = curl_exec($ch );
                curl_close( $ch );
        #Echo Result Of FireBase Server
        echo $result;

    }
	
}
