<?php

namespace App\Http\Controllers;

use App\FOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FOrderController extends Controller
{
    public function create(){
        
        $request = Request();
        
		$validator = Validator::make($request->all(), [
            'user_id'     => 'required|int',
		    'order_id'    => 'required|int',
		    'payment_id'  => 'required|int',
		    'final_price' => 'required',
		    'created_at'  => 'required|string',
        ]);
        
        if ($validator->fails()) {
           return \Response::json(['message' => 'Bad Request!'], 400);
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
