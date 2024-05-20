<?php

use App\Http\Controllers\ProductCt;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryCT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// User
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::middleware(['jwt.admin'])->group(function () {
  // Category => Hanya bisa dikelola oleh admin
  Route::post('categories', [CategoryCT::class, 'store']);
  Route::get('categories', [CategoryCT::class, 'show']);
  Route::put('categories/{id}', [CategoryCT::class, 'update']);
  Route::delete('categories/{id}', [CategoryCT::class, 'delete']);

});

// Route::group(['middleware'=>['jwt.user']]), function(){

// }
Route::middleware('jwt.adminuser')->group(function () {
  // Products => Bisa dikelola oleh admin dan user
  Route::get('products', [ProductCt::class, 'show']);
  Route::post('products', [ProductCt::class, 'create']);
  Route::put('products/{id}', [ProductCt::class, 'update']);
  Route::delete('products/{id}', [ProductCt::class, 'delete']);
});






