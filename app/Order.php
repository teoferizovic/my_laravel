<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   // use SoftDeletes;

	  protected $dates = ['deleted_at'];
    protected $table = 'orders';

    public function scopeStatus($query)
    {
      return $query->where('status', 'F');
    }

    public function scopePrice($query,string $price)
    {
      return $query->where('final_price', $price);
    }

    public function order_products()
    {
        return $this->hasMany('App\OrderProduct');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product', 'order_products', 'order_id', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function orders_users($id){
         return $this::where('user_id',$id)->where(function($q) {
                $q->where('status', 'A')
                  ->orWhere('status', 'P');
          })->get();
    }
}
