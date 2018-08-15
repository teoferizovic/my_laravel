<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    //
    protected $table = 'user_images';

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
