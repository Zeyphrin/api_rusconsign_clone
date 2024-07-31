<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Cod;
use App\Models\Lokasi;
use App\Models\Mitra;
use App\Models\User;
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

    public function getUserCods($userId)
    {
        $user = User::findOrFail($userId);

        $cods = $user->cods()->with(['lokasi.mitra'])->get();

        return response()->json([
            'user' => $user,
            'cods' => $cods,
        ]);
    }

    public function getMitraCods($mitraId)
    {
        $mitra = Mitra::find($mitraId);

        if (!$mitra) {
            return response()->json(['message' => 'Mitra not found'], 404);
        }

        $cods = Cod::whereIn('lokasi_id', $mitra->lokasis->pluck('id'))->with('user')->get();

        return response()->json([
            'mitra' => $mitra,
            'cods' => $cods,
        ]);
    }

    public function updateStatusToCompleted(Request $request, $id)
    {
        // Validasi nilai status_pembayaran
        $validatedData = $request->validate([
            'status_pembayaran' => 'required|string|in:selesai',
        ]);

        // Temukan Cod yang sesuai
        $cod = Cod::findOrFail($id);

        // Periksa apakah status saat ini adalah "progres" sebelum diperbarui ke "selesai"
        if ($cod->status_pembayaran !== 'progres') {
            return response()->json(['message' => 'Status pembayaran harus dalam progres sebelum diupdate ke selesai'], 400);
        }

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

        return response()->json(['message' => 'Status pembayaran berhasil diperbarui menjadi selesai'], 200);
    }



}
