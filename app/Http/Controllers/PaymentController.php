<?php

namespace App\Http\Controllers;

use Helper;
use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    
    public function index(){
        //
    }

    public function create(Request $request){   
        
        $input = $request->all();
        
        //checking if standard or batch insert
        if(((Helper::isAssoc($input)) ? $this->store($input) : $this->batchStore($input))==false){
            return \Response::json(['message' => 'Bad request!'], 400);
        }

        return \Response::json(['message' => 'Successfully saved item!'], 200);

    }

    public function batchStore(array $input){
        
        if(!Payment::insert($input)){
            return false;
        }

        return true;

    }

    
    public function store(array $input){

        $payment = new Payment();
        $payment->name = $input['name'];
        
        if($payment->save()){
            return true;
        }

        return false;
        
    }

}
