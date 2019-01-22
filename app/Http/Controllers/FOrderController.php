<?php

namespace App\Http\Controllers;

use App\FOrder;
use Illuminate\Http\Request;
use App\Http\Requests\Custom\CustomFOrderRequest;

class FOrderController extends Controller
{
    public function create(){
        
        $request = Request();

        $validationArr = CustomFOrderRequest::validate($request->all());
        
        if ($validationArr['validated'] == false){
             return \Response::json(['message' => 'Bad Request!','errors' => $validationArr['errors']], 400);
        }
		
        $input = $request->all();
            
        $f_order = new FOrder();

    	$f_order->user_id 	 	 =  	$input['user_id'];
    	$f_order->order_id 	 	 =  	$input['order_id'];
    	$f_order->payment_id 	 =  	$input['payment_id'];
    	$f_order->final_price    =  	$input['final_price'];
    	$f_order->created_at     =  	$input['created_at'];
    	
    	if ($f_order->save()) {
    		return \Response::json($f_order,201);
    	}
    	
    }
}
