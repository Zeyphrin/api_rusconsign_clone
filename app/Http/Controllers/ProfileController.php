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
            $user->load('profileImages.mitra');

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'bio_desc' => $user->bio_desc,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'id_mitra' => $user->profileImages->first()->mitra->id ?? null,
                'image_profile'=>$user->profileImages->first()->mitra->image_profile ?? null,
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

    public function editprofile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Validasi data yang diterima dari request
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'bio_desc' => 'sometimes|string',
            'image_profile' => 'sometimes|image',
        ]);

        // Update data user
        if (isset($validatedData['name'])) {
            $user->name = $validatedData['name'];
        }
        if (isset($validatedData['bio_desc'])) {
            $user->bio_desc = $validatedData['bio_desc'];
        }
        if (isset($validatedData['image_profile'])) {
            $imagePath = $request->file('image_profile')->store('public/images');
            $user->image_profile = basename($imagePath);
        }
        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user,
        ], 200);
    }
}

