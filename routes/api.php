<?php

use App\Http\Controllers\Auth\AuthadminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthmitraController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });
// });

Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::group([
    "middleware" => ["auth:sanctum"]
], function(){

    Route::get("profile",[AuthController::class,"profile"]);
    Route::get("logout",[AuthController::class,"logout"]);
}
);

Route::get("index",[AuthController::class,"index"]);

 // Routes for AuthmitraController
 Route::post('registermitra', [AuthmitraController::class, 'registermitra']);
 Route::put('acceptmitra/{id}', [AuthmitraController::class, 'accept']);
 Route::delete('rejectmitra/{id}', [AuthmitraController::class, 'reject']);


Route::get("index", [AuthController::class, "index"]);


Route::put('mitra/{id}/accept', [AuthadminController::class, 'acceptMitra']);
Route::delete('mitra/{id}/reject', [AuthadminController::class, 'rejectMitra']);
Route::post('registeradmin',[AuthadminController::class,'registeradmin']);
Route::post('loginadmin',[AuthadminController::class,'loginadmin']);

