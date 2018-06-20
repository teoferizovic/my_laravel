<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function index($id=null){
    	
    	if($id != null){
    		$category = Category::where('id', $id)->first();
    		return \Response::json($category,201);
    	}

    	$categories = Category::all();
    	return \Response::json($categories,201);
    }

    public function create(){
    	var_dump("hello from category");die;
    }

    public function update(){
    	var_dump("hello from category");die;
    }

    public function delete(){
    	var_dump("hello from category");die;
    }

}
