<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create()
    {

    }

    public function store(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'name_product' => 'required|string',
            'desc_product' => 'required|string',
            'price_product' => 'required|numeric',
            'rating_product' => 'required|numeric',
            'image' => 'required|image'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('product_images');

            $imageData = Storage::url($imagePath);
        } else {
            return response()->json(['message' => 'Image is required'], 422);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if the authenticated user exists and has a 'mitraId'
        if (!$user || !$user->mitraId) {
            return response()->json(['message' => 'User is not associated with a mitra'], 422);
        }

        $product = Product::create([
            'name_product' => $validatedData['name_product'],
            'desc_product' => $validatedData['desc_product'],
            'price_product' => $validatedData['price_product'],
            'rating_product' => $validatedData['rating_product'],
            'mitraId' => $user->mitraId,
            'image' => $imageData
        ]);

        if ($product) {
            return response()->json(['message' => 'Product created successfully'], 201);
        } else {
            return response()->json(['message' => 'Failed to create product'], 500);
        }
    }

    public function update(Request $request, $id)
{
    $product = Product::find($id);
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    $validatedData = $request->validate([
        'name_product' => 'required|string',
        'desc_product' => 'required|string',
        'price_product' => 'required|numeric',
        'rating_product' => 'required|numeric',
        'mitraId' => 'required',
        'image' => 'image'
    ]);

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageData = base64_encode(file_get_contents($image));
        $product->image = $imageData;
    }

    $product->name_product = $validatedData['name_product']; // Correct array key
    $product->desc_product = $validatedData['desc_product']; // Correct array key
    $product->price_product = $validatedData['price_product']; // Correct array key
    $product->rating_product = $validatedData['rating_product']; // Correct array key
    $product->mitraId = $validatedData['mitraId']; // Correct array key

    if ($product->save()) {
        return response()->json(['message' => 'Product updated successfully'], 200);
    } else {
        return response()->json(['message' => 'Failed to update product'], 500);
    }
}

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($product->delete()) {
            return response()->json(['message' => 'Product deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to delete product'], 500);
        }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id); // Retrieve product data by ID
        return view('image', compact('product'));
    }

}
