<?php
Route::get('/clear-cache', function() {
	$exitCode = Artisan::call('cache:clear');
	$exitCode = Artisan::call('config:clear');
});
Route::get('/phpinfo', function() {
	phpinfo();
});
Route::get('/not_allowed', function () {
    return view('errors.not_found');
});

// ## Login Section
Route::prefix('admin')->namespace('AdminControllers')->group(function () {
    Route::get('/login', ['App\Http\Controllers\AdminControllers\AdminController', 'login']);
    Route::post('/checkLogin', ['App\Http\Controllers\AdminControllers\AdminController', 'checkLogin']);
});


// ## Auth Section
Route::prefix('admin')->namespace('AdminControllers')->middleware('auth')->group(function () {

    Route::get('/dashboard/{reportBase}', ['App\Http\Controllers\AdminControllers\AdminController', 'dashboard']);
    Route::get('/logout', ['App\Http\Controllers\AdminControllers\AdminController', 'logout']);
    Route::get('logout', ['App\Http\Controllers\Auth\LoginController', 'logout']);

    Route::get('/webPagesSettings/{id}', ['App\Http\Controllers\AdminControllers\ThemeController', 'index2']);

    Route::get('/admin/profile', ['App\Http\Controllers\AdminControllers\AdminController','profile']);
    Route::post('/admin/update', ['App\Http\Controllers\AdminControllers\AdminController','update']);
    Route::post('/admin/updatepassword', ['App\Http\Controllers\AdminControllers\AdminController','updatepassword']);


    Route::get('/home', function () {
        return redirect('/dashboard/{reportBase}');
    });

    // Reports
    Route::get('/statscustomers', ['App\Http\Controllers\AdminControllers\ReportsController','statsCustomers'])->middleware('report');
    Route::get('/statsproductspurchased', ['App\Http\Controllers\AdminControllers\ReportsController','statsProductsPurchased'])->middleware('report');
    Route::get('/statsproductsliked', ['App\Http\Controllers\AdminControllers\ReportsController','statsProductsLiked'])->middleware('report');
    Route::get('/outofstock', ['App\Http\Controllers\AdminControllers\ReportsController','outofstock'])->middleware('report');
    Route::get('/lowinstock', ['App\Http\Controllers\AdminControllers\ReportsController','lowinstock'])->middleware('report');
    Route::get('/stockin', ['App\Http\Controllers\AdminControllers\ReportsController','stockin'])->middleware('report');
    Route::post('/productSaleReport',['App\Http\Controllers\AdminControllers\ReportsController','productSaleReport'])->middleware('report');

    Route::get('/conversion-rate',['App\Http\Controllers\AdminControllers\ReportsController','conversionRate'])->middleware('report');
    Route::get('/top-searched-keywords',['App\Http\Controllers\AdminControllers\ReportsController','topSearchedkeywords'])->middleware('report');

    Route::get('/best-performing-categories',['App\Http\Controllers\AdminControllers\ReportsController','bestPerformingCategories'])->middleware('report');
    Route::get('/best-selling-products',['App\Http\Controllers\AdminControllers\ReportsController','bestSellingProducts'])->middleware('report');

    // Add Adddresses against Customers
    Route::get('/addaddress/{id}/', ['App\Http\Controllers\AdminControllers\CustomersController','addaddress'])->middleware('add_customer');
    Route::post('/addNewCustomerAddress', ['App\Http\Controllers\AdminControllers\CustomersController','addNewCustomerAddress'])->middleware('add_customer');
    Route::post('/editAddress', ['App\Http\Controllers\AdminControllers\CustomersController','editAddress'])->middleware('edit_customer');
    Route::post('/updateAddress', ['App\Http\Controllers\AdminControllers\CustomersController','updateAddress'])->middleware('edit_customer');
    Route::post('/deleteAddress', ['App\Http\Controllers\AdminControllers\CustomersController','deleteAddress'])->middleware('delete_customer');
    Route::post('/getZones', ['App\Http\Controllers\AdminControllers\AddressController','getzones']);

    ////////////////////////////////////////////////////////////////////////////////////
    //////////////     Web Site Landing Page Adminpanel Routes
    ////////////////////////////////////////////////////////////////////////////////////


    // Sliders
    Route::get('/sliders', ['App\Http\Controllers\AdminControllers\AdminSlidersController','sliders'])->middleware('website_routes');
    Route::get('/addsliderimage', ['App\Http\Controllers\AdminControllers\AdminSlidersController','addsliderimage'])->middleware('website_routes');
    Route::post('/addNewSlide', ['App\Http\Controllers\AdminControllers\AdminSlidersController','addNewSlide'])->middleware('website_routes');
    Route::get('/editslide/{id}', ['App\Http\Controllers\AdminControllers\AdminSlidersController','editslide'])->middleware('website_routes');
    Route::post('/updateSlide', ['App\Http\Controllers\AdminControllers\AdminSlidersController','updateSlide'])->middleware('website_routes');
    Route::post('/deleteSlider/', ['App\Http\Controllers\AdminControllers\AdminSlidersController','deleteSlider'])->middleware('website_routes');

    // Constant Banners
    Route::get('/constantbanners', ['App\Http\Controllers\AdminControllers\AdminConstantController','constantBanners'])->middleware('website_routes');
    Route::get('/addconstantbanner', ['App\Http\Controllers\AdminControllers\AdminConstantController','addconstantBanner'])->middleware('website_routes');
    Route::post('/addNewConstantBanner', ['App\Http\Controllers\AdminControllers\AdminConstantController','addNewconstantBanner'])->middleware('website_routes');
    Route::get('/editconstantbanner/{id}', ['App\Http\Controllers\AdminControllers\AdminConstantController','editconstantbanner'])->middleware('website_routes');
    Route::post('/updateconstantBanner', ['App\Http\Controllers\AdminControllers\AdminConstantController','updateconstantBanner'])->middleware('website_routes');
    Route::post('/deleteconstantBanner/', ['App\Http\Controllers\AdminControllers\AdminConstantController','deleteconstantBanner'])->middleware('website_routes');

    // Web Pages
    Route::get('/webPagesSettings/changestatus', ['App\Http\Controllers\AdminControllers\ThemeController', 'changestatus']);
    Route::get('/webPagesSettings/setting/set', ['App\Http\Controllers\AdminControllers\ThemeController', 'set']);
    Route::get('/webPagesSettings/reorder', ['App\Http\Controllers\AdminControllers\ThemeController', 'reorder']);

    ////////////////////////////////////////////////////////////////////////////////////
    //////////////     SITE ROUTES
    ////////////////////////////////////////////////////////////////////////////////////

    // Site Pages Controller
    Route::get('/webpages', ['App\Http\Controllers\AdminControllers\PagesController','webpages'])->middleware('view_web_setting', 'website_routes');
    Route::get('/addwebpage', ['App\Http\Controllers\AdminControllers\PagesController','addwebpage'])->middleware('edit_web_setting', 'website_routes');
    Route::post('/addnewwebpage', ['App\Http\Controllers\AdminControllers\PagesController','addnewwebpage'])->middleware('edit_web_setting', 'website_routes');
    Route::get('/editwebpage/{id}', ['App\Http\Controllers\AdminControllers\PagesController','editwebpage'])->middleware('edit_web_setting', 'website_routes');
    Route::post('/updatewebpage', ['App\Http\Controllers\AdminControllers\PagesController','updatewebpage'])->middleware('edit_web_setting', 'website_routes');
    Route::get('/pageWebStatus', ['App\Http\Controllers\AdminControllers\PagesController','pageWebStatus'])->middleware('view_web_setting', 'website_routes');

    Route::get('/webthemes', ['App\Http\Controllers\AdminControllers\SiteSettingController','webThemes'])->middleware('view_web_setting', 'website_routes');
    Route::get('/themeSettings', ['App\Http\Controllers\AdminControllers\SiteSettingController','themeSettings'])->middleware('edit_web_setting', 'website_routes');

    Route::get('/seo', ['App\Http\Controllers\AdminControllers\SiteSettingController','seo'])->middleware('view_web_setting', 'website_routes');
    Route::get('/customstyle', ['App\Http\Controllers\AdminControllers\SiteSettingController','customstyle'])->middleware('view_web_setting', 'website_routes');
    Route::post('/updateWebTheme', ['App\Http\Controllers\AdminControllers\SiteSettingController','updateWebTheme'])->middleware('edit_web_setting', 'website_routes');
    Route::get('/websettings', ['App\Http\Controllers\AdminControllers\SiteSettingController','webSettings'])->middleware('view_web_setting', 'website_routes');



    /////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    //////////////     GENERAL ROUTES
    ////////////////////////////////////////////////////////////////////////////////////

    //units
    Route::get('/units', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'units'])->middleware('view_general_setting');
    Route::get('/addunit', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'addunit'])->middleware('edit_general_setting');
    Route::post('/addnewunit', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'addnewunit'])->middleware('edit_general_setting');
    Route::get('/editunit/{id}', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'editunit'])->middleware('edit_general_setting');
    Route::post('/updateunit', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'updateunit'])->middleware('edit_general_setting');
    Route::post('/deleteunit', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'deleteunit'])->middleware('edit_general_setting');

    Route::get('/orderstatus', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'orderstatus'])->middleware('view_general_setting');
    Route::get('/addorderstatus', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'addorderstatus'])->middleware('edit_general_setting');
    Route::post('/addNewOrderStatus', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'addNewOrderStatus'])->middleware('edit_general_setting');
    Route::get('/editorderstatus/{id}', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'editorderstatus'])->middleware('edit_general_setting');
    Route::post('/updateOrderStatus', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'updateOrderStatus'])->middleware('edit_general_setting');
    Route::post('/deleteOrderStatus', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'deleteOrderStatus'])->middleware('edit_general_setting');


    Route::get('/facebooksettings', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'facebookSettings'])->middleware('view_general_setting');
    Route::get('/googlesettings', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'googleSettings'])->middleware('view_general_setting');
    //pushNotification
    Route::get('/pushnotification', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'pushNotification'])->middleware('view_general_setting');
    Route::get('/alertsetting', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'alertSetting'])->middleware('view_general_setting');
    Route::post('/updateAlertSetting', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'updateAlertSetting']);
    Route::get('/setting', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'setting'])->middleware('edit_general_setting');
    Route::post('/updateSetting', ['App\Http\Controllers\AdminControllers\SiteSettingController', 'updateSetting'])->middleware('edit_general_setting');

    //admin managements
    Route::get('/admins', ['App\Http\Controllers\AdminControllers\AdminController', 'admins'])->middleware('view_manage_admin');
    Route::get('/addadmins', ['App\Http\Controllers\AdminControllers\AdminController', 'addadmins'])->middleware('add_manage_admin');
    Route::post('/addnewadmin', ['App\Http\Controllers\AdminControllers\AdminController', 'addnewadmin'])->middleware('add_manage_admin');
    Route::get('/editadmin/{id}', ['App\Http\Controllers\AdminControllers\AdminController', 'editadmin'])->middleware('edit_manage_admin');
    Route::post('/updateadmin', ['App\Http\Controllers\AdminControllers\AdminController', 'updateadmin'])->middleware('edit_manage_admin');
    Route::post('/deleteadmin', ['App\Http\Controllers\AdminControllers\AdminController', 'deleteadmin'])->middleware('delete_manage_admin');

    //admin managements
    Route::get('/manageroles', ['App\Http\Controllers\AdminControllers\AdminController', 'manageroles'])->middleware('manage_role');
    Route::get('/addrole/{id}', ['App\Http\Controllers\AdminControllers\AdminController', 'addrole'])->middleware('manage_role');
    Route::post('/addnewroles', ['App\Http\Controllers\AdminControllers\AdminController', 'addnewroles'])->middleware('manage_role');
    Route::get('/addadmintype', ['App\Http\Controllers\AdminControllers\AdminController', 'addadmintype'])->middleware('add_admin_type');
    Route::post('/addnewtype', ['App\Http\Controllers\AdminControllers\AdminController', 'addnewtype'])->middleware('add_admin_type');
    Route::get('/editadmintype/{id}', ['App\Http\Controllers\AdminControllers\AdminController', 'editadmintype'])->middleware('edit_admin_type');
    Route::post('/updatetype', ['App\Http\Controllers\AdminControllers\AdminController', 'updatetype'])->middleware('edit_admin_type');
    Route::post('/deleteadmintype', ['App\Http\Controllers\AdminControllers\AdminController', 'deleteadmintype'])->middleware('delete_admin_type');


});

