<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Http\Controllers\Storage;
use Validator;

class BrandController extends Controller
{
    public function index() {
        $brands = Brand::select('name', 'id', 'image')->get(); // Fetch brands
        return view('modal_form', compact('brands')); // Pass brands to the view
    }
    
    public function showbrand()
    {
        // Fetch all brands from the database
        $brands = Brand::all();

        // Return the brands as a JSON response
        return response()->json([
            'status' => 'success', // Adding a status key can be helpful for handling responses on the client-side
            'data' => $brands // Encapsulating the data in a 'data' key

        ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Create a new brand instance
        $brand = new Brand();
        $brand->name = $validatedData['name'];
    
        // Handle the image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public');
            $brand->image = $imagePath;
        }
    
        // Save the brand
        $brand->save();
    
        // Return a successful response
        return response()->json(['success' => 'Successfully added.']);
    }
    

    public function edit($id){
        $brand = Brand::find($id);
        return response()->json([
            'status' => 'success',
            'brand' => $brand,
        ]);
    }

    public function update(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'brand_id' => 'required|integer|exists:brands,id',
            'editBrandName' => 'required|string|max:255',
            'editBrandImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Retrieve the brand by its ID
        $brand = Brand::find($validated['brand_id']);
    
        // Check if the brand exists
        if (!$brand) {
            return response()->json(['error' => 'Brand not found.'], 404);
        }
    
        // Update brand properties
        $brand->name = $validated['editBrandName'];
    
        // Handle the image upload if a new image is provided
        if ($request->hasFile('editBrandImage')) {
            // Optionally, you might want to delete the old image from storage
            // Storage::disk('public')->delete($brand->image);
    
            $image = $request->file('editBrandImage');
            $imagePath = $image->store('images', 'public');
            $brand->image = $imagePath;
        }
    
        // Save the brand
        $brand->save();
    
        // Return a successful response
        return response()->json(['success' => 'Successfully updated.']);
    }
    
 public function delete(request $request){
    $delete = $request->input('id');
    Brand::destroy($delete);
    return response()->json(['success' => 'Successfully delete.']);
 }

 public function navShowBrand() {
    return view('nav_show_brand'); // Ensure this matches the actual filename in resources/views
}

public function modalForm(){
    return view('modal_form');
}


}
