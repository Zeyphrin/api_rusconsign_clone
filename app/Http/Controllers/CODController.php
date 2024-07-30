<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Cod;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CODController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'lokasi_id' => 'required|exists:lokasis,id',
            'quantity' => 'required|integer',
        ]);

        $barang = Barang::findOrFail($validatedData['barang_id']);
        $lokasi = Lokasi::findOrFail($validatedData['lokasi_id']);
        $user = Auth::user();

        $totalAmount = $barang->harga * $validatedData['quantity'];

        $cod = Cod::create([
            'barang_id' => $validatedData['barang_id'],
            'lokasi_id' => $validatedData['lokasi_id'],
            'quantity' => $validatedData['quantity'],
            'status_pembayaran' => 'belum_pembayaran',
            'grand_total' => $totalAmount,
            'user_id' => $user->id,
        ]);

        return response()->json($cod, 201);
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi nilai status_pembayaran
        $validatedData = $request->validate([
            'status_pembayaran' => 'required|string|in:belum_pembayaran,progres',
        ]);

        // Temukan Cod yang sesuai
        $cod = Cod::findOrFail($id);

        // Update status_pembayaran
        $cod->status_pembayaran = $validatedData['status_pembayaran'];
        $cod->save();

        // Update status pembayaran untuk user terkait
        $user = $cod->user;
        if ($user) {
            $user->status_pembayaran = $validatedData['status_pembayaran'];
            $user->save();
        }

        // Update status pembayaran untuk mitra terkait
        $mitra = $cod->lokasi->mitra;
        if ($mitra) {
            $mitra->status_pembayaran = $validatedData['status_pembayaran'];
            $mitra->save();
        }

        return response()->json(['message' => 'Status pembayaran berhasil diupdate'], 200);
    }

}
