<?php

use App\Http\Controllers\Auth\AuthadminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthmitraController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BarangController;
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

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);


    Route::get('allprofile', [ProfileController::class, 'allprofile']);
    Route::post('edit-profile', [ProfileController::class, 'editProfile']);

    // Like routes
    Route::get('likes', [LikeController::class, 'index']); // Get all liked items for the authenticated user
    Route::post('likes', [LikeController::class, 'favorite']); // Favorite a product
    Route::delete('likes/{barang_id}', [LikeController::class, 'unfavorite']); // Unfavorite a product

    Route::post('/mitra/add-barang', [\App\Http\Controllers\BarangController::class, 'addBarang']);
    Route::put('/mitra/edit-barang/{id}', [\App\Http\Controllers\BarangController::class, 'editBarang']);
    Route::delete('/mitra/delete-barang/{id}', [\App\Http\Controllers\BarangController::class, 'deleteBarang']);
    Route::get('mitra/{mitra_id}', [BarangController::class, 'getBarangsByMitraId']);
}
);

Route::get('/barang/{id}', [BarangController::class, 'show']);
Route::put('publish/{id}', [BarangController::class, 'publish']);
Route::put('unpublish/{id}', [BarangController::class, 'unpublish']);
Route::get('/accepted-barangs', [BarangController::class, 'getAcceptedBarangs']);
Route::get('/barangs/search', [BarangController::class, 'searchAcceptedBarangs']);

Route::get('dataprofile', [ProfileController::class, 'dataprofile']);
Route::get('barang', [\App\Http\Controllers\BarangController::class, 'index']);


Route::post('tambahjasa', [ProfileController::class, 'tambahjasa']);
Route::post( 'tambahproduct', [ProfileController::class, 'tambahproduct']);
Route::post('add-category', [\App\Http\Controllers\CategoryController::class, 'addCategory']);
Route::get('/barang/filter', [BarangController::class, 'filterProductsByCategory']);

Route::get("index",[AuthController::class,"index"]);

 // Routes for AuthmitraController


Route::put('accept/{id}', [AuthmitraController::class, 'accept']);
Route::get("mitra/show/{id}", [AuthmitraController::class, "show"])->middleware('auth:sanctum');
Route::delete('reject/{id}', [AuthmitraController::class, 'reject'])->middleware('auth:sanctum');
Route::get('/mitra',[AuthmitraController::class, 'index']);
Route::put('/mitras/{id}', [AuthmitraController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/mitras/{id}', [AuthmitraController::class, 'destroy'])->middleware('auth:sanctum');


Route::get("index", [AuthController::class, "index"]);

Route::post('/registermitra', [AuthmitraController::class, 'registermitra'])->middleware('auth:sanctum');
Route::put('mitra/{id}/accept', [AuthadminController::class, 'acceptMitra'])->middleware('auth:sanctum');

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



Route::get('/product',[ProductController::class, 'index']);
Route::put('/edit-products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

Route::post('/add-jasa', [JasaController::class, 'addJasa']);
Route::get('/jasa',[JasaController::class, 'index']);
Route::put('/jasas/{id}', [JasaController::class, 'update']);
Route::delete('/jasas/{id}', [JasaController::class, 'destroy']);



