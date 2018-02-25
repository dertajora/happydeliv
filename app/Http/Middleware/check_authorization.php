<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class check_authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = $request->header('Authorization');
        if (empty($headers)) {
            return response()->json(['result_code' => 300, 'result_message' => 'Invalid Authorization']);
        } 

        $header = explode(" ", $headers);
        if ($header[0] != "Basic") {
            return response()->json(['result_code' => 300, 'result_message' => 'Invalid Authorization']);
        }

        $session_search = DB::table('api_credentials')->where('token_api',$header[1])->first();
        
        if (count($session_search) > 0) {
            $timeFirst  = strtotime($session_search->token_generated_time);
            $timeSecond = strtotime(date('Y-m-d H:i:s'));
            $differenceInSeconds = $timeSecond - $timeFirst;
            if ($differenceInSeconds > 3600) {
                return response()->json(['result_code' => 100, 'result_message' => 'Session Expired']);
            }

            return $next($request);
        }else{
            return response()->json(['result_code' => 300, 'result_message' => 'Invalid Credential']);
        }
        
    }
}
