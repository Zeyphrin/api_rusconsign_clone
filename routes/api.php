<?php

use App\Http\Controllers\Auth\AuthadminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthmitraController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });
// });

Route::get('/users', [AuthController::class, 'index']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::delete('/users/{id}', [AuthController::class, 'destroy']);
Route::put('/users/{user_id}/edit-bio', [AuthController::class, 'editBio']);
Route::post('forgot-password', [ForgotPasswordController::class, 'forgot']);
Route::post('reset-password', [ResetPasswordController::class, 'reset']);
Route::post('send-otp', [OTPController::class, 'sendOTP']);
Route::post('verify-otp', [OTPController::class, 'verifyOTP']);

Route::group([
    "middleware" => ["auth:sanctum"]
], function(){
    Route::get("profile",[AuthController::class,"profile"]);
    Route::get("logout",[AuthController::class,"logout"]);
    Route::post('tambahpengikut', [ProfileController::class, 'tambahpengikut']);
    Route::get('/test',[ProductController::class,'test']);

    Route::get('cart', [CartController::class, 'index']);
    Route::post('add-cart', [CartController::class, 'store']);
    Route::put('cart/{id}', [CartController::class, 'update']);
    Route::delete('cart/{id}', [CartController::class, 'destroy']);

    // Like routes
    Route::get('likes', [LikeController::class, 'index']);
    Route::post('add-likes', [LikeController::class, 'store']);
    Route::delete('likes/{id}', [LikeController::class, 'destroy']);
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
Route::put('/mitras/{id}', [AuthmitraController::class, 'update']);
Route::delete('/mitras/{id}', [AuthmitraController::class, 'destroy']);


Route::get("index", [AuthController::class, "index"]);


Route::put('mitra/{id}/accept', [AuthadminController::class, 'acceptMitra']);
Route::delete('mitra/{id}/reject', [AuthadminController::class, 'rejectMitra']);
Route::post('registeradmin',[AuthadminController::class,'registeradmin']);
Route::post('loginadmin',[AuthadminController::class,'loginadmin']);
Route::post('/mitras/{id}/tambahpengikut', [AuthmitraController::class, 'tambahpengikut']);
Route::post('/mitras/{id}/tambahproduct', [AuthmitraController::class, 'tambahproduct']);
Route::get('storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);

    // Periksa apakah file ada
    if (!Storage::exists($path)) {
        abort(404);
    }

    // Baca file dan dapatkan tipe konten
    $file = Storage::get($path);
    $type = Storage::mimeType($path);

    // Kembalikan respons dengan file dan tipe konten
    return response($file, 200)->header('Content-Type', $type);
})->where('path', '.*');


Route::post('add-product', [ProductController::class, 'addProduct']);
Route::get('/product',[ProductController::class, 'index']);
Route::put('/edit-products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

Route::post('/add-jasa', [JasaController::class, 'addJasa']);
Route::get('/jasa',[JasaController::class, 'index']);
Route::put('/jasas/{id}', [JasaController::class, 'update']);
Route::delete('/jasas/{id}', [JasaController::class, 'destroy']);



