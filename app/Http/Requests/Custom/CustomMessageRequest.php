<?php

namespace App\Http\Requests\Custom;

use Illuminate\Support\Facades\Validator;

class CustomMessageRequest {
	
	public static function validate(array $request) : array {
		
		$validator = Validator::make($request, [
            'title'     		=> 'required|string',
            'body'     			=> 'required|string',
		    'reply_id' 			=> 'nullable|int',
		    'sender_id'   		=> 'required|int',
		    'reciver_ids'   	=> 'required|array',
        ]);
        
        if ($validator->fails()) {
            return ['validated' => false, 'errors' => $validator->errors()];
        }
        	
        return ['validated' => true, 'errors' => null];
        

	}

}