<?php 

namespace App\Helpers;

class Helper
{
    public static function discountPrice(float $price,float $discount) : float {
        
        return  $price - ($price * ($discount / 100));
        
    }

    public static function isAssoc($arr) {

        return array_keys($arr) !== range(0, count($arr) - 1);
    
    }

}