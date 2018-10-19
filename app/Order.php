<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   // use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $table = 'orders';
}
