<?php
 
namespace App\Notifications;
 
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
 
class Newslack extends Notification
{
   use Queueable;

   public $message;  

   public function __construct($message=null)
   {
       $this->message = $message;
   }
 
   public function via($notifiable)
   {
       return ['slack'];
   }
 
   public function toSlack($notifiable)
   {
       return (new SlackMessage)
           ->content($this->message." by $notifiable->email !!");
   }
 
 
}