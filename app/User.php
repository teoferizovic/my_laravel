<?php

namespace App;

use Config;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user_images(){
        return $this->hasMany('App\UserImage');
    }

    public function orders(){
        return $this->hasMany('App\Order')->select(array('id', 'status','user_id','final_price'));
    }


    public function routeNotificationForSlack(){
       return config('app.slack_webhook'); 
    }
    

    
}
