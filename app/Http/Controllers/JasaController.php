<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JasaController extends Controller
{
    public function store(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'name_jasa' => 'required|string',
            'desc_jasa' => 'required|string',
            'price_jasa' => 'required|numeric',
            'rating_jasa' => 'required|numeric',
            'mitraId' => 'required',
            'image_jasa' => 'required|image'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('Jasa_images');

            $imageData = Storage::url($imagePath);
        } else {
            return response()->json(['message' => 'Image is required'], 422);
        }

        $product = Jasa::create([
            'name_jasa' => $validatedData['name'],
            'desc_jasa' => $validatedData['desc'],
            'price_jasa' => $validatedData['price'],
            'rating_jasa' => $validatedData['rating'],
            'mitraId' => $validatedData['mitraId'],
            '_jasa' => $imageData
        ]);

        if ($product) {
            return response()->json(['message' => 'Product created successfully'], 201);
        } else {
            return response()->json(['message' => 'Failed to create product'], 500);
        }
    }
}
