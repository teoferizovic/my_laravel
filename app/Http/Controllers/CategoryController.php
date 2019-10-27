<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\DB;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    
    protected $category;
    
    public function __construct(CategoryRepository $category){
        $this->category = $category;
    }


    public function index($id=null){
    	
        if($id != null){
    		$category = $this->category->get($id);
            return \Response::json(($category==null) ? [] : [$category],200);
    	}

        $categories  = $this->category->all();
    	return \Response::json($categories,200);
    }

    /*public function store($id=null,Request $request){
        
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
        
    }*/

    public function create(Request $request){
    	
        $data = $request->all();
    	
    	if (isset($data['name']) == false) {
    		 return \Response::json(['message' => 'Bad Request!'], 400);
    	}

        $this->category->create($data);
    	
    	return \Response::json(['message' => 'Successfully saved item!'], 201);
    	
    }

    public function update($id,Request $request){

		$category = $this->category->get($id);
    	
    	if ($category==null){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}
    	
        $data = $request->all();

        $this->category->update($category,$data);

    	return \Response::json(['message' => 'Successfully updated item!'], 200);

    }

    public function delete($id){
    	
    	$category = $this->category->get($id);
        
    	if ($category==null){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}
       
        $this->category->delete($category);
		
		return \Response::json(['message' => 'Successfully deleted item!'], 200);
    }

}
