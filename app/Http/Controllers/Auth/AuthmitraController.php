<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;

class AuthmitraController extends Controller
{
    public function index()
    {
        // Method index() - tambahkan logika jika diperlukan
    }

    public function registermitra(Request $request)
    {
        $request->validate([
            "nama_lengkap" => "required|string",
            "nis" => "required|integer",
            "no_dompet_digital" => "required|string",
            "image_id_card" => "required|string",
            "status" => "string"
        ]);

        $mitra = new Mitra();
        $mitra->nama_lengkap = $request->nama_lengkap;
        $mitra->nis = $request->nis;
        $mitra->no_dompet_digital = $request->no_dompet_digital;
        $mitra->image_id_card = $request->image_id_card;
        $mitra->status = $request->status ?? 'pending';

        if ($mitra->save()) {
            return response()->json(['message' => 'Mitra berhasil didaftarkan'], 201);
        } else {
            return response()->json(['message' => 'Gagal mendaftarkan mitra'], 500);
        }
    }

    public function accept($id)
    {
        $mitra = Mitra::find($id);

        if (!$mitra) {
            return response()->json(['message' => 'Mitra tidak ditemukan'], 404);
        }

        // Lakukan tindakan yang sesuai untuk menerima mitra (contoh: ubah status mitra menjadi diterima)
        $mitra->status = 'accepted';
        $mitra->save();

        return response()->json(['message' => 'Mitra berhasil diterima']);
    }

    public function reject($id)
    {
        $mitra = Mitra::find($id);

        if (!$mitra) {
            return response()->json(['message' => 'Mitra tidak ditemukan'], 404);
        }

        // Lakukan tindakan yang sesuai untuk menolak mitra (contoh: hapus mitra dari basis data)
        $mitra->delete();

        return response()->json(['message' => 'Mitra berhasil ditolak']);
    }

    public function profilemitra()
    {
        // Method profilemitra() - tambahkan logika jika diperlukan
    }
}
