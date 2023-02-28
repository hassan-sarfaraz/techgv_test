<?php

use App\Http\Controllers\Web\AlertController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WebSite Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test-email', function () {
    Mail::send([], [], function ($message) {
        $message->to('ms.farjadtahir@gmail.com')
            ->subject('Testing Email')
            ->setBody('<h1>Hi, welcome user!</h1>', 'text/html'); // for HTML rich messages
    });
});
/*Route::get('/email-preview', function() {
    $order = DB::table('orders')
        ->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
        ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
        ->where('orders.orders_id', '=', 8)->orderby('orders_status_history.date_added', 'DESC')->get();
    foreach($order as $data){
        $orders_id	 = $data->orders_id;
        $orders_products = DB::table('orders_products')
            ->join('products', 'products.products_id','=', 'orders_products.products_id')
            ->select('orders_products.*', 'products.products_image as image')
            ->where('orders_products.orders_id', '=', $orders_id)->get();
        $i = 0;
        $total_price  = 0;
        $product = array();
        $subtotal = 0;
        foreach($orders_products as $orders_products_data){
            $product_attribute = DB::table('orders_products_attributes')
                ->where([
                    ['orders_products_id', '=', $orders_products_data->orders_products_id],
                    ['orders_id', '=', $orders_products_data->orders_id],
                ])
                ->get();
            $orders_products_data->attribute = $product_attribute;
            $product[$i] = $orders_products_data;
            //$total_tax	 = $total_tax+$orders_products_data->products_tax;
            $total_price = $total_price+$orders_products[$i]->final_price;
            $subtotal += $orders_products[$i]->final_price;
            $i++;
        }
        $data->data = $product;
        $orders_data[] = $data;
    }
    $orders_status_history = DB::table('orders_status_history')
        ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
        ->orderBy('orders_status_history.date_added', 'desc')
        ->where('orders_id', '=', $orders_id)->get();
    $orders_status = DB::table('orders_status')->get();
    $ordersData['orders_data']		 	 	=	$orders_data;
    $ordersData['total_price']  			=	$total_price;
    $ordersData['orders_status']			=	$orders_status;
    $ordersData['orders_status_history']    =	$orders_status_history;
    $ordersData['subtotal']    				=	$subtotal;
    return view('/mail/orderEmail', ['ordersData' => $ordersData]);
});*/

Route::get('/login', ['App\Http\Controllers\Web\CustomersController', 'login']);
Route::post('/process-login', ['App\Http\Controllers\Web\CustomersController', 'processLogin']);
Route::get('/logout', ['App\Http\Controllers\Web\CustomersController', 'logout'])->middleware('Customer');
Route::post('/signupProcess', ['App\Http\Controllers\Web\CustomersController', 'signupProcess']);

// Home Page
Route::get('/', ['App\Http\Controllers\Web\IndexController', 'index']);
// Shop Page
Route::get('/shop', ['App\Http\Controllers\Web\ProductsController', 'shop']);
// News Page
Route::get('/news', ['App\Http\Controllers\Web\NewsController', 'news']);
// About-Us, Privacy Policy, Refund Policy, Terms & Conditions
Route::get('/page', ['App\Http\Controllers\Web\IndexController', 'page']);
// Contact Us
Route::get('/contact', ['App\Http\Controllers\Web\IndexController', 'contactus']);
Route::post('/processContactUs', ['App\Http\Controllers\Web\IndexController', 'processContactUs']);

// Product Details
Route::get('/product-detail/{slug}', ['App\Http\Controllers\Web\ProductsController', 'productDetail']);
// Add to Cart, View Cart, Mode Show, Like My Product, AddToCompare
Route::post('/addToCart', ['App\Http\Controllers\Web\CartController', 'addToCart']);
Route::get('/viewcart', ['App\Http\Controllers\Web\CartController', 'viewcart']);
Route::post('/updateCart', ['App\Http\Controllers\Web\CartController', 'updateCart']);
Route::get('/deleteCart', ['App\Http\Controllers\Web\CartController', 'deleteCart']);
Route::get('/editcart/{id}/{slug}', ['App\Http\Controllers\Web\CartController', 'editcart']);

