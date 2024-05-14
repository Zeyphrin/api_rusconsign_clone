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
        // Check if the user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check if the user is associated with a partner
        if (!$user->mitra) {
            return response()->json(['message' => 'User is not associated with a mitra'], 422);
        }

        // Validate input data
        $validatedData = $request->validate([
            'name_product' => 'required|string',
            'desc_product' => 'required|string',
            'price_product' => 'required|numeric',
            'rating_product' => 'required|numeric',
            'image' => 'required|image',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('product_images');
        } else {
            return response()->json(['message' => 'Image is required'], 422);
        }

        $product = Product::create([
            'name_product' => $validatedData['name_product'],
            'desc_product' => $validatedData['desc_product'],
            'price_product' => $validatedData['price_product'],
            'rating_product' => $validatedData['rating_product'],
            'mitra_id' => $user->mitra->id,
            'image' => $imagePath // Store the image path instead of URL
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
            'image' => 'image' // Remove 'mitraId' from the validation rules
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('product_images');
            $product->image = $imagePath;
        }

        // Update product attributes
        $product->name_product = $validatedData['name_product'] ?? $product->name_product;
        $product->desc_product = $validatedData['desc_product'] ?? $product->desc_product;
        $product->price_product = $validatedData['price_product'] ?? $product->price_product;
        $product->rating_product = $validatedData['rating_product'] ?? $product->rating_product;

        // Save the updated product
        if ($product->save()) {
            return response()->json(['message' => 'Product updated successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to update product'], 500);
        }
    }
    public function destroy($id)
    {
        // Find the product by ID
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Delete the product
        if ($product->delete()) {
            return response()->json(['message' => 'Product deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to delete product'], 500);
        }
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('image', compact('product'));
    }

}
