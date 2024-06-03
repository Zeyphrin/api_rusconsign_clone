<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Category;
use App\Models\Mitra;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('category:id,name', 'mitra:id,nama_lengkap,jumlah_product,jumlah_jasa,pengikut,penilaian')->get();

        if ($barangs->isEmpty()) {
            return response()->json(['message' => 'Tidak ada barang yang ditemukan'], 404);
        }

        // Buat array untuk menyimpan data barang beserta informasi tambahan dari mitra
        $barangData = [];
        foreach ($barangs as $barang) {
            $barangData[] = [
                'id' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'deskrpsi' => $barang->deskrpsi,
                'harga' => $barang->harga,
                'rating_barang' => $barang->rating_barang,
                'category_name' => $barang->category->name,
                'image_barang' => $barang->image_barang,
                'created_at' => $barang->created_at,
                'updated_at' => $barang->updated_at,
                'mitra' => [
                    'id' => $barang->mitra->id,
                    'nama_lengkap' => $barang->mitra->nama_lengkap,
                    'jumlah_product' => $barang->mitra->jumlah_product,
                    'jumlah_jasa' => $barang->mitra->jumlah_jasa,
                    'pengikut' => $barang->mitra->pengikut,
                    'penilaian' => $barang->mitra->penilaian,
                ],
            ];
        }

        return response()->json([
            'message' => 'Data barang berhasil ditemukan',
            'barangs' => $barangData,
        ], 200);
    }

        public function addBarang(Request $request)
        {
            $validatedData = $request->validate([
                'nama_barang' => 'required|string|max:255',
                'deskrpsi' => 'required|string',
                'harga' => 'required|integer',
                'rating_barang' => 'required|numeric',
                'category_id' => 'required|in:1,2',
                'image_barang' => 'required|image',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $profileImage = $user->profileImages()->first();
            if (!$profileImage) {
                return response()->json(['message' => 'Profile image not found'], 404);
            }

            $mitraId = $profileImage->mitra_id;
            $mitra = Mitra::find($mitraId);
            if (!$mitra) {
                return response()->json(['message' => 'Mitra not found'], 404);
            }

            $imagePath = $request->file('image_barang')->store('public/images');
            $barang = new Barang();
            $barang->nama_barang = $validatedData['nama_barang'];
            $barang->deskrpsi = $validatedData['deskrpsi'];
            $barang->harga = $validatedData['harga'];
            $barang->rating_barang = $validatedData['rating_barang'];
            $barang->category_id = $validatedData['category_id'];
            $barang->image_barang = basename($imagePath);
            $barang->mitra_id = $mitraId;
            $barang->save();

            // Retrieve the category
            $category = Category::find($validatedData['category_id']);
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            $categoryName = $category->name;

            $categoryType = $validatedData['category_id'] == 1 ? 'product' : 'jasa';
            $mitra->{"jumlah_$categoryType"} += 1;
            $mitra->save();

            $mitraData = [
                'id' => $mitra->id,
                'name' => $mitra->nama_lengkap,
                'jumlah_product' => $mitra->jumlah_product,
                'jumlah_jasa' => $mitra->jumlah_jasa,
                'pengikut' =>$mitra->pengikut,
                'penilaian' => $mitra->penilaian,
            ];



            return response()->json([
                'message' => 'Produk berhasil ditambahkan',
                'product' => $barang,
                'mitra' => $mitraData,
                'category_name' => $categoryName,
            ], 201);
        }

    public function editBarang(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }


        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskrpsi' => 'required|string',
            'harga' => 'required|integer',
            'rating_barang' => 'required|numeric',
            'category_id' => 'required|in:1,2',
            'image_barang' => 'required|image',
        ]);

        $barang->nama_barang = $validatedData['nama_barang'];
        $barang->deskrpsi = $validatedData['deskrpsi'];
        $barang->harga = $validatedData['harga'];
        $barang->rating_barang = $validatedData['rating_barang'];
        $barang->category_id = $validatedData['category_id'];

        $barang->save();

        return response()->json([
            'message' => 'Barang berhasil diupdate',
            'barang' => $barang,
        ], 200);
    }


    public function deleteBarang(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        if ($barang->mitra_id !== $user->profileImages()->first()->mitra_id) {
            return response()->json(['message' => 'Tidak diizinkan untuk menghapus barang ini'], 403);
        }

        $barang->delete();

        return response()->json([
            'message' => 'Barang berhasil dihapus',
        ], 200);
    }

}
