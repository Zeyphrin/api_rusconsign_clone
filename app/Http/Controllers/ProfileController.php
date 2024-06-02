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

    public function allprofile()
    {
        $user = Auth::user();

        if ($user) {
            // Load the profileImages relationship along with the mitra relationship
            $user->load('profileImages.mitra');

            // Prepare the response data by combining user and mitra data
            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'bio_desc' => $user->bio_desc,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'id_mitra' => $user->profileImages->first()->mitra->id ?? null,
                'nama' => $user->profileImages->first()->mitra->nama_lengkap ?? null,
                'nama_toko' => $user->profileImages->first()->mitra->nama_toko ?? null,
                'nis' => $user->profileImages->first()->mitra->nis ?? null,
                'nomor' => $user->profileImages->first()->mitra->nomor ?? null,
                'image' => $user->profileImages->first()->mitra->image_id_card ?? null,
                'status' => $user->profileImages->first()->mitra->status ?? null,
                'pengikut' => $user->profileImages->first()->mitra->pengikut ?? null,
                'email_dariUser' => $user->profileImages->first()->mitra->email ?? null,
                'jumlahproduct' => $user->profileImages->first()->mitra->jumlah_product ?? null,
                'jumlahjasa' => $user->profileImages->first()->mitra->jumlah_jasa ?? null,
                'penilaian' => $user->profileImages->first()->mitra->penilaian ?? null,
//                'profile_images' => $user->profileImages->map(function ($profileImage) {
//                    return [
//                        'id' => $profileImage->id,
//                        'image' => $profileImage->image, // Adjust if necessary
//                        'bio' => $profileImage->bio,
//                        'mitra' => [
//                            'id' => $profileImage->mitra->id ?? null,
//                            'nama' => $profileImage->mitra->nama_lengkap ?? null,
//                            'nama_toko' => $profileImage->mitra->nama_toko ?? null,
//                            'nis' => $profileImage->mitra->nis ?? null,
//                            'nomor' => $profileImage->mitra->nomor ?? null,
//                            'image' => $profileImage->mitra->image_id_card ?? null,
//                            'status' => $profileImage->mitra->status ?? null,
//                            'pengikut' => $profileImage->mitra->pengikut ?? null,
//                            'email' => $profileImage->mitra->email ?? null,
//                            'jumlahproduct' => $profileImage->mitra->jumlah_product ?? null,
//                            'jumlahjasa' => $profileImage->mitra->jumlah_jasa ?? null,
//                            'penilaian' => $profileImage->mitra->penilaian ?? null,
//                        ]
//                    ];
//                })
            ];

            return response()->json([
                "message" => "Data user berhasil didapatkan",
                "data" => $data
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

