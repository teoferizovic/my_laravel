<?php

namespace App\Jobs;

use Config;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmailResetPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
        $vals = [];

        $vals['id']            =    $this->user->id;
        $vals['name']          =    $this->user->name;
        $vals['email']         =    $this->user->email;
        $vals['forgot_token']  =    $this->user->forgot_token;
        
        Mail::send([],$vals, function ($message) use($vals) {
            $message->from($vals['email'], 'Laravel');
            $message->subject('Confirm order');
            $message->setBody("<p>Reset your password on </p> <a href='".config('app.url').":4200/reset-password/".$vals['forgot_token']."' target='_blank'>Link</a>", 'text/html');
            $message->to('feriz2013@hotmail.com')->cc('bar@example.com');
        });
        
    }

}
