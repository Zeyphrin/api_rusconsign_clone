<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function index()
    {
        $likes = Like::where('user_id', Auth::user())->with('barang')->get();
        return response()->json($likes, 200);
    }

    public function favorite(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
        ]);

        $userId = Auth::user()->id;

        $like = Like::firstOrCreate([
            'user_id' => $userId,
            'barang_id' => $request->barang_id,
        ]);

        $like->load('barang');

        return response()->json(['message' => 'Product liked', 'like' => $like], 201);
    }

    public function unfavorite(Request $request, $barang_id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
        ]);

        $userId = Auth::user()->id;

        $like = Like::where('user_id', $userId)
            ->where('barang_id', $barang_id)
            ->first();

        if (!$like) {
            return response()->json(['message' => 'Like not found'], 404);
        }

        $like->load('barang');

        $like->delete();
        return response()->json(['message' => 'Like removed'], 200);
    }
}
