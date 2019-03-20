<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function ratings()
    {
        return $this->hasMany('App\Rating');
    }

    public function products(){  	
    	return $this::with(['category'])->where('status','>', 0)->paginate(5);
    }

    public function product($id){
    	return $this::with(['category','ratings'])->where('id', $id)->where('status','>', 0)->get(); 
    }

    public function rating($id){
        return \DB::select("SELECT sum(r.rating) / COUNT(r.id) as rating FROM ratings as r WHERE r.product_id=".$id);
    }

    public function search($params){
    	return $this::with(['category'])->where("$params[0]", $params[1])->where('status','>', 0)->get(); 
    }


}
