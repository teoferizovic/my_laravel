<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisService
{

	//to do
	//add redis connection in constructor
	
	public static function setValue(string $key,string $value, $connection=null) : bool {
		$redisCinnection = ($connection==null) ? 'default' : $connection;
		$redis = Redis::connection($redisCinnection);
		$redis->set($key, $value);
		return true;
	}

	public static function getValue(string $key,$connection=null) : string {
	     $redisCinnection = ($connection==null) ? 'default' : $connection;
	     $redis = Redis::connection($redisCinnection);		
		 $value = $redis->get($key);
		 return ($value != null) ? $value : '';
	}

	public static function removeValue(string $key) : bool {
		Redis::del($key);
		return true;
	}

}