<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AgeGroupController;
use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\SubscriberController;
use App\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
Route::post('login', [AuthController::class, 'login']);
// Route::middleware('auth:api')->group(function () {

Route::get("pages/types", [PageController::class, "types"]);
Route::apiResources([
    'brands' => BrandController::class,
    'agegroups' => AgeGroupController::class,
    'blogs' => BlogController::class,
    'pages' => PageController::class,
    'faqs' => FaqController::class,
    'sliders' => SliderController::class,
    "sections" => SectionController::class,
    "admins" => AdminController::class
]);



Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'index');
    Route::post('products', 'store');
    Route::get('products/attributes', 'attribute_list');
    Route::get('products/{product}', 'show');
    Route::patch('products/{product}', 'update');
    Route::delete('products/{product}', 'destroy');
    Route::get('products/{product}/attribute', 'show');
    Route::get('product-gallery/{product}', 'gallery');
    Route::patch('product-gallery/{product}', 'gallerystore');
});
Route::apiResource("attributes/{product}", AttributeController::class)->only(["index", "store"]);
Route::apiResource("reviews", ReviewController::class)->except(["create"]);
Route::apiResource("contactus", ContactUsController::class)->only(["index", "update", "show"]);
Route::apiResource("coupons", CouponController::class);
Route::controller(SaleController::class)->group(function () {
    Route::patch('sales/change-status/{sale}', 'changeStatus');
});

Route::apiResource('sales', SaleController::class);

Route::apiResource('payments', PaymentController::class);

// Route defination for categories
Route::get('category/list', [CategoryController::class, 'catlist']);
Route::get('category/leaf/{category}', [CategoryController::class, 'getLeaf']);
Route::apiResource("category", CategoryController::class);
Route::post('category/feature', [CategoryController::class, 'featureUpdate']);
Route::post('category/trending', [CategoryController::class, 'trendingUpdate']);

// Route defination for customers
Route::apiResource("customers", CustomerController::class)->only(["index", "show"]);

// Route defination for subscribers
Route::apiResource("subscribers", SubscriberController::class)->only(["index"]);

// Route defination for settings
Route::controller(SettingController::class)->group(function () {
    Route::get('settings/{setting}', 'show');
    Route::patch('settings/{setting}', 'update');
});
// Route::apiSingleton("setting", SettingController::class);


Route::controller(ResourceController::class)->group(function () {
    Route::post('resource', 'uploadFile');
    Route::patch('resource/{id}', 'update');
    Route::delete('resource/{id}', 'destroy');
});

    // });
// });
