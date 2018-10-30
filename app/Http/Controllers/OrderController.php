<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use App\OrderProduct;
use Illuminate\Http\Request;
use App\Notifications\Newslack;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
     
	 public function index(){
	 	
	 	$orders = Order::with(['products'])->where('status', "F")->get();
    	return \Response::json($orders,201);

	 }
	 
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
            //batch update
            if(OrderProduct::where('order_id', $id)->update(['status' => 'F'])>0){
                //slack notifications
                User::findOrFail($order->user_id)->notify(new Newslack("New order has been finalized"));
                return \Response::json(['message' => 'Successfully saved item!'], 200);
            }
        }

        
    }
}
