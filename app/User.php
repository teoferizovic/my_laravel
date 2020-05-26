<?php

namespace App;

use Config;
use Illuminate\Support\Facades\Hash;
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

    public function user_logs(){
        return $this->hasMany('App\UserLog');
    }

    public function orders(){
        return $this->hasMany('App\Order')->select(array('id', 'status','user_id','final_price'));
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }

    public function new(array $data) : User {

        $user = new $this;
        
        $user->name       =      isset($data['name']) ? $data['name'] : '';
        $user->email      =      $data['email'];
        $user->password   =      Hash::make($data['password']);
        $user->role_id    =      isset($data['role_id']) ? $data['role_id'] : Config::get('constants.role.default');
        $user->save();
        
        return $user;
    }

    public function get(int $id) { 
        return $this->with(['user_images','orders','user_logs'=> function ($query) {
                $query->orderBy('id', 'desc')->limit(1);
            }])->where('id', $id)->first();
    }

    public function getAll() {
        return $this->with(['user_images','orders','user_logs'  => function ($query) {
            $query->orderBy('id', 'desc')->limit(1);
        }])->get();
    }

    public function getBy(string $value, string $data) {
        return $this->where($value, $data)->first();
    }

    public function getByArray(string $value, array $array) {
        return $this->whereIn($value, $array)->get();
    }

    public function routeNotificationForSlack(){
       return config('app.slack_webhook'); 
    }
    
}
