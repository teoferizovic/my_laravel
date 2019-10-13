<?php

namespace App\Http\Requests\Custom;

use Illuminate\Support\Facades\Validator;

class CustomCommentRequest {
	
	public static function validate(array $request) : array {
		
		$validator = Validator::make($request, [
            'comment'     		=> 'required|string',
		    'user_id'   		=> 'required|int',
		    'product_id'   		=> 'required|int',
		    'parent_id' 		=> 'nullable|int',
        ]);
        
        if ($validator->fails()) {
            return ['validated' => false, 'errors' => $validator->errors()];
        }
        	
        return ['validated' => true, 'errors' => null];
        

	}

}