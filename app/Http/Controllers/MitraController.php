<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    public function index()
    {
        $mitras = Mitra::all();
        return view('/Mitra/mitra', [
            "title" => "mitra",
            "mitras" => $mitras
        ]);
    }



//    public function store(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'nama_lengkap' => 'required|string',
//            'nis' => 'required|numeric|unique:mitras',
//            'no_dompet_digital' => 'required|string',
//            'image_id_card' => 'required|image|max:1024',
//        ]);
//
//        if ($validator->fails()) {
//            return back()->withErrors($validator)->withInput();
//        }
//
//        $imagePath = $request->file('image_id_card')->store('post-images');
//
//        $mitra = new Mitra();
//        $mitra->nama_lengkap = $request->nama_lengkap;
//        $mitra->nis = $request->nis;
//        $mitra->no_dompet_digital = $request->no_dompet_digital;
//        $mitra->image_id_card = $imagePath;
//
//        if ($mitra->save()) {
//            return redirect('/mitra')->with('success', 'Data mitra telah ditambahkan');
//        } else {
//            return back()->withInput()->withErrors(['failed' => 'Gagal menambahkan data mitra']);
//        }
//    }
}
