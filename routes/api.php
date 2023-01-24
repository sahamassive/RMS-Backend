<?php

use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Food\BrandController;
use App\Http\Controllers\Food\SectionController;
use App\Http\Controllers\Food\CategoryController;
use App\Http\Controllers\Food\FoodController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\BranchController;

use App\Http\Controllers\OrderController;

use App\Http\Controllers\WasteController;
use App\Http\Controllers\DiscountController;


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
Route::get('quick-foods/{id}/{bid}',[FoodController::class,'quickfoods']);
Route::get('category-foods/{id}/{rid}/{bid}',[FoodController::class,'foodByCategory']);
Route::get('food-edit/{id}',[FoodController::class,'foodEdit']);
Route::post('food-edit/{id}',[FoodController::class,'foodUpdate']);
Route::get('sp-foods',[FoodController::class,'spFoods']);

//Employee
Route::post('employee-insert',[HrController::class,'employeeInsert']);
Route::get('get-employee/{filter}',[HrController::class,'getEmployee']);
Route::post('department-insert',[HrController::class,'departmentInsert']);
Route::get('departments',[HrController::class,'getDepartment']);

//booking
Route::post('booking-insert',[BookingController::class,'bookingInsert']);
Route::get('bookings',[BookingController::class,'getBooking']);


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
Route::get('/restaurant/branchs/{restaurant_id}',[RestaurantController::class,'allRestaurantsBranches']);
Route::get('restaurant-status/{id}',[RestaurantController::class,'restaurantStatus']);
Route::get('restaurant-edit/{id}',[RestaurantController::class,'editRestaurant']);
Route::post('restaurant-edit/{id}',[RestaurantController::class,'updateRestaurant']);
Route::get('restaurant/{id}',[RestaurantController::class,'getRestaurant']);
Route::get('branch/{id}',[RestaurantController::class,'getBranch']);
Route::get('restaurant/{id}/{city}',[RestaurantController::class,'getDefBranch']);

//branch
Route::post('branch-insert',[BranchController::class,'branchInsert']);
Route::get('branchs',[BranchController::class,'index']);
Route::get('branch-status/{id}',[BranchController::class,'branchStatus']);
Route::get('branch-edit/{id}',[BranchController::class,'editBranch']);
Route::post('branch-edit/{id}',[BranchController::class,'updateBranch']);
Route::post('branch-food-add',[BranchController::class,'branchFoodAdd']);


//order
Route::post('order-store',[OrderController::class,'orderInsert']);

//waste
Route::post('waste-insert',[WasteController::class,'wasteInsert']);
Route::get('wastes',[WasteController::class,'allWaste']);
Route::get('details-wastes/{employee_id}',[WasteController::class,'wasteDetails']);
Route::get('wastes-edit/{waste_id}',[WasteController::class,'editWaste']);
Route::post('wastes-edit/{waste_id}',[WasteController::class,'updateWaste']);

//discount
Route::post('discount-insert',[DiscountController::class,'discountInsert']);
Route::get('discounts',[DiscountController::class,'index']);
Route::get('discount-status/{id}',[DiscountController::class,'discountStatus']);
Route::get('discount-edit/{id}',[DiscountController::class,'editDiscount']);
Route::post('discount-edit/{id}',[DiscountController::class,'updateDiscount']);


