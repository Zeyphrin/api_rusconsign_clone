<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use App\Models\Product;
use App\Models\ProfileImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileController extends Controller
{

    public function profile()
    {
        $user = Auth::user();

        // Muat data relasi profile
        if ($user) {
            $user->load('profile');

            return response()->json([
                "message" => "Data user berhasil didapatkan",
                "data" => $user
            ]);
        }

        return response()->json([
            "message" => "User tidak terautentikasi",
            "data" => null
        ], 401);


    }

    public function index()
    {

    }

    public function dataprofile(Request $request, $user)
    {
        // Validate request parameters
        $validatedData = $request->validate([
            'user' => 'string',
            'pengikut' => 'integer|nullable',
            'jumlah_jasa' => 'integer|nullable',
            'jumlah_product' => 'integer|nullable',
            'penilaian' => 'numeric|min:0|max:5|nullable',
        ]);

        try {
            $user = User::where('name', $user)->firstOrFail();
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'pengikut' => $validatedData['pengikut'] ?? null,
                'jumlah_jasa' => $validatedData['jumlah_jasa'] ?? null,
                'jumlah_product' => $validatedData['jumlah_product'] ?? null,
                'penilaian' => $validatedData['penilaian'] ?? null,
            ];

            return response()->json(['message' => 'Data pengguna berhasil diambil', 'data' => $userData], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengambil data pengguna', 'error' => $e->getMessage()], 500);
        }
    }

    public function tambahpengikut(Request $request)
    {
        try {
            // Check if the user is authenticated
            if (!Auth::check()) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            $validatedData = $request->validate([
                'pengikut' => 'required|integer',
            ]);

            $user = Auth::user();
            $user->pengikut += $validatedData['pengikut'];
            $user->save();

            return response()->json(['message' => 'Pengikut berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan pengikut', 'error' => $e->getMessage()], 500);
        }
    }

    public function tambahjasa(Request $request)
    {
        try {
            $currentJasaCount = Jasa::count();
            $newJasaCount = $currentJasaCount + 1;

            $jasa = Jasa::firstOrCreate(['name' => 'service_count'], ['count' => $newJasaCount]);

            return response()->json(['message' => 'Jasa berhasil ditambahkan', 'total_jasa' => $newJasaCount], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan jasa', 'error' => $e->getMessage()], 500);
        }
    }

    public function tambahproduct(Request $request)
    {
        try {
            $currentProductCount = Product::count();
            $newProductCount = $currentProductCount + 1;

            $product = Product::firstOrCreate(['name' => 'service_count'], ['count' => $newProductCount]);

            return response()->json(['message' => 'Product berhasil ditambahkan', 'total_product' => $newProductCount], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan product', 'error' => $e->getMessage()], 500);
        }
    }
}

