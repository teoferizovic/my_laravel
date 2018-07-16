<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

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


        $results = DB::select('SELECT p.name FROM users as u inner join roles as r on u.role_id = r.id inner join permissions as p on r.id = p.role_id where u.id=5 and p.name=:name', ['name' => $routes[strtolower($request->method())]]);

        if(empty($results))
            return \Response::json(['message' => 'Forbidden!'], 403);

        return $next($request);
    }
}
