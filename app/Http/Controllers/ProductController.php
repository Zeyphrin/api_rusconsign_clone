<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Mitra;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('nama_product')) {
            $query->where('nama_product', 'like', '%' . $request->input('nama_product') . '%');
        }

        $products = $query->get();

        return ProductResource::collection($products);
    }

    public function addProduct(Request $request)
    {
        $validatedData = $request->validate([
            'name_product' => 'required|string',
            'desc_product' => 'required|string',
            'price_product' => 'required|numeric',
            'rating_product' => 'required|numeric',
            'image' => 'required|image',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Access Mitra ID from the authenticated user's profileImage
        $profileImage = $user->profileImages()->first();

        if (!$profileImage) {
            return response()->json(['message' => 'Profile image not found'], 404);
        }

        // Access the Mitra ID from the ProfileImage
        $mitraId = $profileImage->mitra_id;
        $mitra = Mitra::find($mitraId);

        if (!$mitra) {
            return response()->json(['message' => 'Mitra not found'], 404);
        }

        $product = new Product();
        $product->name_product = $validatedData['name_product'];
        $product->desc_product = $validatedData['desc_product'];
        $product->price_product = $validatedData['price_product'];
        $product->rating_product = $validatedData['rating_product'];

        $imagePath = $request->file('image')->store('public/images');
        $product->image = basename($imagePath);
        $product->mitra_id = $mitraId;
        $product->save();

        // Update jumlah_product in Mitra
        $mitra->jumlah_product += 1;
        $mitra->save();

        // Prepare the response data with mitra details
        $mitraData = [
            'id' => $mitra->id,
            'name' => $mitra->nama_lengkap,
            'jumlah_product' => $mitra->jumlah_product,
            'jumlah_jasa' => $mitra->jumlah_jasa,
            'pengikut' =>$mitra->pengikut,
            'penilaian' => $mitra->penilaian,
        ];

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'product' => $product,
            'mitra' => $mitraData,
        ], 201);
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
            'image' => 'image'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('public/images');
            $product->image = basename($imagePath);
        }

        $product->name_product = $validatedData['name_product'] ?? $product->name_product;
        $product->desc_product = $validatedData['desc_product'] ?? $product->desc_product;
        $product->price_product = $validatedData['price_product'] ?? $product->price_product;
        $product->rating_product = $validatedData['rating_product'] ?? $product->rating_product;

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

        // Delete the product
        if ($product->delete()) {
             Storage::delete('public/images/' . $product->image);

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
