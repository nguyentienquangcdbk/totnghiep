<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\imgController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);

Route::post('register', [AuthController::class, 'register']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('me', [AuthController::class, 'getUser']);

    Route::post('logout', [AuthController::class, 'logout']);
});

Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'getId']);
    Route::post('/', [CategoryController::class, 'add']);
    Route::patch('/{id}', [CategoryController::class, 'edit']);
    Route::delete('{id}', [CategoryController::class, 'delete']);
});

Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'getId']);
    Route::post('/', [ProductController::class, 'add']);
    Route::patch('/{id}', [ProductController::class, 'edit']);
    Route::delete('{id}', [ProductController::class, 'delete']);
});

Route::prefix('img')->group(function () {
    Route::post('/upload', [imgController::class, 'upload']);
    Route::post('/uploads', [imgController::class, 'uploads']);
    Route::post('/delete', [imgController::class, 'delete']);
    Route::post('/remove/{id}', [imgController::class, 'removeProductImg']);
});

Route::prefix('cart')->group(function () {
    Route::get('/{id}', [CartController::class, 'show']);
    Route::post('/', [CartController::class, 'addProduct']);
    Route::post('/checkout', [CartController::class, 'checkout']);
});
Route::prefix('cartItem')->group(function () {
    Route::post('/increment', [CartItemController::class, 'increment']);
    Route::post('/decrement', [CartItemController::class, 'decrement']);
    Route::delete('/{id}', [CartItemController::class, 'delete']);
});

Route::prefix('order')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'detail']);
    Route::post('/', [OrderController::class, 'add']);
    Route::delete('/{id}', [OrderController::class, 'delete']);
});
