<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Region;
use App\Http\Controllers\SubRegionController;

class RegionController extends Controller
{
     public function index($id=null){
    	
        if($id != null){
    		$region = Region::with(['sub_regions'])->where('id', $id)->first();
    		return \Response::json($region,201);
    	}

    	$regions = Region::with(['sub_regions'])->get();
    	return \Response::json($regions,201);
    }

    
    public function create(Request $request){
    	
        $input = $request->all();
    	   	
    	$region = new Region();

    	$region->name = $input['name'];
    	$region->description = $input['description'];
    	$region->postal_code = $input['postal_code'];

    	$region->save();

    	if(!empty($input['sub_regions'])){
    		if(!SubRegionController::batchInsert($input['sub_regions'],$region->id))
    			return \Response::json(['message' => 'Bad Request!'], 400);
    	}
    	
    	return \Response::json(['message' => 'Successfully saved item!'], 200);
    	
    }

    public function update($id,Request $request){

		
		$region = Region::find($id);
    	
    	if ($region==null){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}

    	$input = $request->all();

    	$region->name = $input['name'];
    	$region->description = $input['description'];
    	$region->postal_code = $input['postal_code'];

    	SubRegionController::delete($id);
    	
    	if(!empty($input['sub_regions'])){
    		if(!SubRegionController::batchInsert($input['sub_regions'],$region->id))
    			return \Response::json(['message' => 'Bad Request!'], 400);
    	}

    	$region->save();
    	return \Response::json(['message' => 'Successfully updated item!'], 200);

		

    }

    public function delete($id){
    	
    	$region = Region::find( $id );
    	
    	if ($region==null){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}

    	SubRegionController::delete($id);
		$region->delete();
		
		return \Response::json(['message' => 'Successfully deleted item!'], 200);
    	
    }
}
