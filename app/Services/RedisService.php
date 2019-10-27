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

	public static function setExpire(string $key,int $expire, $connection=null) : bool {
		$redisCinnection = ($connection==null) ? 'default' : $connection;
		$redis = Redis::connection($redisCinnection);
		$redis->expire($key, $expire);
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

	public static function getValues() : array {
		
		$allKeys = Redis::keys('*');
		
		$values = [];

        foreach ($allKeys as $key) {
             $values[] = Redis::get($key);
        }
          
        return array_unique($values);
		
	}

	public static function searchByPattern(int $id,$connection=null) : array {
		
		$redisCinnection = ($connection==null) ? 'default' : $connection;
		$redis = Redis::connection($redisCinnection);

		$pattern = '#'.$id.'#*';

		$allKeys = $redis->scan(0, 'match', $pattern);

		$values = [];

        foreach ($allKeys[1] as $key) {
             $values[] = $redis->get($key);
        }

		return $values;
	}

	public static function publishOnChannel(string $channel) : bool {
		Redis::publish($channel, json_encode(['foo' => 'bar']));
		return true;
	}

	//keys *#6#*
	//SCAN 0 match #6#*

}