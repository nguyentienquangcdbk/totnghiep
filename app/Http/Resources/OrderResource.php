<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::find($this->userId);
        return [
            'id' => $this->id,
            'totalPrice' => $this->totalPrice,
            'name' => $this->name,
            'address' => $this->address,
            'sdt' => $this->sdt,
            'userId' => $user->id,
            'description' => $this->description,
            'products' =>  json_decode($this->products),
            'time' => date_format($this->updated_at, 'Y-m-d H:i:s'),
            'pathAddress' => $this->pathAddress,
            'user' => $user
        ];
    }
}
