<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $dates = ['deleted_at'];
    protected $table = 'order_products';

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    
}
