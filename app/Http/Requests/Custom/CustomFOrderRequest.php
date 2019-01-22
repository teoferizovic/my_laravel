<?php

namespace App\Http\Requests\Custom;

use Illuminate\Support\Facades\Validator;

class CustomFOrderRequest {
	
	public static function validate(array $request) : array {
		
		$validator = Validator::make($request, [
            'user_id'     => 'required|int',
		    'order_id'    => 'required|int',
		    'payment_id'  => 'required|int',
		    'final_price' => 'required',
		    'created_at'  => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return ['validated' => false, 'errors' => $validator->errors()];
        }
        	
        return ['validated' => true, 'errors' => null];
        

	}

}