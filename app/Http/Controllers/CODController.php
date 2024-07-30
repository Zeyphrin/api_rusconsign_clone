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
            'barang_id' => 'required|integer',
            'lokasi_id' => 'required|inte  bger',
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
        ]);

        return response()->json($cod, 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status_pembayaran' => 'required|string|in:belum_pembayaran,progres',
        ]);

        $cod = Cod::findOrFail($id);

        $cod->status_pembayaran = $validatedData['status_pembayaran'];
        $cod->save();

        $user = $cod->user;
        $user->status_pembayaran = $validatedData['status_pembayaran'];
        $user->save();

        $mitra = $cod->lokasi->mitra;
        if ($mitra) {
            $mitra->status_pembayaran = $validatedData['status_pembayaran'];
            $mitra->save();
        }

        return response()->json(['message' => 'Status pembayaran berhasil diupdate'], 200);
    }

}
