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

    public static function EagerLoadingOrders() : array {
    	
    	$userOrders = \DB::select('SELECT u.id as user_id,u.email,u.role_id,o.id as order_id,o.user_id as user_id2,o.final_price,o.status FROM users as u left join orders as o on u.id=o.user_id;');
        
        $orders = [];

        foreach ($userOrders as $key => $value) {
            if($value->order_id != null){
                $orders[$value->user_id][] = ['id'=>$value->order_id,'final_price'=>$value->final_price,'status'=>$value->status];
            }
               
        }

        $uniqueOrders = [];
        
        foreach ($userOrders as $obj) {
            $uniqueOrders[$obj->user_id] = $obj;
        }

        $finalDatas = [];

        foreach ($uniqueOrders as $un) {
             if ($un->order_id!=null){
                 $finalDatas[] = ['id' => $un->user_id,'email'=>$un->email,'role_id'=>$un->role_id,'orders'=>$orders[$un->user_id]]; 
             } 
             else{
                 $finalDatas[] = ['id' => $un->user_id,'email'=>$un->email,'role_id'=>$un->role_id,'orders'=>[]];
             }
        }

        return $finalDatas;
    }

    public static function allowedParams(array $params,array $queryParams) : array {
              
        $allowedParams = [];

        foreach ($params as $param) {
            if(isset($queryParams[$param])){
                $allowedParams[] = $param;
                $allowedParams[] = $queryParams[$param];
             }   
        }

        return $allowedParams;
    }

}