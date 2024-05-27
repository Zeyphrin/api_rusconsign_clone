<?php

namespace App\Http\Controllers;

use App\Http\Resources\JasaResource;
use App\Http\Resources\ProductResource;
use App\Models\Jasa;
use App\Models\Mitra;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JasaController extends Controller
{

    public function index()
    {
        $jasas = Jasa::all();
        return JasaResource::collection($jasas);
    }
    public function addJasa(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'name_jasa' => 'required|string',
            'desc_jasa' => 'required|string',
            'price_jasa' => 'required|numeric',
            'rating_jasa' => 'required|numeric',
            'mitra_id' => 'required|exists:mitras,id',
            'image_jasa' => 'required|image'
        ]);

        // Handle image upload
        if ($request->hasFile('image_jasa')) {
            $image = $request->file('image_jasa');
            $imagePath = $image->store('jasa_images');

            // Generate the URL for the stored image
            $imageData = Storage::url($imagePath);
        } else {
            return response()->json(['message' => 'Image is required'], 422);
        }

        // Create a new Jasa instance
        $jasa = new Jasa();
        $jasa->name_jasa = $validatedData['name_jasa'];
        $jasa->desc_jasa = $validatedData['desc_jasa'];
        $jasa->price_jasa = $validatedData['price_jasa'];
        $jasa->rating_jasa = $validatedData['rating_jasa'];
        $jasa->mitra_id = $validatedData['mitra_id'];
        $jasa->image_jasa = $imageData;
        $jasa->save();

        // Mencari data mitra berdasarkan mitra_id
        $mitra = Mitra::find($validatedData['mitra_id']);

        if ($mitra->status !== 'accepted') {
            return response()->json(['message' => 'Mitra not accepted'], 403);
        }

        // Check if Jasa creation was successful
        if ($jasa) {
            // Increment jumlah_jasa pada mitra
            $mitra->jumlah_jasa += 1;
            $mitra->save();

            return response()->json(['message' => 'Jasa created successfully'], 201);
        } else {
            return response()->json(['message' => 'Failed to create jasa'], 500);
        }
    }

}
