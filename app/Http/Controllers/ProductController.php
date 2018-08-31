<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    public function index($id=null){
    	
        if($id != null){
    		$product = Product::where('id', $id)->where('status','>', 0)->first();
    		$products[] = $product;
    		return \Response::json($products,201);
    	}

    	$products = Product::where('status','>', 0)->get();
    	return \Response::json($products,201);
    }
}


//interesting query
//select u.email, ui.user_id, count(*) as total from user_images as ui inner join users as u on ui.user_id=u.id group by ui.user_id ORDER BY `total` DESC ;