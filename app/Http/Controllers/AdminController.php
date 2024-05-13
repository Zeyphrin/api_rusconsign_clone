<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    public function index()
    {
        $mitras = Mitra::all(); // Mengambil semua data mitra dari database
        return view('/admin', [
            "title" => "admin",
            "mitras" => $mitras // Menambahkan data mitra ke dalam array yang dilewatkan ke view
        ]);
    }
}