// ## Language Section
Route::prefix('admin/languages')->namespace('AdminControllers')->middleware('auth')->group(function () {
    Route::get('/display', ['App\Http\Controllers\AdminControllers\LanguageController', 'display'])->middleware('view_language');
    Route::get('/default', ['App\Http\Controllers\AdminControllers\LanguageController', 'default'])->middleware('edit_language');
    Route::get('/add', ['App\Http\Controllers\AdminControllers\LanguageController', 'add'])->middleware('add_language');
    Route::post('/add', ['App\Http\Controllers\AdminControllers\LanguageController', 'insert'])->middleware('add_language');

    Route::get('/edit/{id}', ['App\Http\Controllers\AdminControllers\LanguageController', 'edit'])->middleware('edit_language');
    Route::post('/update', ['App\Http\Controllers\AdminControllers\LanguageController', 'update'])->middleware('edit_language');
    Route::post('/delete', ['App\Http\Controllers\AdminControllers\LanguageController', 'delete'])->middleware('delete_language');
    Route::get('/filter', ['App\Http\Controllers\AdminControllers\LanguageController', 'filter'])->middleware('view_language');

});

// ## Media Section
Route::prefix('admin/media')->namespace('AdminControllers')->middleware('auth')->group(function () {

    Route::get('/display', ['App\Http\Controllers\AdminControllers\MediaController', 'display'])->middleware('view_media');
    Route::get('/add', ['App\Http\Controllers\AdminControllers\MediaController', 'add'])->middleware('add_media');
    Route::post('/updatemediasetting', ['App\Http\Controllers\AdminControllers\MediaController', 'updatemediasetting'])->middleware('edit_media');
    Route::post('/uploadimage', ['App\Http\Controllers\AdminControllers\MediaController', 'fileUpload'])->middleware('add_media');
    Route::get('/deleteimage/{id}', ['App\Http\Controllers\AdminControllers\MediaController', 'deleteimage'])->middleware('delete_media');
    Route::get('/detailimage/{id}', ['App\Http\Controllers\AdminControllers\MediaController', 'detailimage'])->middleware('view_media');
    Route::get('/refresh', ['App\Http\Controllers\AdminControllers\MediaController', 'refresh']);
});

