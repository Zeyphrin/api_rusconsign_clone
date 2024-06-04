<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // Get all liked items for the authenticated user
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $likes = Like::where('user_id', $user->id)->with('barang')->get();

        return response()->json($likes, 200);
    }

    // Favorite a product
    public function favorite(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $like = Like::firstOrCreate([
            'user_id' => $user->id,
            'barang_id' => $request->barang_id,
        ]);

        $like->load('barang');

        return response()->json(['message' => 'Product liked', 'like' => $like], 201);
    }

    // Unfavorite a product
    public function unfavorite(Request $request, $barang_id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $like = Like::where('user_id', $user->id)
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
