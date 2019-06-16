<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Services\RedisService;

class CheckPermissions
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
        $routes = [
          "get" => "read",
          "post" => "create",
          "put" => "update",
          "delete" => "delete",
        ];
        
        $authToken = str_replace("Bearer ","",$request->header('Authorization'));
        
        $redisAclKey = RedisService::getValue($authToken)."-"."ACL";
        $permissionsJson = json_decode(RedisService::getValue($redisAclKey,'acl-cache'));
        $permissionsArr = (array) $permissionsJson;
        
        //$routes[strtolower($request->method())]
        /*$results = DB::select('SELECT p.name FROM users as u inner join roles as r on u.role_id = r.id inner join permissions as p on r.id = p.role_id where u.id=5 and p.name=:name', ['name' => $routes[strtolower($request->method())]]);*/

        if(!$permissionsArr[$routes[strtolower($request->method())]])
            return \Response::json(['message' => 'Forbidden!'], 403);

        return $next($request);
    }
}

