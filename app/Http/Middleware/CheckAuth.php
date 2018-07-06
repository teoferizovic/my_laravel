<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Redis;
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
        $header = $request->header('Authorization');
        Redis::set('teo', 'ferizovic', 'EX', 10);//expired in 10 sec
        //$user = Redis::get('name');
        die;
        if(!$user){
            return \Response::json(['message' => 'Unauthorized!'], 401);
        }
        var_dump($user);
        var_dump(str_replace("Bearer ","",$header));
        //$users = User::all();
        //var_dump($users);
        die;
        return $next($request);
    }
}
