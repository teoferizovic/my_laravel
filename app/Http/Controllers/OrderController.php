<?php

namespace App\Http\Controllers;

use Config;
use App\Order;
use App\User;
use App\OrderProduct;
use Illuminate\Http\Request;
use App\Notifications\Newslack;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendEmail;

class OrderController extends Controller
{
     
	 protected $order;

     public function __construct(Order $order){
         $this->order = $order;
     }

     public function index(){
	 	
	 	$orders = Order::with(['products'])->where('status', "F")->get();
    	return \Response::json($orders,200);

	 }

     public function index1(){
        
        $orders = Order::status()->price('60')->get();
        //$orders = Order::whereFinalPrice(40)->get();
        return \Response::json($orders,201);

     }

     public function orders_users($id){

         $order_user = $this->order->orders_users($id);
         return \Response::json(($order_user != null) ? ['data' => $order_user] : ['data'=>[]],200);
       
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

        $order->status       = 		($status==null) ? "P" : $status;
        $order->final_price  = 		$final_price;
        
        if ($order->save()) {
            //batch update
            if(OrderProduct::where('order_id', $id)->update(['status' => 'P'])>0){
                //$this->sendEmail($order);
                SendEmail::dispatch($order);
                return \Response::json(['message' => 'Successfully saved item!'], 200);
            }
        }

        
    }

    public function checkout($id=null){

         $order = Order::findOrFail($id);

         $order->status  =  'F';

         if ($order->save()){
            if(OrderProduct::where('order_id', $id)->update(['status' => 'F'])>0){
                //slack notifications
                User::findOrFail($order->user_id)->notify(new Newslack("New order has been finalized"));
                //publish order to redis channel
                //Redis::publish('finished_order', json_encode($order));
                return view('confirm_order');
            }
         }

    }

    public function sendEmail(Order $order){
       
        $user = User::findOrFail($order->user_id);
             
        $vals = [];

        $vals['id']         =    $user->id;
        $vals['name']       =    $user->name;
        $vals['email']      =    $user->email;
        $vals['order_id']   =    $order->id;
        
        Mail::send([],$vals, function ($message) use($vals) {
            $message->from($vals['email'], 'Laravel');
            $message->subject('Confirm order');
            $message->setBody("<p>Confirm your order clicking on </p> <a href='".config('app.url').":8000/orders/checkout/".$vals['order_id']."' target='_blank'>Link</a>", 'text/html');
            $message->to('feriz2013@hotmail.com')->cc('bar@example.com');
        });

    }
}
