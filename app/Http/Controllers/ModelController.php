<?php

namespace App\Http\Controllers;

use App\Models\BrandModel;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function storeModel(Request $request)
{
    $validatedData = $request->validate([
        'brand' => 'required|exists:brands,id',
        'model-name' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Proceed with saving the model
    $model = new BrandModel();
    $model->brand_id = $validatedData['brand'];
    $model->model_name = $validatedData['model-name'];

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
        $model->image = $imagePath;
    }

    $model->save();

    return response()->json(['success' => 'Model saved successfully.']);
}

public function showBrands()
{
    try {
        $brands = BrandModel::all();
        return view('brands.show', compact('brands'));
    } catch (\Exception $e) {
        \Log::error('Error fetching brands: ' . $e->getMessage());
        return response()->json(['error' => 'Something went wrong'], 500);
    }
}

}