// ## Manufactures
Route::prefix('admin/manufacturers')->namespace('AdminControllers')->middleware('auth')->group(function () {

    Route::get('/display', ['App\Http\Controllers\AdminControllers\ManufacturerController', 'display'])->middleware('view_manufacturer');
    Route::get('/add', ['App\Http\Controllers\AdminControllers\ManufacturerController','add'])->middleware('add_manufacturer');
    Route::post('/add', ['App\Http\Controllers\AdminControllers\ManufacturerController','insert'])->middleware('add_manufacturer');
    Route::get('/edit/{id}', ['App\Http\Controllers\AdminControllers\ManufacturerController','edit'])->middleware('edit_manufacturer');
    Route::post('/update', ['App\Http\Controllers\AdminControllers\ManufacturerController','update'])->middleware('edit_manufacturer');
    Route::post('/delete', ['App\Http\Controllers\AdminControllers\ManufacturerController','delete'])->middleware('delete_manufacturer');
    Route::get('/filter', ['App\Http\Controllers\AdminControllers\ManufacturerController','filter'])->middleware('view_manufacturer');
});

// ## Stores
Route::prefix('admin/stores')->namespace('AdminControllers')->middleware('auth')->group(function () {
    Route::get('/display', ['App\Http\Controllers\AdminControllers\StoreController', 'display'])->middleware('view_store');
    Route::get('/add', ['App\Http\Controllers\AdminControllers\StoreController','add'])->middleware('add_store');
    Route::post('/add', ['App\Http\Controllers\AdminControllers\StoreController','insert'])->middleware('add_store');
    Route::get('/edit/{id}', ['App\Http\Controllers\AdminControllers\StoreController','edit'])->middleware('edit_store');
    Route::post('/update', ['App\Http\Controllers\AdminControllers\StoreController','update'])->middleware('edit_store');
    Route::post('/delete', ['App\Http\Controllers\AdminControllers\StoreController','delete'])->middleware('delete_store');
    Route::get('/filter', ['App\Http\Controllers\AdminControllers\StoreController','filter'])->middleware('view_store');
});

