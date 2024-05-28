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

    public function index(Request $request)
    {
        $query = Jasa::query();

        if ($request->has('nama_jasa')) {
            $query->where('nama_jasa', 'like', '%' . $request->input('nama_jasa') . '%');
        }

        $jasas = $query->get();

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

        $mitra = Mitra::find($validatedData['mitra_id']);

        if ($mitra->status !== 'accepted') {
            return response()->json(['message' => 'Mitra not accepted'], 403);
        }

        if ($jasa) {
            $mitra->jumlah_jasa += 1;
            $mitra->save();

            return response()->json(['message' => 'Jasa created successfully'], 201);
        } else {
            return response()->json(['message' => 'Failed to create jasa'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name_jasa' => 'sometimes|required|string',
            'desc_jasa' => 'sometimes|required|string',
            'price_jasa' => 'sometimes|required|numeric',
            'rating_jasa' => 'sometimes|required|numeric',
            'mitra_id' => 'sometimes|required|exists:mitras,id',
            'image_jasa' => 'sometimes|image'
        ]);

        $jasa = Jasa::findOrFail($id);

        if ($request->hasFile('image_jasa')) {
            // Delete the old image
            if ($jasa->image_jasa) {
                Storage::delete($jasa->image_jasa);
            }
            $image = $request->file('image_jasa');
            $imagePath = $image->store('jasa_images');
            $imageData = Storage::url($imagePath);
            $jasa->image_jasa = $imageData;
        }

        $jasa->update($validatedData);

        return response()->json(['message' => 'Jasa updated successfully'], 200);
    }

    public function destroy($id)
    {
        $jasa = Jasa::findOrFail($id);

        // Delete the associated image
        if ($jasa->image_jasa) {
            Storage::delete($jasa->image_jasa);
        }

        $jasa->delete();

        return response()->json(['message' => 'Jasa deleted successfully'], 200);
    }

}
