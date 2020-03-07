<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\User;
use App\Services\RedisService;


class AclService 
{
	public static function setPermissions(User $newUser):bool {
			
		$allRoles = DB::select('SELECT p.name from permissions as p GROUP BY(p.name)');
	
    	$userRoles = DB::select('SELECT p.name from users as u inner join roles as r on u.role_id = r.id inner join role_permissions as rp on r.id=rp.role_id inner join permissions as p 
   			  on rp.permission_id = p.id where u.role_id=:role_id',["role_id" => $newUser->role_id]);

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
    	
    	$redisAclKey = $newUser->email."-"."ACL";

    	RedisService::setValue($redisAclKey,json_encode($permissions),'acl-cache');

    	return true;

	}
}