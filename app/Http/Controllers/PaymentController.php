<?php

namespace App\Http\Controllers;

use Helper;
use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $payment;
    
    public function __construct(Payment $payment){
        $this->payment = $payment;
    }

    public function create(Request $request){   
        
        $input = $request->all();
        
        //checking if standard or batch insert
        if(((Helper::isAssoc($input)) ? $this->payment->store($input) : $this->payment->batchStore($input))==false){
                return \Response::json(['message' => 'Bad request!'], 400);
        }

        return \Response::json(['message' => 'Successfully saved item!'], 200);

    }

}
