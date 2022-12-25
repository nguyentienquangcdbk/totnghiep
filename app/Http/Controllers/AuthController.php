<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemCollection;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    //

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $checkLogin = Auth::attempt([
            'email' => $fields['email'],
            'password' => $fields['password']
        ]);

        $user = User::where('email', $fields['email'])->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }


        $token = $user->createToken('myapptoken')->plainTextToken;
        $cart = $user->cart;
        $order = $user->order;
        $itemCart = new CartItemCollection($cart->items);
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;
        $cart = $user->cart()->create([]);
        $itemCart = new CartItemCollection($cart->items);
        $order = $user->order;

        $response = [
            'user' => $user,
            // 'cart' => $cart,
            // 'itemCart' => $cart->items,
            'token' => $token
        ];

        return response($response, 201);
    }
    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        return [
            'message' => 'Logged out',
        ];
    }
    public function getUser(Request $request)
    {
        $access_token = $request->header('Authorization');
        $auth_header = explode(' ', $access_token);
        $token = $auth_header[1];
        $hastoken = PersonalAccessToken::findToken($token);
        $userId = $hastoken->tokenable_id;
        $user = User::find($userId);
        $cart = $user->cart;
        $order = $user->order;
        $itemCart = new CartItemCollection($cart->items);
        $response = [
            'user' => $user,
            // 'cart' => $cart,
            // 'itemCart' => $cart->items
        ];

        return response($response, 201);
    }
}
