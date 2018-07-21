<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
    	$users = User::all();
    	return \Response::json($users,201);
    }

    public function create(){
    	
    	
    	$request = Request();
    	$input = $request->all();

    	if ((isset($input['email']) == false) or (isset($input['password']) == false)) {
    		 return \Response::json(['message' => 'Bad Request!'], 400);
    	}

    	$user = new User();

    	$user->name = "";
    	$user->email = $input['email'];
    	
    	$user->password = password_hash($input['password'], PASSWORD_BCRYPT);
    	$user->role_id = isset($input['role_id']) ? $input['role_id'] : 2;

    	if($user->save()){

    		$allRoles = DB::select('SELECT p.name from permissions as p GROUP BY(p.name)');
    	
	    	  $userRoles = DB::select('SELECT p.name from users as u inner join roles as r on u.role_id = r.id inner join role_permissions as rp on r.id=rp.role_id inner join permissions as p 
	   			  on rp.permission_id = p.id where u.role_id=:role_id',["role_id" => $user->role_id]);

	    	$userRolesArr = [];
	    	$allRolesArr = [];
	    	$permissions = [];

	    	foreach ($userRoles as $role) {
	    		$userRolesArr[] = $role->name;
	    	}

	    	
	    	foreach ($allRoles as $role) {
	    		$allRolesArr[] = $role->name;
	    	}

	    	foreach ($allRolesArr as $role) {
	   
	    		if(in_array($role, $userRolesArr)){
	    			$permissions[$role] = true;
	    		} else {
	    			$permissions[$role] = false;
	    		}
	   
	    	}
	    	
	    	$redisAclKey = $user->email."-"."ACL";
	    	
	    	Redis::set($redisAclKey,json_encode($permissions));

    		return \Response::json(['message' => 'Successfully saved item!'], 200);
    	}

    	
    }

    public function login(){

    	$request = Request();
    	
    	$input = $request->all();

    	if ((isset($input['email']) == false) or (isset($input['password']) == false)) {
    		 return \Response::json(['message' => 'Bad Request!'], 400);
    	}

    	$user = User::where('email', $input['email'])->first();

		if($user == null or !password_verify($input['password'], $user->password)) {
			 return \Response::json(['message' => 'Not Found!'], 404);
		}

		$authToken = str_random(60);
		Redis::set($authToken, $user->email);

		$user->api_token = $authToken;
		
    	return \Response::json($user, 200);

    }


    public function logout($token=null){

    	if($token==null) {
    		return \Response::json(['message' => 'Bad Request!'], 400);
    	}

    	//find auth_token in Redis and delete it
    	$user = Redis::get($token);
    	
    	if ($user == null){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}

    	Redis::del($token);

    	return \Response::json(['message' => 'Successfully logged out!'], 200);
    }
}
