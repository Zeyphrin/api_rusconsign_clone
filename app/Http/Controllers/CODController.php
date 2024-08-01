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
        $cods = Cod::with(['barang', 'lokasi.mitra', 'user'])->get();

        return response()->json([
            'cods' => $cods,
        ], 200);
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

        return response()->json([
            'message' => 'Pembayaran berhasil ditambahkan',
            'cod' => $cod,
            'product' => $barang,
            'lokasi' => $lokasi,
        ], 201);
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
        if ($user) {
            $user->status_pembayaran = $validatedData['status_pembayaran'];
            $user->save();
        }

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

        $cods = $user->cods()->with(['barang', 'lokasi.mitra'])->get();

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

        $cods = Cod::whereIn('lokasi_id', $mitra->lokasis->pluck('id'))->with(['barang', 'user'])->get();

        return response()->json([
            'mitra' => $mitra,
            'cods' => $cods,
        ]);
    }

    public function updateStatusToCompleted(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status_pembayaran' => 'required|string|in:selesai',
        ]);

        $cod = Cod::findOrFail($id);

        if ($cod->status_pembayaran !== 'progres') {
            return response()->json(['message' => 'Status pembayaran harus dalam progres sebelum diupdate ke selesai'], 400);
        }

        $cod->status_pembayaran = $validatedData['status_pembayaran'];
        $cod->save();

        $user = $cod->user;
        if ($user) {
            $user->status_pembayaran = $validatedData['status_pembayaran'];
            $user->save();
        }

        $mitra = $cod->lokasi->mitra;
        if ($mitra) {
            $mitra->status_pembayaran = $validatedData['status_pembayaran'];
            $mitra->save();
        }

        return response()->json(['message' => 'Status pembayaran berhasil diperbarui menjadi selesai'], 200);
    }

    public function getCodsByStatus($role, $status)
    {
        // Validasi role yang diterima
        if (!in_array($role, ['user', 'mitra'])) {
            return response()->json(['message' => 'Role tidak valid'], 400);
        }

        // Validasi status yang diterima
        if (!in_array($status, ['belum_pembayaran', 'progres', 'selesai'])) {
            return response()->json(['message' => 'Status pembayaran tidak valid'], 400);
        }

        // Query berdasarkan role dan status
        if ($role === 'user') {
            $cods = Cod::where('status_pembayaran', $status)  // Update to use 'status_pembayaran' instead of 'user_status_pembayaran'
            ->with(['barang', 'lokasi.mitra', 'user'])
                ->get();
        } else {
            $cods = Cod::where('status_pembayaran', $status)  // Update to use 'status_pembayaran' instead of 'mitra_status_pembayaran'
            ->with(['barang', 'lokasi.mitra', 'user'])
                ->get();
        }

        // Mengembalikan hasil query dalam bentuk JSON
        return response()->json([
            'role' => $role,
            'status' => $status,
            'cods' => $cods,
        ], 200);
    }
}
