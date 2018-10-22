<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
     public function create(){
        
        $request = Request();
        $input = $request->all();

        if ((isset($input['user_id']) == false)) {
             return \Response::json(['message' => 'Bad Request!'], 400);
        }

        $order = new Order();

    	$order->user_id = $input['user_id'];
    	
    	if ($order->save()) {
    		return \Response::json($order,201);
    	}
    	
    }

    public function update($id=null,$status=null){
        
       
        $order = Order::findOrFail($id);
        
        $order->status  = ($status==null) ? "F" : $status;

        if ($order->save()) {
            return \Response::json(['message' => 'Successfully saved item!'], 200);
        }

        
    }
}
