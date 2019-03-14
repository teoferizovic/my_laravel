<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FOrder extends Model
{

	public function new(array $input) : bool {

		$f_order = new FOrder();

    	$f_order->user_id 	 	 =  	$input['user_id'];
    	$f_order->order_id 	 	 =  	$input['order_id'];
    	$f_order->payment_id 	 =  	$input['payment_id'];
    	$f_order->final_price    =  	$input['final_price'];
    	$f_order->created_at     =  	$input['created_at'];
		
		return ($f_order->save()) ? true : false;

	}
    
}
