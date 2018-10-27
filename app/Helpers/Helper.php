<?php 

namespace App\Helpers;

class Helper
{
    public static function discountPrice(float $price,float $discount) : float {
        
        return  $price - ($price * ($discount / 100));
        
    }
}