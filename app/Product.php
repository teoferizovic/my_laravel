<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function products(){  	
    	return $this::with(['category'])->where('status','>', 0)->paginate(5);
    }

    public function product($id){
    	return $this::with(['category'])->where('id', $id)->where('status','>', 0)->get(); 
    }

    public function search($params){
    	return $this::with(['category'])->where("$params[0]", $params[1])->where('status','>', 0)->get(); 
    }


}
