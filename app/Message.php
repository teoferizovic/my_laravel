<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    use SoftDeletes;

    protected $table = 'messages';

    public function sender() {
        return $this->belongsTo('App\User');
    }

    public function reciver() {
        return $this->belongsTo('App\User');
    }

    public function reply_to_message() {
        return $this->belongsTo('App\Message','reply_id');
    }

    public function get(int $id) { 
		return $this->with(['sender','reciver','reply_to_message'])->where('id', $id)->first(); 
	}

    public function getAll() {
		return $this->with(['sender','reciver','reply_to_message'])->get();
	}

    public function new(array $data) : bool {

        $datas = [];

        foreach ($data['reciver_ids'] as $value) {
            $datas[] = ['title' => $data['title'],'body' => $data['body'], 'reply_id' => $data['reply_id'],'sender_id' => $data['sender_id'],'reciver_id' => $value, 'created_at' => date('Y-m-d H:i:s')];
        }

        return ($this::insert($datas)) ? true : false;
    }

	public function edit($message,array $data):bool { 
	  	
	  	$message->status = $data['status']; 

	  	return ($message->save()) ? true : false;
	}

	public function remove(int $id) : bool {

		return DB::table('messages') 
		->where('id',$id)
		->orWhere('reply_id',$id)
		->update(['status' => 'D','deleted_at' => date('Y-m-d H:i:s')]);
	}  

}
