<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        $mitras = Mitra::all();
        return view('/admin', [
            "title" => "admin",
            "mitras" => $mitras
        ]);
    }
}