// ## Manufactures
Route::prefix('admin/categories')->namespace('AdminControllers')->middleware('auth')->group(function () {
    Route::get('/display', ['App\Http\Controllers\AdminControllers\CategoriesController','display']);
    Route::get('/add', ['App\Http\Controllers\AdminControllers\CategoriesController','add']);
    Route::post('/add', ['App\Http\Controllers\AdminControllers\CategoriesController','insert']);
    Route::get('/edit/{id}', ['App\Http\Controllers\AdminControllers\CategoriesController','edit']);
    Route::post('/update', ['App\Http\Controllers\AdminControllers\CategoriesController','update']);
    Route::post('/delete', ['App\Http\Controllers\AdminControllers\CategoriesController','delete']);
    Route::get('/filter', ['App\Http\Controllers\AdminControllers\CategoriesController','filter']);
});

// Products
Route::prefix('admin/products')->namespace('AdminControllers')->middleware('auth')->group(function (){

    Route::get('/display',['App\Http\Controllers\AdminControllers\ProductController', 'display'])->middleware('view_product');
    Route::get('/add',['App\Http\Controllers\AdminControllers\ProductController','add'])->middleware('add_product');
    Route::post('/add',['App\Http\Controllers\AdminControllers\ProductController','insert'])->middleware('add_product');
    Route::get('/edit/{id}',['App\Http\Controllers\AdminControllers\ProductController','edit'])->middleware('edit_product');
    Route::post('/update',['App\Http\Controllers\AdminControllers\ProductController','update'])->middleware('edit_product');
    Route::post('/delete',['App\Http\Controllers\AdminControllers\ProductController','delete'])->middleware('delete_product');
    Route::get('/filter',['App\Http\Controllers\AdminControllers\ProductController','filter'])->middleware('view_product');

    Route::group(['prefix'=>'promotion'], function (){
        Route::get('/promotion_list',['App\Http\Controllers\AdminControllers\ProductController','promotion_list'])->middleware('view_product');
        Route::get('/new',['App\Http\Controllers\AdminControllers\ProductController','add_promotion'])->middleware('view_product');
        Route::post('/addnewpromotion',['App\Http\Controllers\AdminControllers\ProductController','addnewpromotion'])->middleware('add_product');

        Route::get('/edit/{id}',['App\Http\Controllers\AdminControllers\ProductController','promotion_edit'])->middleware('edit_product');
        Route::post('/update',['App\Http\Controllers\AdminControllers\ProductController','promotion_update'])->middleware('edit_product');

        Route::post('/promotion_delete',['App\Http\Controllers\AdminControllers\ProductController','promotion_delete'])->middleware('delete_product');
    });
    Route::group(['prefix'=>'inventory'], function (){
        Route::get('/display',['App\Http\Controllers\AdminControllers\ProductController','addinventoryfromsidebar'])->middleware('view_product');
        Route::get('/display/bulk',['App\Http\Controllers\AdminControllers\ProductController','addinventoryfromsidebarBulk'])->middleware('view_product');
        Route::get('/display2', function () {
            $title = array('pageTitle' => Lang::get("labels.ProductInventory"));
                 return view("admin.products.inventory.add2", $title);
            /*return view('welcome');*/
        })->middleware('view_product');
        Route::get('/ajax_min_max/{id}/',['App\Http\Controllers\AdminControllers\ProductController','ajax_min_max'])->middleware('view_product');
        Route::get('/ajax_min_max_bulk/{id}/',['App\Http\Controllers\AdminControllers\ProductController','ajax_min_max_bulk'])->middleware('view_product');
        Route::get('/ajax_attr/{id}/',['App\Http\Controllers\AdminControllers\ProductController','ajax_attr'])->middleware('view_product');
        Route::get('/get_promo_products_from/{id}/',['App\Http\Controllers\AdminControllers\ProductController','get_promo_products_from'])->middleware('view_product');
        Route::get('/get_promo_products_to/{id}/',['App\Http\Controllers\AdminControllers\ProductController','get_promo_products_to'])->middleware('view_product');

        Route::post('/addnewstock',['App\Http\Controllers\AdminControllers\ProductController','addnewstock'])->middleware('add_product');
        Route::post('/addnewstock/bulk',['App\Http\Controllers\AdminControllers\ProductController','addnewstockBulk'])->middleware('add_product');
        Route::post('/addminmax',['App\Http\Controllers\AdminControllers\ProductController','addminmax'])->middleware('add_product');
        Route::get('/addproductimages/{id}/',['App\Http\Controllers\AdminControllers\ProductController','addproductimages'])->middleware('add_product');
    });

    Route::group(['prefix'=>'images'], function (){
      Route::get('/display/{id}/',['App\Http\Controllers\AdminControllers\ProductController','displayProductImages'])->middleware('view_product');
      Route::get('/add/{id}/',['App\Http\Controllers\AdminControllers\ProductController','addProductImages'])->middleware('add_product');
      Route::post('/insertproductimage',['App\Http\Controllers\AdminControllers\ProductController','insertProductImages'])->middleware('add_product');
      Route::get('/editproductimage/{id}',['App\Http\Controllers\AdminControllers\ProductController','editProductImages'])->middleware('edit_product');
      Route::post('/updateproductimage',['App\Http\Controllers\AdminControllers\ProductController','updateproductimage'])->middleware('edit_product');
      Route::post('/deleteproductimagemodal',['App\Http\Controllers\AdminControllers\ProductController','deleteproductimagemodal'])->middleware('edit_product');
      Route::post('/deleteproductimage',['App\Http\Controllers\AdminControllers\ProductController','deleteproductimage'])->middleware('edit_product');
    });

    Route::group(['prefix'=>'attach/attribute'], function (){
      Route::get('/display/{id}',['App\Http\Controllers\AdminControllers\ProductController','addproductattribute'])->middleware('view_product');
      Route::group(['prefix'=>'/default'], function (){
        Route::post('/',['App\Http\Controllers\AdminControllers\ProductController','addnewdefaultattribute'])->middleware('view_product');
        Route::post('/edit',['App\Http\Controllers\AdminControllers\ProductController','editdefaultattribute'])->middleware('edit_product');
        Route::post('/update',['App\Http\Controllers\AdminControllers\ProductController','updatedefaultattribute'])->middleware('edit_product');
        Route::post('/deletedefaultattributemodal',['App\Http\Controllers\AdminControllers\ProductController','deletedefaultattributemodal'])->middleware('edit_product');
        Route::post('/delete',['App\Http\Controllers\AdminControllers\ProductController','deletedefaultattribute'])->middleware('edit_product');
        Route::group(['prefix'=>'/options'], function (){
            Route::post('/add',['App\Http\Controllers\AdminControllers\ProductController','showoptions'])->middleware('view_product');
            Route::post('/edit',['App\Http\Controllers\AdminControllers\ProductController','editoptionform'])->middleware('edit_product');
            Route::post('/update',['App\Http\Controllers\AdminControllers\ProductController','updateoption'])->middleware('edit_product');
            Route::post('/showdeletemodal',['App\Http\Controllers\AdminControllers\ProductController','showdeletemodal'])->middleware('edit_product');
            Route::post('/delete',['App\Http\Controllers\AdminControllers\ProductController','deleteoption'])->middleware('edit_product');
            Route::post('/getOptionsValue',['App\Http\Controllers\AdminControllers\ProductController','getOptionsValue'])->middleware('edit_product');
            Route::post('/currentstock',['App\Http\Controllers\AdminControllers\ProductController','currentstock'])->middleware('view_product');
        });
      });

    });
});


