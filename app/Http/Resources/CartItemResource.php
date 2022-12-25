<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $product = Product::find($this->product_id);
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'img' => $product->avatar,
            'name' => $product->name,
            'quantity' => $this->quantity,
            'size' => $this->size,
            'price' => $product->price
        ];
    }
}
