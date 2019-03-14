<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubRegion;

class SubRegionController extends Controller
{
    public static function batchInsert(array $subRegions, int $regionId):bool {
    	
    	$subRegionArr = [];
    	$createdAt = now();

    	foreach ($subRegions as $key => $value) {
    		$subRegionArr[] = ['region_id' => $regionId,'name' => $value['name'],'created_at'=>$createdAt];
    	}

    	if(!SubRegion::insert($subRegionArr)){
    		return false;
    	}

    	return true;
    	
    }

    public static function delete($regionId):bool {
    	
        SubRegion::where('region_id', $regionId)->delete();
    	return true;
        
    }


}