// Attributes
Route::prefix('admin/products/attributes')->namespace('AdminControllers')->middleware('auth')->group(function (){
    Route::get('/display',['App\Http\Controllers\AdminControllers\ProductAttributesController','display'])->middleware('view_product');
    Route::get('/add',['App\Http\Controllers\AdminControllers\ProductAttributesController','add'])->middleware('view_product');
    Route::post('/insert',['App\Http\Controllers\AdminControllers\ProductAttributesController','insert'])->middleware('view_product');
    Route::get('/edit/{id}',['App\Http\Controllers\AdminControllers\ProductAttributesController','edit'])->middleware('view_product');
    Route::post('/update',['App\Http\Controllers\AdminControllers\ProductAttributesController','update'])->middleware('view_product');
    Route::post('/delete',['App\Http\Controllers\AdminControllers\ProductAttributesController','delete'])->middleware('view_product');

    Route::prefix('options/values')->group(function (){
        Route::get('/display/{id}',['App\Http\Controllers\AdminControllers\ProductAttributesController','displayoptionsvalues'])->middleware('view_product');
        Route::post('/insert',['App\Http\Controllers\AdminControllers\ProductAttributesController','insertoptionsvalues'])->middleware('edit_product');
        Route::get('/edit/{id}',['App\Http\Controllers\AdminControllers\ProductAttributesController','editoptionsvalues'])->middleware('edit_product');
        Route::post('/update',['App\Http\Controllers\AdminControllers\ProductAttributesController','updateoptionsvalues'])->middleware('edit_product');
        Route::post('/delete',['App\Http\Controllers\AdminControllers\ProductAttributesController','deleteoptionsvalues'])->middleware('edit_product');
        Route::post('/addattributevalue',['App\Http\Controllers\AdminControllers\ProductAttributesController','addattributevalue'])->middleware('edit_product');
        Route::post('/updateattributevalue',['App\Http\Controllers\AdminControllers\ProductAttributesController','updateattributevalue'])->middleware('edit_product');
        Route::post('/checkattributeassociate',['App\Http\Controllers\AdminControllers\ProductAttributesController','checkattributeassociate'])->middleware('edit_product');
        Route::post('/checkvalueassociate',['App\Http\Controllers\AdminControllers\ProductAttributesController','checkvalueassociate'])->middleware('edit_product');
    });
});

