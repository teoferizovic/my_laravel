<?php 
namespace App\Repositories; 
use App\Region; 
use App\Http\Controllers\SubRegionController; 

class RegionRepository implements CRUDRepositoryInterface{ 

	public function all(){ 
		
		return Region::all(); 

	} 

	public function get(int $id){ 
	
		return Region::where('id', $id)->first(); 
	
	} 
	
	public function delete($region):bool {
	
	 	SubRegionController::delete($region->id);

	 	$region->delete(); 
	 	return true; 
	
	}

	public function create(array $data):bool { 

	 	$region = new Region();
	 	
	 	$region->name = $data['name']; 
	 	
	 	$region->description = $data['description'];
	 	  
	 	$region->postal_code = $data['postal_code']; 
	 	  
	 	$region->save(); 

	 	 if(!empty($data['sub_regions'])){
	 	   		if(!SubRegionController::batchInsert($data['sub_regions'],$region->id))
	 	   		 return false; 
	 	 } 

	 	return true; 
	 }


	public function update($region,array $data):bool { 
	  	
	  	$region->name = $data['name']; 

	  	$region->description = $data['description']; 
	  	
	  	$region->postal_code = $data['postal_code'];
	  	
	  	SubRegionController::delete($region->id); 
	  	
	  	if(!empty($data['sub_regions'])){ 
	  	 	if(!SubRegionController::batchInsert($data['sub_regions'],$region->id)) 
	  	 		return false;
	  	} 
	  	
	  	$region->save(); 

	  	 return true;
	} 

	 }