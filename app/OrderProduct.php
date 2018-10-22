<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $dates = ['deleted_at'];
    protected $table = 'order_products';
}
