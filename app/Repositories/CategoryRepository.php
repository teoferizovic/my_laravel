<?php
namespace App\Repositories;

use App\Category;

class CategoryRepository implements CRUDRepositoryInterface
{

	public function all(){
        
        return Category::all();

    }

    public function get($id){
        
        return Category::where('id', $id)->first();

    }

    public function delete($category){
        
        $category->delete();
        return true;

    }

    public function create($data){

    	$category = new Category();

    	$category->name         =   $data['name'];
    	$category->description  =   $data['description'];

    	$category->save();
    	return true;
    }

    public function update($category,$data){

    	$category->name         =   $data['name'];
    	$category->description  =   $data['description'];

    	$category->save();
    	return true;
    }


}