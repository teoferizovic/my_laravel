<?php

namespace App\Http\Controllers;

use App\Role;

class RoleController extends Controller
{
     
    protected $role;

    public function __construct(Role $role){
        $this->role = $role;
    }

    public function index($id=null){
	 	
	 	//search by id
        if($id != null ){
    		$role = $this->role->role($id);
            return \Response::json(($role!=null) ? ['data' => $role] : ['data'=>[]],200);
    	}

	 	$roles = $this->role->roles();

        return \Response::json(['data' => $roles],200);

	}

}
