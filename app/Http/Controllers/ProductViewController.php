<?php

namespace App\Http\Controllers;

use App\ProductView;
use Illuminate\Http\Request;

class ProductViewController extends Controller
{
    public function index(){
    	
    	$products = ProductView::all();
    	return \Response::json($products,200);
    	
    }
}
