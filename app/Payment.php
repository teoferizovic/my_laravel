<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    
    protected $table = 'payments';

    public function store(array $input) : bool {

    	$payment = new Payment();
        $payment->name = $input['name'];
        
        if($payment->save()){
            return true;
        }

        return false;
    }

    public function batchStore(array $input) : bool {
        
        if(!self::insert($input)){
            return false;
        }

        return true;

    }

}
