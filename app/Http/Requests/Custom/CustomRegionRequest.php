<?php

namespace App\Http\Requests\Custom;

use Illuminate\Support\Facades\Validator;

class CustomRegionRequest {
	
	public static function validate(array $request) : array {
		
		$validator = Validator::make($request, [
            'name'     		=> 'required|string',
		    'description'   => 'required|string',
		    'postal_code'   => 'required|int',
		    'sub_regions' 	=> 'required|array',
        ]);
        
        if ($validator->fails()) {
            return ['validated' => false, 'errors' => $validator->errors()];
        }
        	
        return ['validated' => true, 'errors' => null];
        

	}

}