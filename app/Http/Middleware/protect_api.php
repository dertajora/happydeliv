<?php

namespace App\Http\Middleware;
use App\User;
use Closure;

class protect_api
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
        $user_info = json_decode($request->get('user_info'));
        
        if (!isset($user_info) || empty($user_info->phone) || empty($user_info->token) ) {
            return response()->json(['result_code' => 2, 'result_message' => 'Invalid Parameter!', 'data' => '']);
        }

        $user_validation = $this->checkUser($user_info);
        if ($user_validation) {
            return $next($request);
        }else{
            return response()->json(['result_code' => 2, 'result_message' => 'User info not valid!', 'data' => '']);
        }


    }

    public function checkUser($user_info){
        $user = User::where('phone', $user_info->phone)->where('token',$user_info->token)->count();
        if ($user > 0) {
            return true;
        }

        return false;
    }
}
