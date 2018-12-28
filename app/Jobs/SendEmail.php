<?php

namespace App\Jobs;

use Config;
use App\Order;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order){
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        
        $user = User::findOrFail($this->order->user_id);
             
        $vals = [];

        $vals['id']         =    $user->id;
        $vals['name']       =    $user->name;
        $vals['email']      =    $user->email;
        $vals['order_id']   =    $this->order->id;
        
        Mail::send([],$vals, function ($message) use($vals) {
            $message->from($vals['email'], 'Laravel');
            $message->subject('Confirm order');
            $message->setBody("<p>Confirm your order clicking on </p> <a href='".config('app.url').":8000/orders/checkout/".$vals['order_id']."' target='_blank'>Link</a>", 'text/html');
            $message->to('feriz2013@hotmail.com')->cc('bar@example.com');
        });
    }
}
