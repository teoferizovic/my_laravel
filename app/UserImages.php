<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserImages extends Model
{
    //
    protected $table = 'user_images';

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
