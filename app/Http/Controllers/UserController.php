<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use Config;

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

		$user->api_token = str_random(60);

    	$user->save();

    	return \Response::json($user, 200);

    }


    public function logout($token=null){

    	if($token==null) {
    		return \Response::json(['message' => 'Bad Request!'], 400);
    	}

    	$user = User::where('api_token', $token)->first();
    	
    	if ($user == null){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}

    	$user->api_token = null;

    	$user->save();

    	return \Response::json(['message' => 'Successfully logged out!'], 200);
    }
}
