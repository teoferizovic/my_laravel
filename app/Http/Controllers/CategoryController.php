<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    

	 public function __construct(){
        //$this->middleware('auth:api');
     }   

    public function index($id=null){
    	
        if($id != null){
    		$category = Category::where('id', $id)->first();
    		return \Response::json($category,201);
    	}

    	$categories = Category::all();
    	return \Response::json($categories,201);
    }

    public function store($id=null,Request $request){
        
        $input = $request->all();

        $category = $request->isMethod('put') ? Category::findOrFail($id) :
        new Category;
        
        if (isset($input['name']) == false) {
             return \Response::json(['message' => 'Bad Request!'], 400);
        }

        $category->name = $input['name'];
        $category->description = $input['description'];

        if ($category->save()) {
            return \Response::json(['message' => 'Successfully saved item!'], 200);
        }
        
    }

    public function create(Request $request){
    	
        $input = $request->all();
    	
    	if (isset($input['name']) == false) {
    		 return \Response::json(['message' => 'Bad Request!'], 400);
    	}

    	$category = new Category();

    	$category->name = $input['name'];
    	$category->description = $input['description'];

    	$category->save();
    	return \Response::json(['message' => 'Successfully saved item!'], 200);
    	
    }

    public function update($id,Request $request){

		$category = Category::find($id);
    	
    	if ($category==null){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}

    	$input = $request->all();

    	$category->name = $input['name'];
    	$category->description = $input['description'];

    	$category->save();
    	return \Response::json(['message' => 'Successfully updated item!'], 200);

    }

    public function delete($id){
    	
    	$category = Category::find( $id );
    	
    	if ($category==null){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}

		$category->delete();
		return \Response::json(['message' => 'Successfully deleted item!'], 200);
    }

}
