<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Food\BrandController;
use App\Http\Controllers\Food\SectionController;
use App\Http\Controllers\Food\CategoryController;
use App\Http\Controllers\Food\FoodController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\RestaurantController;

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
Route::get('brands',[BrandController::class,'brand']);
Route::post('update-brand-status',[BrandController::class,'updateBrandStatus']);
//brand delete
Route::get('brand-delete/{id}',[BrandController::class,'deleteBrand'])->name('deleteBrand');
//brand Add & Update
Route::match(['get', 'post'], 'brand-add-edit/{id?}',[BrandController::class,'add_edit_brand']);
Route::post('brand-insert',[BrandController::class,'brandInsert']);
Route::get('brand-status/{id}',[BrandController::class,'brandStatus']);
Route::get('brand-edit/{id}',[BrandController::class,'brandEdit']);
Route::post('brand-update/{id}',[BrandController::class,'brandUpdate']);


//Sections

Route::get('sections',[SectionController::class,'sections']);
Route::post('update-section-status',[SectionController::class,'updateSectionStatus']);
//section delete
Route::get('section-delete/{id}',[SectionController::class,'deleteSection'])->name('deleteSection');
//section Add & Update
Route::match(['get', 'post'], 'section-add-edit/{id?}',[SectionController::class,'add_edit_section']);
Route::post('section-insert',[SectionController::class,'sectionInsert']);
Route::get('section-edit/{id}',[SectionController::class,'sectionEdit']);
Route::post('section-update/{id}',[SectionController::class,'sectionUpdate']);
Route::get('section-status/{id}',[SectionController::class,'sectionStatus']);
// Categories

Route::get('categories',[CategoryController::class,'categories'])->name('admin.view-categories');
Route::post('update-category-status',[CategoryController::class,'updateCategoryStatus']);
//categorie delete
Route::get('category-delete/{id}',[CategoryController::class,'deleteCategory'])->name('deleteCategory');
//categorie Add & Update
Route::match(['get', 'post'], 'category-add-edit/{id?}',[CategoryController::class,'add_edit_category']);
Route::get('append-categories-level',[CategoryController::class,'appendCategoryLevel']);
Route::post('category-insert',[CategoryController::class,'categoryInsert']);


//food 
Route::post('food-insert',[FoodController::class,'foodInsert']);
Route::get('foods',[FoodController::class,'foods']);
Route::get('quick-foods',[FoodController::class,'quickfoods']);
Route::get('category-foods/{id}',[FoodController::class,'foodByCategory']);
Route::get('food-edit/{id}',[FoodController::class,'foodEdit']);

//Employee
Route::post('employee-insert',[HrController::class,'employeeInsert']);
Route::get('get-employee/{filter}',[HrController::class,'getEmployee']);
Route::post('department-insert',[HrController::class,'departmentInsert']);
Route::get('departments',[HrController::class,'getDepartment']);


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


//restaurant
Route::post('restaurant-insert',[RestaurantController::class,'restaurantInsert']);
Route::get('restaurants',[RestaurantController::class,'index']);
Route::get('restaurant-status/{id}',[RestaurantController::class,'restaurantStatus']);
Route::get('restaurant-edit/{id}',[RestaurantController::class,'editRestaurant']);
Route::post('restaurant-edit/{id}',[RestaurantController::class,'updateRestaurant']);
