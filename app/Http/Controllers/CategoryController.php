<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {

    }

    public function addCategory(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255', // Validation rules for the 'nama' field
        ]);

        // Create a new category instance
        $category = new Category();
        $category->name = $validatedData['nama'];
        $category->save();

        return response()->json(['message' => 'Category added successfully'], 200);
    }
}