// News
Route::prefix('admin/news')->namespace('AdminControllers')->middleware('auth')->group(function (){
    Route::get('/display',['App\Http\Controllers\AdminControllers\NewsController','display'])->middleware('view_news');
    Route::get('/add',['App\Http\Controllers\AdminControllers\NewsController','add'])->middleware('add_news');
    Route::post('/add',['App\Http\Controllers\AdminControllers\NewsController','insert'])->middleware('add_news');
    Route::get('/edit/{id}',['App\Http\Controllers\AdminControllers\NewsController','edit'])->middleware('edit_news');
    Route::post('/update',['App\Http\Controllers\AdminControllers\NewsController','update'])->middleware('edit_news');
    Route::post('/delete',['App\Http\Controllers\AdminControllers\NewsController','delete'])->middleware('delete_news');
    Route::get('/filter',['App\Http\Controllers\AdminControllers\NewsController','filter'])->middleware('view_news');
});


// News Category
Route::prefix('admin/newscategories')->namespace('AdminControllers')->middleware('auth')->group(function (){

    Route::get('/display',['App\Http\Controllers\AdminControllers\NewsCategoriesController','display'])->middleware('view_news');
    Route::get('/add',['App\Http\Controllers\AdminControllers\NewsCategoriesController','add'])->middleware('add_news');
    Route::post('/add',['App\Http\Controllers\AdminControllers\NewsCategoriesController','insert'])->middleware('add_news');
    Route::get('/edit/{id}',['App\Http\Controllers\AdminControllers\NewsCategoriesController','edit'])->middleware('edit_news');
    Route::post('/update',['App\Http\Controllers\AdminControllers\NewsCategoriesController','update'])->middleware('edit_news');
    Route::post('/delete',['App\Http\Controllers\AdminControllers\NewsCategoriesController','delete'])->middleware('delete_news');
    Route::get('/filter',['App\Http\Controllers\AdminControllers\NewsCategoriesController','filter'])->middleware('view_news');

});

// Customers
Route::prefix('admin/customers')->namespace('AdminControllers')->middleware('auth')->group(function (){
    Route::get('/display',['App\Http\Controllers\AdminControllers\CustomersController','display'])->middleware('view_customer');
    Route::get('/add',['App\Http\Controllers\AdminControllers\CustomersController','add'])->middleware('add_customer');
    Route::post('/add',['App\Http\Controllers\AdminControllers\CustomersController','insert'])->middleware('add_customer');
    Route::get('/edit/{id}',['App\Http\Controllers\AdminControllers\CustomersController','edit'])->middleware('edit_customer');
    Route::post('/update',['App\Http\Controllers\AdminControllers\CustomersController','update'])->middleware('edit_customer');
    Route::post('/delete',['App\Http\Controllers\AdminControllers\CustomersController','delete'])->middleware('delete_customer');
    Route::get('/filter',['App\Http\Controllers\AdminControllers\CustomersController','filter'])->middleware('view_customer');
    //add adddresses against customers
    Route::get('/address/display/{id}/',['App\Http\Controllers\AdminControllers\CustomersController','diplayaddress'])->middleware('add_customer');
    Route::post('/addcustomeraddress',['App\Http\Controllers\AdminControllers\CustomersController','addcustomeraddress'])->middleware('add_customer');
    Route::post('/editaddress',['App\Http\Controllers\AdminControllers\CustomersController','editaddress'])->middleware('edit_customer');
    Route::post('/updateaddress',['App\Http\Controllers\AdminControllers\CustomersController','updateaddress'])->middleware('edit_customer');
    Route::post('/deleteAddress',['App\Http\Controllers\AdminControllers\CustomersController','deleteAddress'])->middleware('edit_customer');
});

// Countries
Route::prefix('admin/countries')->namespace('AdminControllers')->middleware('auth')->group(function (){
    Route::get('/filter', ['App\Http\Controllers\AdminControllers\CountriesController','filter'])->middleware('view_tax');
    Route::get('/display', ['App\Http\Controllers\AdminControllers\CountriesController','index'])->middleware('view_tax');
    Route::get('/add', ['App\Http\Controllers\AdminControllers\CountriesController','add'])->middleware('add_tax');
    Route::post('/add', ['App\Http\Controllers\AdminControllers\CountriesController','insert'])->middleware('add_tax');
    Route::get('/edit/{id}', ['App\Http\Controllers\AdminControllers\CountriesController','edit'])->middleware('edit_tax');
    Route::post('/update', ['App\Http\Controllers\AdminControllers\CountriesController','update'])->middleware('edit_tax');
    Route::post('/delete', ['App\Http\Controllers\AdminControllers\CountriesController','delete'])->middleware('delete_tax');
});

// Zones
Route::prefix('admin/zones')->namespace('AdminControllers')->middleware('auth')->group(function (){
    Route::get('/display', ['App\Http\Controllers\AdminControllers\ZonesController','index'])->middleware('view_tax');
    Route::get('/filter', ['App\Http\Controllers\AdminControllers\ZonesController','filter'])->middleware('view_tax');
    Route::get('/add', ['App\Http\Controllers\AdminControllers\ZonesController','add'])->middleware('add_tax');
    Route::post('/add', ['App\Http\Controllers\AdminControllers\ZonesController','insert'])->middleware('add_tax');
    Route::get('/edit/{id}', ['App\Http\Controllers\AdminControllers\ZonesController','edit'])->middleware('edit_tax');
    Route::post('/update', ['App\Http\Controllers\AdminControllers\ZonesController','update'])->middleware('edit_tax');
    Route::post('/delete', ['App\Http\Controllers\AdminControllers\ZonesController','delete'])->middleware('delete_tax');
});

