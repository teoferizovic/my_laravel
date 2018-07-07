<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use Config;
use Illuminate\Support\Facades\Redis;

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
    	$user->password = md5($input['password']);

    	$user->save();

    	return \Response::json(['message' => 'Successfully saved item!'], 200);
    	
    }

    public function login(){

    	$request = Request();
    	$input = $request->all();

    	if ((isset($input['email']) == false) or (isset($input['password']) == false)) {
    		 return \Response::json(['message' => 'Bad Request!'], 400);
    	}

    	$user = User::where('email', $input['email'])
		->where('password', md5($input['password']))
		->first();

		if($user == null) {
			 return \Response::json(['message' => 'Not Found!'], 404);
		}

		//create auth_token and save it in Redis
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
