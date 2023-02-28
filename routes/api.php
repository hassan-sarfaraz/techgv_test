<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\ProductsAPIController;
use App\Http\Controllers\API\WishlistAPIController;
use App\Http\Controllers\API\PaymentsAPIController;
use App\Http\Controllers\API\ShippingAPIController;
use App\Http\Controllers\API\OrdersAPIController;
use App\Http\Controllers\API\VerificationsAPIController;
use App\Http\Controllers\API\HelpersAPIController;
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

Route::post('login', [AuthController::class, 'signin']);
Route::post('create_customer', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/social_login', [AuthController::class, 'SocialLogin']);
Route::middleware(['auth:sanctum'])->group( function () {
    Route::post('customer', [AuthController::class, 'customerDetails']);
    Route::post('reset_password', [AuthController::class, 'resetPassword']);
    Route::post('update_customer', [AuthController::class, 'updateCustomer']);
    Route::post('/my-orders', [OrdersAPIController::class, 'my_orders'])->name('orders.my_orders');
});

Route::post('forgot_password', [AuthController::class, 'forgotPassword']);
Route::post('/report/problem', [HomeController::class, 'reportProblem'])->name('report.problem');

Route::post('/submit/order', [OrdersAPIController::class, 'index'])->name('orders.index');
Route::post('/postpay/submit/order', [OrdersAPIController::class, 'postpay'])->name('orders.postpay');
Route::post('/verify-coupon', [OrdersAPIController::class, 'verify_coupon'])->name('verifications.coupon');

Route::post('/home', [HomeController::class, 'index'])->name('home.index');
Route::post('/brands', [HomeController::class, 'brands'])->name('home.brands');
Route::post('/stores', [HomeController::class, 'stores'])->name('home.stores');
Route::post('/news-tickers', [HomeController::class, 'news_tickers'])->name('home.news_tickers');

Route::post('/products', [ProductsAPIController::class, 'index'])->name('products.index');
Route::post('/live_search', [ProductsAPIController::class, 'live_search'])->name('products.live_search');
Route::post('/check-stock', [ProductsAPIController::class, 'check_stock'])->name('products.check_stock');
Route::post('/variable-check-stock', [ProductsAPIController::class, 'variable_check_stock'])->name('products.variable_check_stock');

Route::post('/wishlist', [WishlistAPIController::class, 'index'])->name('wishlist.index');
Route::post('/get_payment_gateways', [PaymentsAPIController::class, 'index'])->name('payments.index');
Route::post('/get_shippings', [ShippingAPIController::class, 'index'])->name('shipping.index');
Route::post('/generate-otp/email', [VerificationsAPIController::class, 'emailOtp'])->name('verifications.email.otp');
Route::post('/verify-otp/email', [VerificationsAPIController::class, 'verify_emailOtp'])->name('verifications.email.verify.otp');
Route::post('/verify-otp/phone', [VerificationsAPIController::class, 'verify_phoneOtp'])->name('verifications.phone.verify.otp');
/*Route::post('/generate-otp/phone', [VerificationsAPIController::class, 'phoneOtp'])->name('verifications.phone.otp');*/

Route::post('/check-app-version', [HelpersAPIController::class, 'app_versions'])->name('helpers.app_versions');
