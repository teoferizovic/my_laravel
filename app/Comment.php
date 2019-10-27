<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function product() {
        return $this->belongsTo('App\Product');
    }

    public function parent_comment() {
        return $this->belongsTo('App\Comment','parent_id');
    }


    public function new(array $data) : bool {

    	$comment = new $this;

    	$comment->comment 	 	 =  	$data['comment'];
    	$comment->user_id 	 	 =  	$data['user_id'];
    	$comment->product_id 	 =  	$data['product_id'];
    	$comment->parent_id    	 =  	$data['parent_id'];
		
		return ($comment->save()) ? true : false;

    }

    public function get(int $id) { 
		return self::where('id', $id)->first(); 
	}

	public function edit($comment,array $data):bool { 
	  	
	  	$comment->comment = $data['comment']; 

	  	return ($comment->save()) ? true : false;
	}

	public function getAll() {
		return $this->with(['user','product','parent_comment'])->get();
	}

	public function remove(int $id) {
		$this->where('id', $id)->delete();
	}  
}
