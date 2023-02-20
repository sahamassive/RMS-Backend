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
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Inventory\SupplierController;
use App\Http\Controllers\Inventory\InvoiceController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\CustomerController;



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

Route::middleware('auth:sanctum')->group(function () {

//branch
Route::post('branch-insert',[BranchController::class,'branchInsert']);
Route::get('branchs',[BranchController::class,'index']);
Route::get('branch-status/{id}',[BranchController::class,'branchStatus']);
Route::get('branch-edit/{id}',[BranchController::class,'editBranch']);
Route::post('branch-edit/{id}',[BranchController::class,'updateBranch']);
Route::post('branch-food-add',[BranchController::class,'branchFoodAdd']);


});

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

Route::get('quick-foods-branch/{id}/{bid}',[FoodController::class,'quickfoodsBranch']);

//Employee
Route::post('employee-insert',[HrController::class,'employeeInsert']);
Route::get('get-employee/{filter}',[HrController::class,'getEmployee']);
Route::post('department-insert',[HrController::class,'departmentInsert']);
Route::get('departments',[HrController::class,'getDepartment']);
Route::post('leave-insert',[HrController::class,'leaveInsert']);
Route::get('profile/{type}/{emp_id}',[HrController::class,'profileInfo']);

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



//order
Route::post('order-store',[OrderController::class,'orderInsert']);
Route::get('orders',[OrderController::class,'index']);
Route::get('order/recent-order',[OrderController::class,'recentOrder']);
Route::get('get-msp/{id}',[OrderController::class,'getMsp']);
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



// Recipe
Route::post('ingredient-insert',[RecipeController::class,'ingredientInsert']);
Route::get('ingredient-list/{id}',[RecipeController::class,'ingredientList']);
Route::get('ingredient-status/{id}',[RecipeController::class,'ingredientStatus']);
Route::get('ingredient-edit/{id}',[RecipeController::class,'editIngredient']);
Route::post('ingredient-edit/{id}',[RecipeController::class,'updateIngredient']);
Route::post('recipe-insert',[RecipeController::class,'recipeInsert']);

Route::get('items/{id}',[RecipeController::class,'itemList']);
Route::get('basic-price/{id}',[RecipeController::class,'basicPrice']);
Route::post('updatePrice/{id}',[RecipeController::class,'priceUpdate']);
//item
Route::get('get-items/{id}',[RecipeController::class,'getItem']);
Route::get('item-edit/{id}',[RecipeController::class,'editItem']);
Route::post('item-update/{id}',[RecipeController::class,'updateItem']);

//coupon
Route::post('coupon-insert',[CouponController::class,'couponInsert']);
Route::get('coupons',[CouponController::class,'index']);
Route::get('coupon-status/{id}',[CouponController::class,'couponStatus']);
Route::get('coupon-edit/{id}',[CouponController::class,'editCoupon']);
Route::post('coupon-edit/{id}',[CouponController::class,'updateCoupon']);

//login user
Route::post('login-dashboard',[LoginController::class,'loginDashboard']);
Route::post('customer/login-dashboard',[LoginController::class,'CustomerloginDashboard']);

//supplier
Route::get('suppliers/{id}',[SupplierController::class,'index']);
Route::post('supplier-insert',[SupplierController::class,'supplierInsert']);
Route::get('supplier-status/{id}',[SupplierController::class,'supplierStatus']);
Route::get('supplier-edit/{id}',[SupplierController::class,'editSupplier']);
Route::post('supplier-edit/{id}',[SupplierController::class,'updateSupplier']);

//invoices
Route::post('invoice-insert',[InvoiceController::class,'invoiceInsert']);
Route::get('invoice-details/{invoice_id}',[InvoiceController::class,'invoiceDetails']);
Route::get('invoices',[InvoiceController::class,'index']);

//inventory
Route::get('inventories',[InventoryController::class,'index']);
Route::post('inventory-distribution',[InventoryController::class,'inventoryDistribution']);
Route::post('inventory-transfer',[InventoryController::class,'inventoryTransfer']);

//chef
Route::get('chefs/{id}',[ChefController::class,'index']);
Route::get('chef-inventory/{emp_id}/{filter}',[ChefController::class,'ChefInventory']);
Route::get('chef-order/{emp_id}/{order_id}/{food_id}/{quantity}',[ChefController::class,'ChefOrder']);
Route::get('chef-attend-order/{emp_id}/{filter}',[ChefController::class,'ChefAttendOrder']);
Route::get('chef-attend-order-status/{order_id}/{item_code}',[ChefController::class,'ChefAttendOrderStatus']);

//table
Route::post('table-insert',[TableController::class,'tableInsert']);
Route::get('tables/{id}',[TableController::class,'tableList']);
Route::get('table-edit/{id}',[TableController::class,'editTable']);
Route::post('table-edit/{id}',[TableController::class,'updateTable']);

//table type
Route::post('table-type-insert',[TableController::class,'typeInsert']);
Route::get('table-type-list/{id}',[TableController::class,'typeList']);
Route::get('table-type-status/{id}',[TableController::class,'tableTypeStatus']);
Route::get('table-type-edit/{id}',[TableController::class,'editTableType']);
Route::post('table-type-edit/{id}',[TableController::class,'updateTableType']);

//waiter
Route::get('waiter-with-orders',[WaiterController::class,'getWaiter']);

//customer
Route::post('register-customer',[CustomerController::class,'customerInsert']);
Route::get('customer-order/{emp_id}',[CustomerController::class,'customerOrder']);


