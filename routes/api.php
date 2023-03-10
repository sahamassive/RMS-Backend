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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginWithController;
use App\Http\Controllers\RecaptchaController;

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

    
//dashbord
Route::get('trending-order/{id}',[DashboardController::class,'trending']);
Route::get('total-sales-month-wise/{id}',[DashboardController::class,'totalSalesMonthWise']);
Route::get('total-sales-day-wise/{id}',[DashboardController::class,'totalSalesDaykWise']);
Route::get('year-wise-comparison/{id}',[DashboardController::class,'yearWiseComparison']);
Route::get('today-data/{id}',[DashboardController::class,'todayData']);
Route::get('filter-data/{start}/{end}/{id}',[DashboardController::class,'filterData']);
Route::get('filter-chef-data/{chefId}/{id}',[DashboardController::class,'filterDataChef']);
Route::get('filter-sell-data/{start}/{end}/{id}',[DashboardController::class,'filterDataSell']);

//branch
Route::post('branch-insert',[BranchController::class,'branchInsert']);
Route::get('branchs',[BranchController::class,'index']);
Route::get('branch-status/{id}',[BranchController::class,'branchStatus']);
Route::get('branch-edit/{id}',[BranchController::class,'editBranch']);
Route::post('branch-edit/{id}',[BranchController::class,'updateBranch']);
Route::post('branch-food-add',[BranchController::class,'branchFoodAdd']);
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
Route::post('update-category-status',[CategoryController::class,'updateCategoryStatus']);

//categorie delete
Route::get('category-delete/{id}',[CategoryController::class,'deleteCategory'])->name('deleteCategory');

//categorie Add & Update
Route::match(['get', 'post'], 'category-add-edit/{id?}',[CategoryController::class,'add_edit_category']);
Route::get('append-categories-level',[CategoryController::class,'appendCategoryLevel']);
Route::post('category-insert',[CategoryController::class,'categoryInsert']);

//food

Route::post('food-edit/{id}',[FoodController::class,'foodUpdate']);
Route::post('food-insert',[FoodController::class,'foodInsert']);
Route::get('food-item/{id}',[FoodController::class,'getSingleFood']);
Route::post('submit-review',[CustomerController::class,'submitReview']);
Route::get('get-review/{emp_id}',[FoodController::class,'getReview']);

//Employee
Route::post('employee-insert',[HrController::class,'employeeInsert']);
Route::get('get-employee/{filter}',[HrController::class,'getEmployee']);
Route::get('get-all-employee',[HrController::class,'getAllEmployee']);
Route::post('department-insert',[HrController::class,'departmentInsert']);
Route::get('departments',[HrController::class,'getDepartment']);
Route::post('leave-insert',[HrController::class,'leaveInsert']);
Route::get('profile/{type}/{emp_id}',[HrController::class,'profileInfo']);
Route::post('edit-profile/{type}/{emp_id}',[HrController::class,'updateProfileInfo']);
Route::post('change-password/{type}/{emp_id}',[HrController::class,'updatePassword']);
Route::post('edit-customer-profile/{type}/{emp_id}',[HrController::class,'customerProfile']);


//restaurants
Route::post('restaurant-insert',[RestaurantController::class,'restaurantInsert']);
Route::get('restaurants',[RestaurantController::class,'index']);
Route::get('/restaurant/branchs/{restaurant_id}',[RestaurantController::class,'allRestaurantsBranches']);
Route::get('restaurant-status/{id}',[RestaurantController::class,'restaurantStatus']);
Route::get('restaurant-edit/{id}',[RestaurantController::class,'editRestaurant']);
Route::post('restaurant-edit/{id}',[RestaurantController::class,'updateRestaurant']);

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
Route::get('chef/return-inventory/{emp_id}/{ingredient_id}/{inHand}',[ChefController::class,'ChefReturnInventory']);

//table
Route::post('table-insert',[TableController::class,'tableInsert']);
Route::get('table-edit/{id}',[TableController::class,'editTable']);
Route::post('table-edit/{id}',[TableController::class,'updateTable']);

//table type
Route::post('table-type-insert',[TableController::class,'typeInsert']);
Route::get('table-type-list/{id}',[TableController::class,'typeList']);
Route::get('table-type-status/{id}',[TableController::class,'tableTypeStatus']);
Route::get('table-type-edit/{id}',[TableController::class,'editTableType']);
Route::post('table-type-edit/{id}',[TableController::class,'updateTableType']);

//customer
Route::get('customer-order/{customer_id}',[CustomerController::class,'customerOrder']);
Route::get('get-delivery-address/{customer_id}',[CustomerController::class,'customerDeliveryAddress']);
Route::post('change-delivery-address/{customer_id}',[CustomerController::class,'changeDeliveryAddress']);

//discout-apply
Route::get('get-msp/{id}',[OrderController::class,'getMsp']);
Route::get('get-coupon-discount/{id}',[OrderController::class,'getCouponDiscount']);

//order
Route::get('order/recent-order',[OrderController::class,'recentOrder']);

});

//recapcha
Route::post('verify-recaptcha',[RecaptchaController::class,'verify']);
Route::post('login/google',[LoginWithController::class,'handleGoogleLogin']);

//food 
Route::get('category-foods/{id}/{rid}/{bid}',[FoodController::class,'foodByCategory']);

Route::get('foods',[FoodController::class,'foods']);
Route::get('quick-foods/{id}/{bid}',[FoodController::class,'quickfoods']);
Route::get('food-edit/{id}',[FoodController::class,'foodEdit']);
Route::get('multiple-images/{item_code}',[FoodController::class,'getMultipleImage']);
Route::get('sp-foods/{id}',[FoodController::class,'spFoods']);

Route::get('quick-foods-branch/{id}/{bid}',[FoodController::class,'quickfoodsBranch']);


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
Route::get('restaurant/{id}',[RestaurantController::class,'getRestaurant']);
Route::get('branch/{id}',[RestaurantController::class,'getBranch']);
Route::get('restaurant/{id}/{city}',[RestaurantController::class,'getDefBranch']);

//order
Route::post('order-store',[OrderController::class,'orderInsert']);
Route::get('orders',[OrderController::class,'index']);

//login user
Route::post('login-dashboard',[LoginController::class,'loginDashboard']);
Route::post('customer/login-dashboard',[LoginController::class,'CustomerloginDashboard']);

//waiter
Route::get('waiter-with-orders',[WaiterController::class,'getWaiter']);
Route::get('waiter-with-orders/{emp_id}',[WaiterController::class,'getDetails']);
Route::get('waiter-with-orders-count/{emp_id}',[WaiterController::class,'getOrdersCount']);

//customer
Route::post('register-customer',[CustomerController::class,'customerInsert']);

//categories
Route::get('categories',[CategoryController::class,'categories'])->name('admin.view-categories');

//table
Route::get('tables/{id}',[TableController::class,'tableList']);

Route::post('/botman',[BotManController::class,'handle']);
