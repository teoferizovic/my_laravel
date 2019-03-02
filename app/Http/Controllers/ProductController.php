<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Cache;
use Helper;

class ProductController extends Controller
{
    
    protected $product;

    public function __construct(Product $product){
        $this->product = $product;
    }

    public function index(Request $request,$id=null){
    	
        $queryParams = $request->query();

        //search by id
        if($id != null ){
    		$product = $this->product->product($id);
            return \Response::json(($product!=null) ? ['data' => $product] : ['data'=>[]],200);
    	}

        //logic for dinamic search, in this case by name or by price       
        if(isset($queryParams) && !isset($queryParams['page'])){
            
            $params = Helper::allowedParams(['name','price'],$queryParams);
                if(!empty($params)){
                    $product = $this->product->search($params);
                        return \Response::json(($product!=null) ? ['data' => $product] : ['data'=>[]],201);
                }
                
        }

        $products = $this->product->products();

        return \Response::json($products,200);
    }
    	
    }


//return ProductResource::make($product);
//return ProductResource::collection($products);
//$fullUrl = request()->fullUrl();
        
/*return Cache::remember($fullUrl, 2, function() use($products) {
    //return ProductResource::collection($products);
            return \Response::json($products,201);  
});*/


//interesting query
//select u.email, ui.user_id, count(*) as total from user_images as ui inner join users as u on ui.user_id=u.id group by ui.user_id ORDER BY `total` DESC ;