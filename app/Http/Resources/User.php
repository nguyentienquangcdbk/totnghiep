<?php

namespace App\Http\Resources;

use App\Models\Cart;

use Illuminate\Http\Resources\Json\ResourceCollection;

class User extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $Cart = Cart::where('userID', $this->id);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'isAdmin' => $this->isAdmin,
            'cart' => $Cart->id
        ];
    }
}
