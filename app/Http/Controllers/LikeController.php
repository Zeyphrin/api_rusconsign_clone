<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function index()
    {
        $likes = Like::where('user_id', Auth::id())->with('product')->get();
        return response()->json($likes, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $like = Like::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ]
        );

        return response()->json(['message' => 'Product liked', 'like' => $like], 201);
    }

    public function destroy($id)
    {
        $like = Like::where('user_id', Auth::id())->where('id', $id)->first();
        if (!$like) {
            return response()->json(['message' => 'Like not found'], 404);
        }

        $like->delete();
        return response()->json(['message' => 'Like removed'], 200);
    }
}

