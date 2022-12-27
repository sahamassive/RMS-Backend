<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//Brands
Route::get('brand',[BrandController::class,'view'])->name('view-brand');
Route::post('update-brand-status',[BrandController::class,'updateBrandStatus']);
//brand delete
Route::get('brand-delete/{id}',[BrandController::class,'deleteBrand'])->name('deleteBrand');
//brand Add & Update
Route::match(['get', 'post'], 'brand-add-edit/{id?}',[BrandController::class,'add_edit_brand']);


//Sections

Route::get('sections',[SectionController::class,'sections'])->name('admin.view-section');
Route::post('update-section-status',[SectionController::class,'updateSectionStatus']);
//section delete
Route::get('section-delete/{id}',[SectionController::class,'deleteSection'])->name('deleteSection');
//section Add & Update
Route::match(['get', 'post'], 'section-add-edit/{id?}',[SectionController::class,'add_edit_section']);

// Categories

Route::get('categories',[CategoryController::class,'categories'])->name('admin.view-categories');
Route::post('update-category-status',[CategoryController::class,'updateCategoryStatus']);
//categorie delete
Route::get('category-delete/{id}',[CategoryController::class,'deleteCategory'])->name('deleteCategory');
//categorie Add & Update
Route::match(['get', 'post'], 'category-add-edit/{id?}',[CategoryController::class,'add_edit_category']);
Route::get('append-categories-level',[CategoryController::class,'appendCategoryLevel']);

// Product

Route::get('products',[ProductController::class,'products'])->name('admin.view-products');
Route::post('update-product-status',[ProductController::class,'updateProductStatus']);

//Product delete
Route::get('product-delete/{id}',[ProductController::class,'deleteProduct'])->name('deleteProduct');
//Product Image delete
Route::get('product-add-edit/product-image-delete/{id}',[ProductController::class,'deleteProductImage'])->name('deleteProductImage');
//Product Video delete
Route::get('product-add-edit/product-video-delete/{id}',[ProductController::class,'deleteProductVideo'])->name('deleteProductVideo');

//Product Add & Update
Route::match(['get', 'post'], 'product-add-edit/{id?}',[ProductController::class,'add_edit_product']);
// Products Attribute Add
Route::match(['get', 'post'], 'product-add-edit-attribute/{id}',[ProductController::class,'addAttributes']);