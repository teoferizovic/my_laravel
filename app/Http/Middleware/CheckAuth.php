<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Services\RedisService;
use Illuminate\Http\Response;

class CheckAuth
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
        
        $authToken = str_replace("Bearer ","",$request->header('Authorization'));
        $user = RedisService::getValue($authToken);
        
        if(!$user){
            return \Response::json(['message' => 'Unauthorized!'], 401);
        }
       
        return $next($request);
    }
}
