<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('barang')->get();
        return response()->json($cartItems, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'barang_id' => $request->barang_id,
            ],
            [
                'quantity' => $request->quantity,
            ]
        );

        $cartItem->load('barang');

        return response()->json(['message' => 'Product added to cart', 'cartItem' => $cartItem], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Cart item updated', 'cartItem' => $cartItem], 200);
    }

    public function destroy($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->delete();
        return response()->json(['message' => 'Cart item removed'], 200);
    }
}
