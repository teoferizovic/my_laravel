<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    
    public function roles(){  	
    	return $this::all();
    }

     public function role($id){
    	return $this::where('id', $id)->get(); 
    }

}
