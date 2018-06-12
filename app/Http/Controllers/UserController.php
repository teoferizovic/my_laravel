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
    	var_dump(Config::get('constants.ip_address').':'.Config::get('constants.port').'/*');die;
    	var_dump("kako je majkaa");die;
    }
}
