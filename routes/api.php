<?php

use App\Http\Controllers\Auth\AuthadminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthmitraController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('register', [AuthController::class, 'register']);

Route::group([
    "middleware" => ["auth:sanctum"]
], function(){
    Route::get("profile",[AuthController::class,"profile"]);
    Route::get("logout",[AuthController::class,"logout"]);
    Route::post('tambahpengikut', [ProfileController::class, 'tambahpengikut']);
    Route::get('/test',[ProductController::class,'test']);
}
);

Route::get('dataprofile/{user}', [ProfileController::class, 'dataprofile']);

Route::post('tambahjasa', [ProfileController::class, 'tambahjasa']);
Route::post('tambahproduct', [ProfileController::class, 'tambahproduct']);

Route::get("index",[AuthController::class,"index"]);

 // Routes for AuthmitraController
 Route::post('registermitra', [AuthmitraController::class, 'registermitra']);
 Route::put('accept/{id}', [AuthmitraController::class, 'accept']);
 Route::delete('reject/{id}', [AuthmitraController::class, 'reject']);
 Route::get('/mitra',[AuthmitraController::class, 'index']);


Route::get("index", [AuthController::class, "index"]);


Route::put('mitra/{id}/accept', [AuthadminController::class, 'acceptMitra']);
Route::delete('mitra/{id}/reject', [AuthadminController::class, 'rejectMitra']);
Route::post('registeradmin',[AuthadminController::class,'registeradmin']);
Route::post('loginadmin',[AuthadminController::class,'loginadmin']);
Route::post('/mitras/{id}/tambahpengikut', [AuthmitraController::class, 'tambahpengikut']);
Route::post('/mitras/{id}/tambahproduct', [AuthmitraController::class, 'tambahproduct']);

Route::post('add-product', [ProductController::class, 'addProduct']);

Route::group(['prefix' => 'product'], function () {
    Route::post('/store', [ProductController::class, 'store']);
    Route::get('/', [ProductController::class, 'index']);
});



