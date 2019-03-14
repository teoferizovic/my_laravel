<?php

namespace App\Http\Controllers;

use App\FOrder;
use Illuminate\Http\Request;
use App\Http\Requests\Custom\CustomFOrderRequest;

class FOrderController extends Controller
{
    protected $forder;
    
    public function __construct(FOrder $forder){
        $this->forder = $forder;
    }

    public function create(){
        
        $request = Request();

        $validationArr = CustomFOrderRequest::validate($request->all());
        
        if ($validationArr['validated'] == false){
             return \Response::json(['message' => 'Bad Request!','errors' => $validationArr['errors']], 400);
        }
		
        $input = $request->all();
            
        if(!$this->forder->new($input)){
             return  \Response::json(['message' => ' Internal Server Error!'], 500);
        }
        
    	return \Response::json($input,201);
    }
}
