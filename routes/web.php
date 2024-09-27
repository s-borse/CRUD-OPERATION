<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ModelController;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('navigationbar');
});

Route::get('/nav_show_brand', [BrandController::class, 'navShowBrand']);
Route::get('/modal_form', [BrandController::class,'modalForm']);
Route::get('/modal_form', [BrandController::class, 'index']);

// Route::post('/',[BrandController::class,'index']);
Route::post('/brands',[BrandController::class,'store']);

Route::get('/show-brand',[BrandController::class,'showbrand']);
Route::get('edit-brand/{id}',[BrandController::class,'edit']);
Route::post('/update-brand',[BrandController::class,'update']);
Route::post('/delete-brand',[BrandController::class,'delete']);

// Define the route for storing the brand model
Route::post('/brand-model', [ModelController::class, 'storeModel']);
Route::get('/model-show-brand', [ModelController::class, 'showBrands']);