// Tax
Route::prefix('admin/tax')->namespace('AdminControllers')->middleware('auth')->group(function (){
    Route::prefix('/taxclass')->group(function (){
        Route::get('/filter', ['App\Http\Controllers\AdminControllers\TaxController','filtertaxclass'])->middleware('view_tax');
        Route::get('/display', ['App\Http\Controllers\AdminControllers\TaxController','taxindex'])->middleware('view_tax');
        Route::get('/add', ['App\Http\Controllers\AdminControllers\TaxController','addtaxclass'])->middleware('add_tax');
        Route::post('/add', ['App\Http\Controllers\AdminControllers\TaxController','inserttaxclass'])->middleware('add_tax');
        Route::get('/edit/{id}', ['App\Http\Controllers\AdminControllers\TaxController','edittaxclass'])->middleware('edit_tax');
        Route::post('/update', ['App\Http\Controllers\AdminControllers\TaxController','updatetaxclass'])->middleware('edit_tax');
        Route::post('/delete', ['App\Http\Controllers\AdminControllers\TaxController','deletetaxclass'])->middleware('delete_tax');
    });
    Route::prefix('/taxrates')->group(function (){
        Route::get('/display', ['App\Http\Controllers\AdminControllers\TaxController','displaytaxrates'])->middleware('view_tax');
        Route::get('/filter', ['App\Http\Controllers\AdminControllers\TaxController','filtertaxrates'])->middleware('view_tax');
        Route::get('/add', ['App\Http\Controllers\AdminControllers\TaxController','addtaxrate'])->middleware('add_tax');
        Route::post('/add', ['App\Http\Controllers\AdminControllers\TaxController','inserttaxrate'])->middleware('add_tax');
        Route::get('/edit/{id}', ['App\Http\Controllers\AdminControllers\TaxController','edittaxrate'])->middleware('edit_tax');
        Route::post('/update', ['App\Http\Controllers\AdminControllers\TaxController','updatetaxrate'])->middleware('edit_tax');
        Route::post('/delete', ['App\Http\Controllers\AdminControllers\TaxController','deletetaxrate'])->middleware('delete_tax');
    });
});

// Coupons
Route::prefix('admin/coupons')->namespace('AdminControllers')->middleware('auth')->group(function (){
    Route::get('/display', ['App\Http\Controllers\AdminControllers\CouponsController','display'])->middleware('view_coupon');
    Route::get('/add', ['App\Http\Controllers\AdminControllers\CouponsController','add'])->middleware('add_coupon');
    Route::post('/insert', ['App\Http\Controllers\AdminControllers\CouponsController','insert'])->middleware('add_coupon');
    Route::get('/edit/{id}', ['App\Http\Controllers\AdminControllers\CouponsController','edit'])->middleware('edit_coupon');
    Route::post('/update', ['App\Http\Controllers\AdminControllers\CouponsController','update'])->middleware('edit_coupon');
    Route::post('/delete', ['App\Http\Controllers\AdminControllers\CouponsController','delete'])->middleware('delete_coupon');
    Route::get('/filter', ['App\Http\Controllers\AdminControllers\CouponsController','filter'])->middleware('view_coupon');
});

// Notificatios
Route::prefix('admin/devices')->namespace('AdminControllers')->middleware('auth')->group(function (){
    Route::get('/display', ['App\Http\Controllers\AdminControllers\NotificationController','devices'])->middleware('view_notification');
    Route::get('/viewdevices/{id}', ['App\Http\Controllers\AdminControllers\NotificationController','viewdevices'])->middleware('view_notification');
    Route::post('/notifyUser', ['App\Http\Controllers\AdminControllers\NotificationController','notifyUser'])->middleware('edit_notification');
    Route::get('/notifications', ['App\Http\Controllers\AdminControllers\NotificationController','notifications'])->middleware('view_notification');
    Route::post('/sendNotifications', ['App\Http\Controllers\AdminControllers\NotificationController','sendNotifications'])->middleware('edit_notification');
    Route::post('/customerNotification', ['App\Http\Controllers\AdminControllers\NotificationController','customerNotification'])->middleware('view_notification');
    Route::post('/singleUserNotification', ['App\Http\Controllers\AdminControllers\NotificationController','singleUserNotification'])->middleware('edit_notification');
    Route::post('/deletedevice', ['App\Http\Controllers\AdminControllers\NotificationController','deletedevice'])->middleware('view_notification');
});

// Orders
Route::prefix('admin/orders')->namespace('AdminControllers')->middleware('auth')->group(function (){
    Route::get('/display', ['App\Http\Controllers\AdminControllers\OrdersController','display'])->middleware('view_order');
    Route::get('/unpaidOrder', ['App\Http\Controllers\AdminControllers\OrdersController', 'unpaidOrder'])->middleware('view_order');
    Route::get('/vieworder/{id}', ['App\Http\Controllers\AdminControllers\OrdersController','vieworder'])->middleware('view_order');
    Route::post('/updateOrder', ['App\Http\Controllers\AdminControllers\OrdersController','updateOrder'])->middleware('edit_order');
    Route::post('/deleteOrder', ['App\Http\Controllers\AdminControllers\OrdersController','deleteOrder'])->middleware('edit_order');
    Route::get('/invoiceprint/{id}', ['App\Http\Controllers\AdminControllers\OrdersController','invoiceprint'])->middleware('view_order');
    Route::get('/orderstatus', ['App\Http\Controllers\AdminControllers\SiteSettingController','orderstatus'])->middleware('view_order');
    Route::get('/addorderstatus', ['App\Http\Controllers\AdminControllers\SiteSettingController','addorderstatus'])->middleware('edit_order');
    Route::post('/addNewOrderStatus', ['App\Http\Controllers\AdminControllers\SiteSettingController','addNewOrderStatus'])->middleware('edit_order');
    Route::get('/editorderstatus/{id}', ['App\Http\Controllers\AdminControllers\SiteSettingController','editorderstatus'])->middleware('edit_order');
    Route::post('/updateOrderStatus', ['App\Http\Controllers\AdminControllers\SiteSettingController','updateOrderStatus'])->middleware('edit_order');
    Route::post('/deleteOrderStatus', ['App\Http\Controllers\AdminControllers\SiteSettingController','deleteOrderStatus'])->middleware('edit_order');
});

