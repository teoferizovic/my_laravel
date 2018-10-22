<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $order_products = DB::table('order_products')->where('order_id', $id)->get();

        $final_price = 0;

        foreach ($order_products as $op) {
        	$final_price += $op->final_price;
        }

        $order->status       = 		($status==null) ? "F" : $status;
        $order->final_price  = 		$final_price;
 
        if ($order->save()) {
            return \Response::json(['message' => 'Successfully saved item!'], 200);
        }

        
    }
}
