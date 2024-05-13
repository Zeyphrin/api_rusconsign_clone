<?php
use App\Http\Controllers\AuthadminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MitraController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin', [AdminController::class, "index"]);
// Route::get('/user', [AuthController::class, "datauser"]);



Route::get('/mitra', [MitraController::class, "index"]);
Route::get('/Mitra/create', [MitraController::class, 'create']);
Route::post('/Mitra/create', [MitraController::class, 'store']);