// Shipping Methods
Route::prefix('admin/shippingmethods')->namespace('AdminControllers')->middleware('auth')->group(function (){
  Route::get('/display', ['App\Http\Controllers\AdminControllers\ShippingMethodsController','display'])->middleware('view_shipping');
  Route::get('/upsShipping', ['App\Http\Controllers\AdminControllers\ShippingMethodsController','upsShipping'])->middleware('view_shipping');
  Route::post('/updateupsshipping', ['App\Http\Controllers\AdminControllers\ShippingMethodsController','updateupsshipping'])->middleware('edit_shipping');
  Route::get('/flateRate', ['App\Http\Controllers\AdminControllers\ShippingMethodsController','flateRate'])->middleware('view_shipping');
  Route::post('/updateflaterate', ['App\Http\Controllers\AdminControllers\ShippingMethodsController','updateflaterate'])->middleware('edit_shipping');
  Route::post('/defaultShippingMethod', ['App\Http\Controllers\AdminControllers\ShippingMethodsController','defaultShippingMethod'])->middleware('edit_shipping');
  Route::get('/detail/{table_name}', ['App\Http\Controllers\AdminControllers\ShippingMethodsController','detail'])->middleware('edit_shipping');
  Route::post('/update', ['App\Http\Controllers\AdminControllers\ShippingMethodsController','update'])->middleware('edit_shipping');

  Route::get('/shppingbyweight', ['App\Http\Controllers\AdminControllers\ShippingByWeightController','shppingbyweight'])->middleware('view_shipping');
  Route::post('/updateShppingWeightPrice', ['App\Http\Controllers\AdminControllers\ShippingByWeightController','updateShppingWeightPrice'])->middleware('edit_shipping');
});

// Payment Methods
Route::prefix('admin/paymentmethods')->namespace('AdminControllers')->middleware('auth')->group(function (){
	Route::get('/index', ['App\Http\Controllers\AdminControllers\PaymentMethodsController','index'])->middleware('view_payment');
	Route::get('/display/{id}', ['App\Http\Controllers\AdminControllers\PaymentMethodsController','display'])->middleware('view_payment');
	Route::post('/update', ['App\Http\Controllers\AdminControllers\PaymentMethodsController','update'])->middleware('edit_payment');
	Route::post('/active', ['App\Http\Controllers\AdminControllers\PaymentMethodsController','active'])->middleware('edit_payment');
});

// Currency
Route::prefix('admin/currencies')->namespace('AdminControllers')->middleware('auth')->group(function (){
    Route::get('/display', ['App\Http\Controllers\AdminControllers\CurrencyController', 'display']);
    Route::get('/add', ['App\Http\Controllers\AdminControllers\CurrencyController', 'add']);
    Route::post('/add', ['App\Http\Controllers\AdminControllers\CurrencyController', 'insert']);
    Route::get('/edit/{id}', ['App\Http\Controllers\AdminControllers\CurrencyController', 'edit']);
      Route::get('/edit/warning/{id}', ['App\Http\Controllers\AdminControllers\CurrencyController', 'warningedit']);
    Route::post('/update', ['App\Http\Controllers\AdminControllers\CurrencyController', 'update']);
    Route::post('/delete', ['App\Http\Controllers\AdminControllers\CurrencyController', 'delete']);
});

// Managments
Route::prefix('admin/managements')->namespace('AdminControllers')->middleware('auth')->group(function (){
    Route::get('/merge', ['App\Http\Controllers\AdminControllers\ManagementsController', 'merge'])->middleware('edit_management');
    Route::get('/backup', ['App\Http\Controllers\AdminControllers\ManagementsController', 'backup'])->middleware('edit_management');
    Route::post('/take_backup', ['App\Http\Controllers\AdminControllers\ManagementsController', 'take_backup'])->middleware('edit_management');
    Route::get('/import', ['App\Http\Controllers\AdminControllers\ManagementsController', 'import'])->middleware('edit_management');
    Route::post('/importdata', ['App\Http\Controllers\AdminControllers\ManagementsController', 'importdata'])->middleware('edit_management');
    Route::post('/mergecontent', ['App\Http\Controllers\AdminControllers\ManagementsController', 'mergecontent'])->middleware('edit_management');
    Route::get('/updater', ['App\Http\Controllers\AdminControllers\ManagementsController', 'updater'])->middleware('edit_management');
    Route::post('/checkpassword', ['App\Http\Controllers\AdminControllers\ManagementsController', 'checkpassword'])->middleware('edit_management');
    Route::post('/updatercontent', ['App\Http\Controllers\AdminControllers\ManagementsController', 'updatercontent'])->middleware('edit_management');
});
