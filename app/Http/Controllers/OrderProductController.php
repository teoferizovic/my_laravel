<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderProduct;

class OrderProductController extends Controller
{
    public function create(){
        
        $request = Request();
        $input = $request->all();

        if ((isset($input['order_id']) == false) or (isset($input['product_id']) == false)) {
             return \Response::json(['message' => 'Bad Request!'], 400);
        }

        $order_product = new OrderProduct();

    	$order_product->order_id = $input['order_id'];
    	$order_product->product_id = $input['product_id'];
    	
    	if ($order_product->save()) {
    		return \Response::json($order_product,201);
    	}
    	
    }
}
