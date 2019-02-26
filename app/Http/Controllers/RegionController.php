<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\SubRegionController;
use App\Http\Requests\Custom\CustomRegionRequest;
use App\Repositories\RegionRepository;

class RegionController extends Controller
{
    
    protected $region;
    
    public function __construct(RegionRepository $region){
        $this->region = $region;
    }

    public function index($id=null){
    	
        if($id != null){
            $region = $this->region->get($id);
            return \Response::json(($region == null) ? [] : [$region],201);
        }

        $regions  = $this->region->all();
        return \Response::json($regions,201);
        
    }

    
    public function create(Request $request){

        $input = $request->all();
        
        $validationArr = CustomRegionRequest::validate($input);
        
        if ($validationArr['validated'] == false){
             return \Response::json(['message' => 'Bad Request!','errors' => $validationArr['errors']], 400);
        }
    	  	   	
        $this->region->create($input);
    	
    	return \Response::json(['message' => 'Successfully saved item!'], 200);
    	
    }

    public function update($id,Request $request){
		
		$region = $this->region->get($id);
    	
    	if ($region==null){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}

        $input = $request->all();

        $validationArr = CustomRegionRequest::validate($input);
        
        if ($validationArr['validated'] == false){
             return \Response::json(['message' => 'Bad Request!','errors' => $validationArr['errors']], 400);
        }


        $this->region->update($region,$input);

    	return \Response::json(['message' => 'Successfully updated item!'], 200);		

    }

    public function delete($id){
    	
    	$region = $this->region->get($id);

    	if ($region==null){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}

    	$this->region->delete($region);
      	
		return \Response::json(['message' => 'Successfully deleted item!'], 200);
    	
    }
}
