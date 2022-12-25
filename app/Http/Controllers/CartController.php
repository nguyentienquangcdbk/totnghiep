<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    //
    public function show(Request $request, $id)
    {
        // $itemCart  = Cart::find(1)->items;
        $item = CartItem::where('cart_id', '=', $id)->get();
        // dd($item);
        // return json_encode($item);
        return [
            'itemCart' => $item
        ];
    }

    public function addProduct(Request $request)
    {
        $cartItem = CartItem::where(['cart_id' => $request->id, 'product_id' => $request->productId, 'size' => $request->size])->first();

        if ($cartItem) {
            $cartItem->quantity = $cartItem->quantity + $request->quantity;
            $cartItem->save();
            return response()->json(['message' => 'giỏ hàng đc cập nhật thành công'], 200);
        } else {
            CartItem::create([
                'cart_id' => $request->id,
                'product_id' => $request->productId,
                'quantity' => $request->quantity,
                'avatar' => $request->avatar,
                'price' => $request->price,
                'name' => $request->name,
                'size' => $request->size
            ]);

            return response()->json(['message' => 'thêm sản phẩm vào giỏ hàng thành công'], 200);
        }
    }


    public function checkout(Request $request)
    {
    }
}
