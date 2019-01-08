<?php

namespace App\Http\Controllers;

use App\UserLog;

class UserLogController extends Controller
{
    //https://laravel.com/docs/5.3/eloquent-relationships#eager-loading
    public static function create(array $input) : bool {
    	
    	$userLog = new UserLog();

    	$userLog->user_id    = $input['user_id'];
    	$userLog->ip_address = $input['ip_address'];
    	
    	if ($userLog->save()) {
    		return true;
    	}

    	return false;
    }
}
