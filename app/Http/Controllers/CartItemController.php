<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;

class CartItemController extends Controller
{
    //

    public function increment(Request $request)
    {
        // tăng số lượng sản phẩm
        $item = CartItem::find($request->id);

        $item->quantity = $item->quantity + 1;
        $item->save();

        return [
            'cartItem' => $item
        ];
    }
    public function decrement(Request $request)
    {
        // giam số lượng sản phẩm
        // dd($request->id);
        $item = CartItem::find($request->id);
        if ($item->quantity == 1) {
            return [
                'message' => 'giảm đến mức tối đa',
            ];
        } else {
            $item->quantity = $item->quantity - 1;
            $item->save();
        }
        return [
            'cartItem' => $item
        ];
    }
    public function delete($id, Request $request)
    {
        return CartItem::destroy($id);
    }
}
