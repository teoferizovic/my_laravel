<?php

namespace App\Http\Resources;

use App\Category;
use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Category as CategoryResource;

class Product extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
         return [
            'id'            => $this->id,
            'name'          => $this->name,
            'status'        => $this->status,
            'category_id'   => $this->category_id,
            'category'      => CategoryResource::make(Category::find($this->id)),
            'price'         => $this->price,
            'discount'      => $this->discount,
            'final_price'   => $this->price * $this->price, 
            'deleted_at'    => $this->deleted_at,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'external_name' => $this->external_name,
         ];
    }
}
