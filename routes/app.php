<?php

use App\Http\Controllers\App\CartController;
use App\Http\Controllers\App\Auth\LoginController;
use App\Http\Controllers\App\AgegroupController;
use App\Http\Controllers\App\AttributeController;
use App\Http\Controllers\App\Auth\RegisterController;
use App\Http\Controllers\App\BlogController;
use App\Http\Controllers\App\BrandController;
use App\Http\Controllers\App\CategoryController;
use App\Http\Controllers\App\FormController;
use App\Http\Controllers\App\HomeController;
use App\Http\Controllers\App\PageController;
use App\Http\Controllers\App\ProductController;
use App\Http\Controllers\App\PromotionController;
use App\Http\Controllers\App\ReviewController;
use App\Http\Controllers\App\ProfileController;
use App\Http\Controllers\App\SalesController;
use App\Http\Controllers\App\SectionController;
use App\Http\Controllers\App\WishlistController;
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
// Route::post('login', [AuthController::class, 'login']);
// Route::middleware('auth:api')->group(function () {

Route::group(['as' => 'app.'], function () {


    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'create']);

    Route::prefix("home")->controller(HomeController::class)->group(function () {
        Route::get('all', 'index');
        Route::get('slider', 'slider');
        Route::get('featureCategories', 'featureCategories');
        Route::get('trandingCategories', 'trandingCategories');
        Route::get('listing', 'listing');
        Route::get('agegroup', 'agegroup');
        Route::get('ads', 'ads');
        Route::get('gender', 'gender');
        Route::get('newsletter', 'newsletter');
        Route::get('services', 'services');
        Route::get('faqs', 'faqs');
        Route::get('siteinfo', 'siteinfo');
    });


    Route::prefix("blogs")->controller(BlogController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{blog}', 'show');
    });


    Route::prefix("pages")->controller(PageController::class)->group(function () {
        Route::get('/typelist', 'typeList');
        Route::get('/{type}/type', 'type');
    });

    Route::prefix("products")->controller(ProductController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{product}', 'show');
    });

    Route::prefix("attributes")->controller(AttributeController::class)->group(function () {
        Route::get('/{product}', 'index');
    });

    Route::prefix("brands")->controller(BrandController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{brand}', 'show');
    });

    Route::prefix("promotions")->controller(PromotionController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{promotion}', 'show');
    });

    Route::prefix("category")->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{category}', 'show');
    });

    Route::prefix("age-groups")->controller(AgegroupController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{ageGroup}', 'show');
    });

    Route::prefix("sections")->controller(SectionController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{section}', 'show');
    });

    Route::controller(FormController::class)->group(function () {
        Route::post('/contactus', 'contactus');
        Route::post('/subscribe', 'subscribe');
    });

    // all protected route should be inside this
    Route::group(['middleware' => 'auth:app'], function () {

        Route::prefix("profile")->controller(ProfileController::class)->group(function () {
            Route::get('/', 'show');
            Route::post('/', 'update');
            Route::post('/password', 'updatePassword');
            Route::get('/address', 'addressList');
            Route::post('/address', 'addressAdd');
            Route::patch('/address/{id}', 'addressUpdate');
            Route::delete('/address/{id}', 'addressUpdate');
            Route::post('/logout', 'logout');
        });

        Route::prefix("cart")->controller(CartController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'addToCart');
            Route::post('/checkout', 'checkOut');
            Route::delete('/coupon', 'clearCoupon');
            Route::post('/coupon/{code}', 'applyCoupon');
            Route::delete('/delete-all', 'removeAll');
            Route::delete('/{item}', 'removeCart');
        });

        Route::prefix("wishlist")->controller(WishlistController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'toggle');
            Route::delete('/delete-all', 'deleteAll');
            Route::delete('/{id}', 'delete');
        });

        Route::prefix("review")->controller(ReviewController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/myreview', 'myreview');
            Route::post('/{product}', 'store');
            Route::get('/{review}', 'show');
            Route::patch('/{review}', 'update');
            Route::delete('/{review}', 'delete');
        });

        Route::prefix("orders")->controller(SalesController::class)->group(function () {
            Route::get('/', 'myOrders');
            Route::get('/{order}', 'details');
            Route::get('/{order}/cancel', 'cancelOrder');
            Route::get('/{order}/cancel/{item}', 'cancelOrderItem');
            Route::get('/{order}/payment', 'paymentOrder');
        });
    });


    Route::controller(ResourceController::class)->group(function () {
        Route::post('resource', 'uploadFile');
        Route::patch('resource/{id}', 'update');
        Route::delete('resource/{id}', 'destroy');
    });
});
