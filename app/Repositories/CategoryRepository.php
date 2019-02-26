<?php
namespace App\Repositories;

use App\Category;

class CategoryRepository implements CRUDRepositoryInterface
{

	public function all(){
      
        return Category::all();

    }

    public function get(int $id){
       
        return Category::where('id', $id)->first();

    }

    public function delete($category):bool {
        
        $category->delete();
        return true;

    }

    public function create(array $data):bool {

    	$category = new Category();

    	$category->name         =   $data['name'];
    	$category->description  =   $data['description'];

    	$category->save();
    	return true;
    }

    public function update($category,array $data):bool {

    	$category->name         =   $data['name'];
    	$category->description  =   $data['description'];

    	$category->save();
    	return true;
    }


}