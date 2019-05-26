<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Redis;

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

    public static function activeUsers() :  array  {
         
         $tokenArr = ['9f3envf05aTWDQgdwMU0blJ69Cu0nRjuO5Gfd0P1QVPeYXjjRkDg5xD86ZWB','gd7c4M5MQdymtE0d6YzwZ93Y4VBOny8y2I2jSL8H5tcwdPUfWfHPPMxRWB1I','a9sLW4safXPmm9UOdo32ysh61JXH9j8IWfUHMlPC9uhWTyHlxIFXaFP95A1m','cACVfIb578y8EvJBSUBVauvyQ0mFeyBtLfIb8Nm9bFocPExdH2UmzcRgMHzO','cACVfIb578y8EvJBSUBVauvyQ0mFeyBtLfIb8Nm9bFocPExdH2UmzcRgMHzO'];

         $emailArr = [];

         foreach ($tokenArr as $value) {
             $emailArr[] = Redis::get($value);
         }
           
         return array_unique($emailArr);
    }

    public static function singleUser($users,int $id)  : array {

        $user = null; 
      
        foreach($users as $value) {
            if ($value->id == $id) {
                $user = $value;
                break;
            }
        }

        return ($user==null) ? [] :[$user];

    }

}