Route::get('/updatesinglecart', ['App\Http\Controllers\Web\CartController', 'updatesinglecart']);
Route::get('/cartButton', ['App\Http\Controllers\Web\CartController', 'cartButton']);
Route::post('/modal_show', ['App\Http\Controllers\Web\ProductsController', 'ModalShow']);
Route::post('/getquantity', ['App\Http\Controllers\Web\ProductsController', 'getquantity']);
Route::post('/likeMyProduct', ['App\Http\Controllers\Web\CustomersController', 'likeMyProduct']);
Route::post('/addToCompare', ['App\Http\Controllers\Web\CustomersController', 'addToCompare']);
Route::get('/checkout', ['App\Http\Controllers\Web\OrdersController', 'checkout'])->middleware('Customer');
Route::post('/checkout/postpay/full_installment', ['App\Http\Controllers\PostPayController', 'postpayFullIntsallment'])->name('postpayFullIntsallment');
Route::post('/checkout/postpay/installments', ['App\Http\Controllers\PostPayController', 'postpayIntsallments'])->name('postpayIntsallments');
Route::get('/success', ['App\Http\Controllers\PostPayController', 'postpayConfirmOrder']);
Route::get('/mobileSuccess', ['App\Http\Controllers\PostPayController', 'postpayMobileConfirmOrder']);
Route::post('/cancel', ['App\Http\Controllers\PostPayController', 'postpayOrderCancelled'])->name('postpayOrderCancelled');
Route::name('web.')->group(function () {
    // Login
    // Exception Handling
    Route::get('general_error/{msg}', function ($msg) {
        return view('errors.general_error', ['msg' => $msg]);
    });

    // Customer Panel
    Route::post('/change_currency', ['App\Http\Controllers\Web\WebSettingController', 'changeCurrency']);
    Route::post('/change_language', ['App\Http\Controllers\Web\WebSettingController', 'changeLanguage']);
    Route::get('/checkout', ['App\Http\Controllers\Web\OrdersController', 'checkout'])->middleware('Customer');
    Route::post('/checkout_shipping_address', ['App\Http\Controllers\Web\OrdersController', 'checkout_shipping_address'])->middleware('Customer');
    Route::post('/checkout_billing_address', ['App\Http\Controllers\Web\OrdersController', 'checkout_billing_address'])->middleware('Customer');
    Route::post('/checkout_payment_method', ['App\Http\Controllers\Web\OrdersController', 'checkout_payment_method'])->middleware('Customer');
    Route::post('/paymentComponent', ['App\Http\Controllers\Web\OrdersController', 'paymentComponent'])->middleware('Customer');
    Route::post('/place_order', ['App\Http\Controllers\Web\OrdersController', 'place_order'])->middleware('Customer');
    Route::post('/myorders', ['App\Http\Controllers\Web\OrdersController', 'myorders'])->middleware('Customer');
    Route::get('/stripeForm', ['App\Http\Controllers\Web\OrdersController', 'stripeForm'])->middleware('Customer');
    Route::post('/pay-instamojo', ['App\Http\Controllers\Web\OrdersController', 'payIinstamojo'])->middleware('Customer');
    Route::post('/pay-payd', ['App\Http\Controllers\Web\OrdersController', 'payPayd'])->middleware('Customer');
    Route::get('/pay-payd-callback', ['App\Http\Controllers\Web\OrdersController', 'payPaydCallback'])->middleware('Customer');

    // My Accounts Tabs
    Route::get('/profile', ['App\Http\Controllers\Web\CustomersController', 'profile'])->middleware('Customer');
    Route::get('/wishlist', ['App\Http\Controllers\Web\CustomersController', 'wishlist'])->middleware('Customer');
    Route::post('/updateMyProfile', ['App\Http\Controllers\Web\CustomersController', 'updateMyProfile'])->middleware('Customer');
    Route::post('/updateMyPassword', ['App\Http\Controllers\Web\CustomersController', 'updateMyPassword'])->middleware('Customer');
    Route::get('UnlikeMyProduct/{id}', ['App\Http\Controllers\Web\CustomersController', 'unlikeMyProduct'])->middleware('Customer');
    Route::post('likeMyProduct', ['App\Http\Controllers\Web\CustomersController', 'likeMyProduct']);
    Route::post('addToCompare', ['App\Http\Controllers\Web\CustomersController', 'addToCompare']);
    Route::get('compare', ['App\Http\Controllers\Web\CustomersController', 'Compare'])->middleware('Customer');
    Route::get('deletecompare/{id}', ['App\Http\Controllers\Web\CustomersController', 'DeleteCompare'])->middleware('Customer');

    // Orders & Shipping Addressess
    Route::get('/orders', ['App\Http\Controllers\Web\OrdersController', 'orders'])->middleware('Customer');
    Route::get('/view-order/{id}', ['App\Http\Controllers\Web\OrdersController', 'viewOrder'])->middleware('Customer');
    Route::post('/updatestatus', ['App\Http\Controllers\Web\OrdersController', 'updatestatus'])->middleware('Customer');
    Route::get('/shipping-address', ['App\Http\Controllers\Web\ShippingAddressController', 'shippingAddress'])->middleware('Customer');
    Route::post('/addMyAddress', ['App\Http\Controllers\Web\ShippingAddressController', 'addMyAddress'])->middleware('Customer');
    Route::post('/myDefaultAddress', ['App\Http\Controllers\Web\ShippingAddressController', 'myDefaultAddress'])->middleware('Customer');
    Route::post('/update-address', ['App\Http\Controllers\Web\ShippingAddressController', 'updateAddress'])->middleware('Customer');
    Route::get('/delete-address/{id}', ['App\Http\Controllers\Web\ShippingAddressController', 'deleteAddress'])->middleware('Customer');
    Route::post('/ajaxZones', ['App\Http\Controllers\Web\ShippingAddressController', 'ajaxZones']);

    Route::get('login/{social}', ['App\Http\Controllers\Web\CustomersController', 'socialLogin']);
    Route::get('login/{social}/callback', ['App\Http\Controllers\Web\CustomersController', 'handleSocialLoginCallback']);
    Route::post('/commentsOrder', ['App\Http\Controllers\Web\OrdersController', 'commentsOrder']);
    Route::post('/subscribeNotification/', ['App\Http\Controllers\Web\CustomersController', 'subscribeNotification']);

    Route::get('/signup', ['App\Http\Controllers\Web\CustomersController', 'signup']);
    Route::get('/forgotPassword', ['App\Http\Controllers\Web\CustomersController', 'forgotPassword']);
    Route::get('/recoverPassword', ['App\Http\Controllers\Web\CustomersController', 'recoverPassword']);
    Route::post('/processPassword', ['App\Http\Controllers\Web\CustomersController', 'processPassword']);

    Route::get('/checkout/hyperpay', ['App\Http\Controllers\Web\OrdersController', 'hyperpay'])->middleware('Customer');
    Route::get('/checkout/hyperpay/checkpayment', ['App\Http\Controllers\Web\OrdersController', 'checkpayment'])->middleware('Customer');
    Route::post('/checkout/payment/changeresponsestatus', ['App\Http\Controllers\Web\OrdersController', 'changeresponsestatus'])->middleware('Customer');
    Route::post('/apply_coupon', ['App\Http\Controllers\Web\CartController', 'apply_coupon']);
    Route::get('/removeCoupon/{id}', ['App\Http\Controllers\Web\CartController', 'removeCoupon']);

    Route::get('/guest_checkout', ['App\Http\Controllers\Web\OrdersController', 'guest_checkout']);
    Route::post('/filterProducts', ['App\Http\Controllers\Web\ProductsController', 'filterProducts']);
    Route::post('/shop', ['App\Http\Controllers\Web\ProductsController', 'shop']);
    Route::post('/loadMoreNews', ['App\Http\Controllers\Web\NewsController', 'loadMoreNews']);
    Route::get('/news-detail/{slug}', ['App\Http\Controllers\Web\NewsController', 'newsDetail']);

    Route::get('/ajaxsearch', ['App\Http\Controllers\Web\ProductsController', 'ajaxsearch'])->name('products.ajaxsearch');
});
