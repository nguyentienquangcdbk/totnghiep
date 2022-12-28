<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemCollection;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //

    public function index()
    {

        // return Order::all();
        return new OrderCollection(Order::all());
    }
    public function detail($id)
    {
        $order = Order::find($id);

        return new OrderResource($order);
    }


    public function add(Request $request)
    {
        // dd($request->all());

        $cart = Cart::find($request->cartId);

        $TotalPrice =  0;

        foreach ($cart->items as $item) {

            $product = Product::find($item->product_id);
            $price = $product->price;


            $TotalPrice = $TotalPrice + ($price * $item->quantity);
        }

        // return $request->pathAddress;

        $order = Order::create([
            'products' => json_encode(new CartItemCollection($cart->items)),
            'totalPrice' => $TotalPrice,
            'name' => $request->name,
            'address' => $request->address,
            'pathAddress' => $request->path_with_type,
            'userId' => $cart->userID,
            'sdt' => $request->sdt,
            // 'description' => $request->description

        ]);

        // return $order;

        $user =  User::find($cart->userID);
        $cart->delete();


        return [
            'data' => new OrderResource($order),
            'cart' => $user->cart()->create([]),

        ];

        // $cart->delete();
    }
    public function delete($id)
    {
        $order = Order::find($id);
        $items = json_decode($order->products);
        // dd($items);
        if (is_array($items)) {
            foreach ($items as $item) {
                CartItem::find($item->id)->delete();
            }
        } else {
            CartItem::find($items->id)->delete();
        }

        return $order->delete();
    }
}
