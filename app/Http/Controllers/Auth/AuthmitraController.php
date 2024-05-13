<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\MitraResource;
use App\Models\Mitra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AuthmitraController extends Controller
{
    public function index()
    {
        $mitras = Mitra::all();
        return MitraResource::collection($mitras);
    }

    public function show($id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) {
            return response()->json(['message' => 'Mitra not found'], 404);
        }
        return new MitraResource($mitra);
    }

    public function store(Request $request)
    {
        $mitra = new Mitra();

        if ($mitra->save()) {
            // Setelah berhasil menyimpan mitra, temukan pengguna terkait
            $user = User::where('email', $request->email)->first();

            // Jika pengguna ditemukan, ubah statusnya menjadi "mitra"
            if ($user) {
                $user->status_mitra = 'mitra';
                $user->save();
            }

            return new MitraResource($mitra);
        } else {
            return response()->json(['message' => 'Failed to register mitra'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) {
            return response()->json(['message' => 'Mitra not found'], 404);
        }
        if ($mitra->save()) {
            $user = User::where('email', $request->email)->first();

            // Jika pengguna ditemukan, ubah statusnya menjadi "mitra"
            if ($user) {
                $user->status_mitra = 'mitra';
                $user->save();
            }

            return new MitraResource($mitra);
        } else {
            return response()->json(['message' => 'Failed to update mitra'], 500);
        }
    }

    public function destroy($id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) {
            return response()->json(['message' => 'Mitra not found'], 404);
        }

        if ($mitra->delete()) {
            return response()->json(['message' => 'Mitra deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to delete mitra'], 500);
        }
    }
}
