<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;
use Closure;

class log_web_request
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
        return $next($request);
    }


    public function terminate($request, $response)
    {
        Log::info('app.requests', 

            [
            'type' => 'Web',
            'request' => $request->all(), 
            'current_url' => url()->current(),
            'current_ip' => \Request::ip()]);
    }
}
