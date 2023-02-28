<?php

namespace App\Http\Controllers\API;

use App\Models\Core\Categories;
use App\Models\Core\Manufacturers;
use App\Models\Core\Stores;
use App\Models\Web\Products;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Web\Index;
use Carbon;
use Illuminate\Http\Request;

class HomeController extends BaseController {

    public function __construct(
        Products $products
    )
    {
        $this->products = $products;
    }
    function index() {
        $main_categories        = [];
        $main_slider            = [];
        $category_banners       = [];
        $all_categories         = [];
        $all_languages          = [];
        $brands                 = [];
        $feature_products       = [
            "status"        => "enable",
            "title"         => "Feature Products",
            "screen_order"  => 0,
            "products"      => []
        ];
        $recent_products        = [
            "status"        => "enable",
            "title"         => "New Products",
            "screen_order"  => 1,
            "products"      => []
        ];
        $special_deal_products  = [
            "status"        => "enable",
            "title"         => "Special Deal",
            "screen_order"  => 2,
            "products"      => []
        ];
        $popular_products       = [
            "status"        => "enable",
            "title"         => "Best Seller",
            "screen_order"  => 3,
            "products"      => []
        ];
        $top_rated_products     = [
            "status"        => "enable",
            "title"         => "Top Rated Products",
            "screen_order"  => 4,
            "products"      => []
        ];

        $language_id     =   '1';
        $currentDate     = Carbon\Carbon::now();
        $currentDate     = $currentDate->toDateTimeString();

        $setting         = DB::table('settings')->get();
        $index           = new Index();
        $data            = $index->finalTheme();
        $carousel_id     = $data->carousel;

        $brands_data =  Manufacturers::select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
                        ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
                        ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
                        ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
                        ->where('manufacturers_info.languages_id', $language_id)
                        ->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL')
                        ->get();

        foreach ($brands_data as $brands_datum) {
            $brands[] = [
                'id'        => $brands_datum['id'],
                'name'      => $brands_datum['name'],
                'image'     => (isset($brands_datum['path']) and !empty($brands_datum['path'])) ? asset($brands_datum['path']) : '',
                'url'       => $brands_datum['url']
            ];
        }

        $categories      = DB::table('categories')
                            ->LeftJoin('categories_description', 'categories_description.categories_id', '=', 'categories.categories_id')
                            ->leftJoin('image_categories','categories.categories_image','=','image_categories.image_id')
                            ->select('categories.categories_id as id',
                                'categories.categories_image as image',
                                'categories.sort_order as order',
                                'categories.categories_slug as slug',
                                'categories.parent_id',
                                'categories_description.categories_name as name',
                                'image_categories.path as path',
                                'image_categories.image_id as image_id'
                            )
                            ->where('categories_description.language_id','=', $language_id)
                            ->where('parent_id','0')
                            ->groupBy('categories.categories_id');

        if($categories->count() > 0) {
            foreach($categories->get() as $key => $category){
                $main_categories[] = [
                    "main_cat_id"       => $category->id,
                    "main_cat_name"     => $category->name,
                    "main_cat_image"    => asset($category->path)
                ];
                $category_banners[] = [
                    "cat_banners_image_id"  => $category->image_id,
                    "cat_banners_image_url" => asset($category->path),
                    "cat_banners_cat_id"    => $category->id,
                    "cat_banners_title"     => $category->name,
                ];
            }
        }

        $slides = $index->slidesByCarousel($currentDate,$carousel_id);

        if($slides->count() > 0) {
            foreach($slides as $key => $slide){
                $slider_product_id = 0;
                $slider_cat_id = 0;
                if($slide->type == "product" and !empty($slide->url)) {
                    $slider_product_info = DB::table('products')->select('products_id')
                                            ->where('products_slug','=', $slide->url)
                                            ->first();
                    if($slider_product_info) {
                        $slider_product_id = $slider_product_info->products_id;
                    }
                }else if($slide->type == "category" and !empty($slide->url)) {
                    $slider_cat_info = DB::table('categories')->select('categories_id')
                        ->where('categories_slug','=', $slide->url)
                        ->first();
                    if($slider_cat_info) {
                        $slider_cat_id = $slider_cat_info->categories_id;
                    }
                }
                $main_slider[] = [
                    "upload_image_id"       => $slide->id,
                    "type"                  => $slide->type,
                    "slider_cat_id"         => ($slide->type == 'category') ? $slider_cat_id : 0,
                    "slider_product_id"     => ($slide->type == 'product') ? $slider_product_id : 0,
                    "upload_image_url"      => asset($slide->path)
                ];
            }
        }

        $feature_products['products']       = $this->getProductsListings('featured', $language_id);
        $recent_products['products']        = $this->getProductsListings('recent_products', $language_id);
        $special_deal_products['products']  = $this->getProductsListings('special_deal_products', $language_id);
        $popular_products['products']       = $this->getProductsListings('popular_products', $language_id);
        $top_rated_products['products']     = $this->getProductsListings('top_rated_products', $language_id);

        $categories = Categories::select('categories.categories_id as id', 'categories.categories_image as image',
                        'categories.categories_icon as icon',  'categories.created_at as date_added',
                        'categories.updated_at as last_modified', 'categories_description.categories_name as name',
                        'categories_description.language_id','categoryTable.path as imgpath','iconTable.path as iconpath',
                        'categories.categories_status  as categories_status',
                        'categories.categories_slug as slug',
                        'categories.parent_id')
            ->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
            ->leftJoin('images','images.id', '=', 'categories.categories_image')
            ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'categories.categories_image')
            ->leftJoin('image_categories as iconTable','iconTable.image_id', '=', 'categories.categories_icon')
            ->where('categories_description.language_id', $language_id)
            /*->where(function($query) {
                $query->where('categoryTable.image_type', '=',  'THUMBNAIL')
                    ->where('categoryTable.image_type','!=',   'THUMBNAIL')
                    ->orWhere('categoryTable.image_type','=',   'ACTUAL')
                    ->where('iconTable.image_type', '=',  'THUMBNAIL')
                    ->where('iconTable.image_type','!=',   'THUMBNAIL')
                    ->orWhere('iconTable.image_type','=',   'ACTUAL');
            })*/
            ->groupby('categories.categories_id');

        if($categories->count() > 0) {
            foreach($categories->get() as $key => $category){
                $all_categories[] = [
                    "description"   => '',
                    "id"            => $category->id,
                    "image"         => ['src' => asset($category->imgpath)],
                    'name'          => $category->name,
                    'parent'        => $category->parent_id,
                    'slug'          => $category->slug,
                ];
            }
        }

        $languages = \App\Models\Core\Languages::all();

        foreach ($languages as $language) {
           $all_languages[] = [
                "code"             => $language->code,
                "id"              => $language->languages_id,
                "native_name"     => strtoupper($language->code),
                "active"          => "1",
                "default_locale"  => $language->code,
                "translated_name" => strtoupper($language->code),
                "language_code"   => $language->code,
                "disp_language"   => strtoupper($language->code),
                "site_language"   => $language->code,
                "is_rtl"          => ($language->direction == 'rtl') ? true : false
            ];
        }

        $response = [
            'app_logo_light'    => asset($setting[15]->value),
            'app_logo'          => asset($setting[15]->value),
            'main_category'     => $main_categories,
            'brands'            => $brands,
            "main_slider"       => $main_slider,
            "category_banners"  => $category_banners,
            "products_carousel" => [
                "feature_products"      => $feature_products,
                "recent_products"       => $recent_products,
                "special_deal_products" => $special_deal_products,
                "popular_products"      => $popular_products,
                "top_rated_products"    => $top_rated_products
            ],
            "banner_ad"                  => [],
            "feature_box_status"         => 'enable',
            "feature_box_heading"        => '',
            "feature_box"                => ['feature_title' => '', 'feature_content' => ''],
            "static_page"                => ['about_us' => '8', 'terms_of_use' => '6', 'privacy_policy' => '5'],
            "info_pages"                 => ['info_pages_page_id' => ''],
            "pgs_app_social_links"       => [
              'facebook'    => $setting['50']->value,
              'twitter'     => $setting['52']->value,
              'linkedin'    => $setting['53']->value,
              'google_plus' => $setting['51']->value,
              'pinterest'   => '#',
              'instagram'   => '#',
            ],
            "pgs_app_contact_info"       => [
                'address_line_1'            => $setting['4']->value,
                'address_line_2'            => implode(',', array_filter([$setting['5']->value, $setting['6']->value, $setting['7']->value,$setting['8']->value], 'strlen')),
                'email'                     => $setting['3']->value,
                'phone'                     => $setting['11']->value,
                'whatsapp_floating_button'  => 'enabled',
                'whatsapp_no'               => $setting['11']->value,
            ],
            "pgs_api_is_wpml"               => 'disable',
            "pgs_api_filters"               => [
                'pgs_price'             => 'enable',
                'pa_brand'              => 'enable',
                'pa_gender'             => 'enable',
                'pa_size'               => 'enable',
                'pgs_average_rating'    => 'enable',
            ],
            "products_view_orders"          => [
                ['name' => 'feature_products',],
                ['name' => 'recent_products',],
                ['name' => 'special_deal_products',],
                ['name' => 'popular_products',],
                ['name' => 'top_rated_products',],
            ],
            "all_categories"                => $all_categories,
            "is_wishlist_active"            => false,
            "is_currency_switcher_active"   => false,
            "is_order_tracking_active"      => false,
            "is_reward_points_active"       => false,
            "is_guest_checkout_active"      => true,
            "is_wpml_active"                => true,
            "price_formate_options"         => [
                "decimal_separator"     => ".",
                "thousand_separator"    => ",",
                "decimals"              => 0,
                "currency_pos"          => "right_space",
                "currency_symbol"       => trim($setting['19']->value),
                "currency_code"         => trim($setting['19']->value),
            ],
            "ios_app_url"                   => $setting['110']->value,
            "site_language"                 => 'en-US',
            "wpml_languages"            => $all_languages,
            "checkout_redirect_url"     => [],
            'app_color'                 => [
                'header_color'      => '',
                'primary_color'     => '',
                'secondary_color'   => '',
            ],
            "wc_tax_enabled"                => true,
            "woocommerce_tax_display_shop"  => "excl",
            "woocommerce_tax_display_cart"  => "excl",
            "is_rtl"                        => false,
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    function brands(Request $request) {
        $language_id    = 1;
        $brands         = [];

        $brands_data =  Manufacturers::select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
                        ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
                        ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
                        ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
                        ->where('manufacturers_info.languages_id', $language_id)
                        ->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL')
                        ->get();

        foreach ($brands_data as $brands_datum) {
            $brands[] = [
                'id'        => $brands_datum['id'],
                'name'      => $brands_datum['name'],
                'image'     => (isset($brands_datum['path']) and !empty($brands_datum['path'])) ? asset($brands_datum['path']) : '',
                'url'       => $brands_datum['url']
            ];
        }

        return response()->json($brands, 200, [], JSON_PRETTY_PRINT);
    }

    function stores(Request $request) {
        $language_id    = 1;
        $stores         = [];

        $stores_data =  Stores::select('stores.stores_id as id', 'stores.store_name as name', 'stores.store_email as email', 'stores.store_phone as phone', 'stores.store_address as address','stores.status as status','stores.store_lat','stores.store_lng')
                        ->leftJoin('stores_info','stores_info.stores_id', '=', 'stores.stores_id')
                        ->where('stores_info.languages_id', $language_id)
                        ->where('stores.status', 1)
                        ->get();

        foreach ($stores_data as $stores_datum) {
            $stores[] = [
                'id'        => $stores_datum['id'],
                'name'      => $stores_datum['name'],
                'email'     => $stores_datum['email'],
                'phone'     => $stores_datum['phone'],
                'address'   => $stores_datum['address'],
                'store_lat'   => $stores_datum['store_lat'] ?: '',
                'store_lng'   => $stores_datum['store_lng'] ?: ''
            ];
        }

        return response()->json($stores, 200, [], JSON_PRETTY_PRINT);
    }

    function news_tickers() {
        $news = [
            '10 reasons to shop at our stores.',
            'Buy from our LIMITED-TIME offers.',
            'Buy one and get two free for limited time and limited products.'
        ];
        return response()->json($news, 200, [], JSON_PRETTY_PRINT);
    }    

    public function getProductsListings($type, $language_id) {
        if($type == "featured") {
            $data = array('page_number'=>'0', 'type'=>'is_feature', 'limit'=>5 , 'min_price' => '', 'max_price' => '');
        }else if($type == "recent_products") {
            $data = array('page_number'=>'0', 'type'=>'', 'limit'=>5 , 'min_price' => '', 'max_price' => '');
        }else if($type == "special_deal_products") {
            $data = array('page_number'=>'0', 'type'=>'special', 'limit'=>5 , 'min_price' => '', 'max_price' => '');
        }else if($type == "popular_products") {
            $data = array('page_number'=>'0', 'type'=>'topseller', 'limit'=>5 , 'min_price' => '', 'max_price' => '');
        }else if($type == "top_rated_products") {
            $data = array('page_number'=>'0', 'type'=>'mostliked', 'limit'=>5 , 'min_price' => '', 'max_price' => '');
        }
        $products = $this->products($data, $language_id);

        return $products['product_data'] ?: [];
    }

    private function products($data, $language_id){
        if(empty($data['page_number']) or $data['page_number'] == 0 ){
            $skip								= $data['page_number'].'0';
        }else{
            $skip								= $data['limit']*$data['page_number'];
        }

        $min_price	 							= $data['min_price'];
        $max_price	 							= $data['max_price'];
        $take									= $data['limit'];
        $currentDate 							= time();
        $type									= $data['type'];
        $customer_id                            = 0;

        if($type == "atoz"){
            $sortby								= "products_name";
            $order								= "ASC";
        }elseif($type == "ztoa"){
            $sortby								= "products_name";
            $order								= "DESC";
        }elseif($type == "hightolow"){
            $sortby								= "products_price";
            $order								= "DESC";
        }elseif($type == "lowtohigh"){
            $sortby								= "products_price";
            $order								= "ASC";
        }elseif($type == "topseller"){
            $sortby								= "products_ordered";
            $order								= "DESC";
        }elseif($type == "mostliked"){
            $sortby								= "products_liked";
            $order								= "DESC";
        }elseif($type == "special"){
            $sortby                             = "specials.products_id";
            $order                              = "desc";
        }elseif($type == "flashsale"){
            $sortby                             = "flash_sale.flash_start_date";
            $order                              = "asc";
        }else{
            $sortby                             = "products.products_id";
            $order                              = "desc";
        }

        $filterProducts = array();
        $eliminateRecord = array();

        $categories = DB::table('products')
            ->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
            ->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
            ->leftJoin('products_description','products_description.products_id','=','products.products_id')
            ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id');

        if(!empty($data['categories_id'])){
            $categories->LeftJoin('products_to_categories', 'products.products_id', '=', 'products_to_categories.products_id')
                ->leftJoin('categories','categories.categories_id','=','products_to_categories.categories_id')
                ->LeftJoin('categories_description','categories_description.categories_id','=','products_to_categories.categories_id');
        }

        if(!empty($data['filters']) and empty($data['search'])){
            $categories->leftJoin('products_attributes','products_attributes.products_id','=','products.products_id');
        }

        if(!empty($data['search'])){
            $categories->leftJoin('products_attributes','products_attributes.products_id','=','products.products_id')
                ->leftJoin('products_options','products_options.products_options_id','=','products_attributes.options_id')
                ->leftJoin('products_options_values','products_options_values.products_options_values_id','=','products_attributes.options_values_id');
        }
        //wishlist customer id
        if($type == "wishlist"){
            $categories->LeftJoin('liked_products', 'liked_products.liked_products_id', '=', 'products.products_id')
                ->select('products.*','image_categories.path as image_path', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url');

        }
        //parameter special
        elseif($type == "special"){
            $categories->LeftJoin('specials', 'specials.products_id', '=', 'products.products_id')
                ->select('products.*','image_categories.path as image_path', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price', 'specials.specials_new_products_price as discount_price');
        }
        elseif($type == "flashsale"){
            //flash sale
            $categories->LeftJoin('flash_sale', 'flash_sale.products_id', '=', 'products.products_id')
                ->select(DB::raw(time().' as server_time'),'products.*','image_categories.path as image_path', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url','flash_sale.flash_start_date', 'flash_sale.flash_expires_date', 'flash_sale.flash_sale_products_price as flash_price');

        }elseif($type == "compare"){
            //flash sale
            $categories->LeftJoin('flash_sale', 'flash_sale.products_id', '=', 'products.products_id')
                ->select(DB::raw(time().' as server_time'),'products.*','image_categories.path as image_path', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url','flash_sale.flash_start_date', 'flash_sale.flash_expires_date', 'flash_sale.flash_sale_products_price as discount_price');

        }
        else{
            $categories->LeftJoin('specials', function ($join) use ($currentDate) {
                $join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
            })->select('products.*','image_categories.path as image_path','products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price');
        }

        if($type == "special"){ //deals special products
            $categories->where('specials.status','=', '1')->where('expires_date','>',  $currentDate);
        }

        if($type == "flashsale"){ //flashsale
            $categories->where('flash_sale.flash_status','=', '1')->where('flash_expires_date','>',  $currentDate);
        }elseif($type != "compare"){
            $categories->whereNotIn('products.products_id',function($query) use ($currentDate) {
                $query->select('flash_sale.products_id')->from('flash_sale')->where('flash_sale.flash_status','=', '1');
            });
        }

        //get single products
        if(!empty($data['products_id']) && $data['products_id']!=""){
            $categories->where('products.products_id','=', $data['products_id']);
        }

        //for min and maximum price
        if(!empty($max_price)){
            $categories->whereBetween('products.products_price', [$min_price, $max_price]);
        }

        if(!empty($data['search'])){

            $searchValue = $data['search'];
            $categories->where('products_options.products_options_name', 'LIKE', '%'.$searchValue.'%')->where('products_status','=',1);

            if(!empty($data['categories_id'])){
                $categories->where('products_to_categories.categories_id','=', $data['categories_id']);
            }

            if(!empty($data['filters'])){
                $temp_key = 0;
                if(!empty($data['filters']['filter_attribute'])){
                    foreach($data['filters']['filter_attribute']['option_values'] as $option_id_temp){
                        if($temp_key == 0){
                            $categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);
                            if(count($data['filters']['filter_attribute']['options'])>1){
                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
                            }
                        }else{
                            $categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);

                            if(count($data['filters']['filter_attribute']['options'])>1){
                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
                            }
                        }
                        $temp_key++;
                    }
                }
            }
            if(!empty($max_price)){
                $categories->whereBetween('products.products_price', [$min_price, $max_price]);
            }

            $categories->orWhere('products_options_values.products_options_values_name', 'LIKE', '%'.$searchValue.'%')->where('products_status','=',1);
            if(!empty($data['categories_id'])){
                $categories->where('products_to_categories.categories_id','=', $data['categories_id']);
            }

            if(!empty($data['filters'])){
                $temp_key = 0;
                if(!empty($data['filters']['filter_attribute'])){
                    foreach($data['filters']['filter_attribute']['option_values'] as $option_id_temp){

                        if($temp_key == 0){

                            $categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);
                            if(count($data['filters']['filter_attribute']['options'])>1){

                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
                            }

                        }else{
                            $categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);

                            if(count($data['filters']['filter_attribute']['options'])>1){
                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
                            }

                        }
                        $temp_key++;
                    }

                }
            }

            if(!empty($max_price)){
                $categories->whereBetween('products.products_price', [$min_price, $max_price]);
            }

            $categories->orWhere('products_name', 'LIKE', '%'.$searchValue.'%')->where('products_status','=',1);

            if(!empty($data['categories_id'])){
                $categories->where('products_to_categories.categories_id','=', $data['categories_id']);
            }

            if(!empty($data['filters'])){
                $temp_key = 0;
                if(!empty($data['filters']['filter_attribute'])){
                    foreach($data['filters']['filter_attribute']['option_values'] as $option_id_temp){

                        if($temp_key == 0){

                            $categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);
                            if(count($data['filters']['filter_attribute']['options'])>1){

                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
                            }

                        }else{
                            $categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);

                            if(count($data['filters']['filter_attribute']['options'])>1){
                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
                            }

                        }
                        $temp_key++;
                    }

                }
            }

            if(!empty($max_price)){
                $categories->whereBetween('products.products_price', [$min_price, $max_price]);
            }

            $categories->orWhere('products_model', 'LIKE', '%'.$searchValue.'%')->where('products_status','=',1);

            if(!empty($data['categories_id'])){
                $categories->where('products_to_categories.categories_id','=', $data['categories_id']);
            }

            if(!empty($data['filters'])){
                $temp_key = 0;
                if(!empty($data['filters']['filter_attribute'])){
                    foreach($data['filters']['filter_attribute']['option_values'] as $option_id_temp){

                        if($temp_key == 0){

                            $categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);
                            if(count($data['filters']['filter_attribute']['options'])>1){

                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
                            }

                        }else{
                            $categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);

                            if(count($data['filters']['filter_attribute']['options'])>1){
                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
                            }

                        }
                        $temp_key++;
                    }

                }
            }
        }

        if(!empty($data['filters'])){
            $temp_key = 0;
            if(!empty($data['filters']['filter_attribute'])){
                foreach($data['filters']['filter_attribute']['option_values'] as $option_id_temp){

                    if($temp_key == 0){
                        $categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
                            ->where('products_attributes.options_values_id', $option_id_temp);

                        if(count($data['filters']['filter_attribute']['options'])>1){
                            $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
                        }

                    }else{
                        $categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
                            ->where('products_attributes.options_values_id', $option_id_temp);

                        if(count($data['filters']['filter_attribute']['options'])>1){
                            $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);
                        }

                    }
                    $temp_key++;
                }//foreach
            }//if conditon filter_attributes not empty
        }

        //wishlist customer id
        if($type == "wishlist"){
            $categories->where('liked_customers_id', '=', $customer_id);
        }

        //wishlist customer id
        if($type == "is_feature"){
            $categories->where('products.is_feature', '=', 1);
        }


        $categories->where('products_description.language_id','=',$language_id)->where('products_status','=',1);

        //get single category products
        if(!empty($data['categories_id'])){
            $categories->where('products_to_categories.categories_id','=', $data['categories_id']);
            $categories->where('categories_description.language_id','=',$language_id);
        }

        $categories->orderBy($sortby, $order)->groupBy('products.products_id');

        //count
        $total_record = $categories->get();
        $products  = $categories->skip($skip)->take($take)->get();

        $result = array();
        $result2 = array();

        //check if record exist
        if(count($products) > 0){

            $index = 0;
            foreach ($products as $products_data){
                $products_id = $products_data->products_id;

                //multiple images
                $products_images = DB::table('products_images')
                    ->LeftJoin('image_categories','products_images.image','=','image_categories.image_id')
                    ->select('image_categories.path as image_path','image_categories.image_type')
                    ->where('products_id','=', $products_id)
                    //->where('')
                    ->orderBy('sort_order', 'ASC')
                    //->groupBy('image_categories.image_id')
                    ->get();
                // DD($products_images);
                $products_data->images =  $products_images;

                $default_image_thumb = DB::table('products')
                    ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id')
                    ->select('image_categories.path as image_path','image_categories.image_type')
                    ->where('products_id','=', $products_id)
                    ->where('image_type','=', 'THUMBNAIL')
                    ->first();

                $products_data->default_thumb =  $default_image_thumb;

                //categories
                $categories = DB::table('products_to_categories')
                    ->leftjoin('categories','categories.categories_id','products_to_categories.categories_id')
                    ->leftjoin('categories_description','categories_description.categories_id','products_to_categories.categories_id')
                    ->select('categories.categories_id','categories_description.categories_name','categories.categories_image','categories.categories_icon', 'categories.parent_id')
                    ->where('products_id','=', $products_id)
                    ->where('categories_description.language_id','=', $language_id)->get();

                $products_data->categories =  $categories;
                array_push($result,$products_data);

                $options = array();
                $attr = array();

                $stocks = 0;
                $stockOut = 0;
                if($products_data->products_type == '0'){
                    $stocks = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','in')->sum('stock');
                    $stockOut = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','out')->sum('stock');
                }

                $result[$index]->defaultStock = $stocks - $stockOut;

                //like product
                if(!empty($customer_id)){
                    $liked_customers_id						=	$customer_id;
                    $categories = DB::table('liked_products')->where('liked_products_id', '=', $products_id)->where('liked_customers_id', '=', $liked_customers_id)->get();

                    if(count($categories)>0){
                        $result[$index]->isLiked = '1';
                    }else{
                        $result[$index]->isLiked = '0';
                    }
                }else{
                    $result[$index]->isLiked = '0';
                }

                // fetch all options add join from products_options table for option name
                $products_attribute = DB::table('products_attributes')->where('products_id','=', $products_id)->groupBy('options_id')->get();
                if(count($products_attribute)){
                    $index2 = 0;
                    foreach($products_attribute as $attribute_data){

                        $option_name = DB::table('products_options')
                            ->leftJoin('products_options_descriptions', 'products_options_descriptions.products_options_id', '=', 'products_options.products_options_id')->select('products_options.products_options_id', 'products_options_descriptions.options_name as products_options_name', 'products_options_descriptions.language_id')->where('language_id','=', $language_id)->where('products_options.products_options_id','=', $attribute_data->options_id)->get();

                        if(count($option_name)>0){

                            $temp = array();
                            $temp_option['id'] = $attribute_data->options_id;
                            $temp_option['name'] = $option_name[0]->products_options_name;
                            $temp_option['is_default'] = $attribute_data->is_default;
                            $attr[$index2]['option'] = $temp_option;

                            // fetch all attributes add join from products_options_values table for option value name
                            $attributes_value_query =  DB::table('products_attributes')->where('products_id','=', $products_id)->where('options_id','=', $attribute_data->options_id)->get();
                            $k = 0;
                            foreach($attributes_value_query as $products_option_value){

                                $option_value = DB::table('products_options_values')->leftJoin('products_options_values_descriptions','products_options_values_descriptions.products_options_values_id','=','products_options_values.products_options_values_id')->select('products_options_values.products_options_values_id', 'products_options_values_descriptions.options_values_name as products_options_values_name' )->where('products_options_values_descriptions.language_id','=', $language_id)->where('products_options_values.products_options_values_id','=', $products_option_value->options_values_id)->get();


                                $attributes = DB::table('products_attributes')->where([['products_id','=', $products_id],['options_id','=', $attribute_data->options_id],['options_values_id','=', $products_option_value->options_values_id]])->get();

                                $temp_i['products_attributes_id'] = $attributes[0]->products_attributes_id;
                                $temp_i['id'] = $products_option_value->options_values_id;
                                $temp_i['value'] = $option_value[0]->products_options_values_name;
                                $temp_i['price'] = $products_option_value->options_values_price;
                                $temp_i['price_prefix'] = $products_option_value->price_prefix;
                                array_push($temp,$temp_i);

                            }
                            $attr[$index2]['values'] = $temp;
                            $result[$index]->attributes = 	$attr;
                            $index2++;
                        }
                    }
                }else{
                    $result[$index]->attributes = 	array();
                }

                $is_sale_item = (isset($products_data->discount_price) and !empty($products_data->discount_price))? true: false;
                $sale_banner  = "";
                if($is_sale_item === true) {
                    $percent     = (($products_data->products_price - $products_data->discount_price)*100) /$products_data->products_price ;
                    $sale_banner = number_format($percent,'2')."% Sale";
                }

                $result2[$index] = [
                    "id"            => $products_data->products_id,
                    "title"         => $products_data->products_name,
                    "type"          => "simple",
                    "on_sale"       => $is_sale_item,
                    "sale_banner"   => $sale_banner,
                    "image"         => asset($products_data->image_path),
                    "price_html"    => "",
                    "price" => [
                        'regular_price' => $products_data->products_price,
                        'sale_price' => $products_data->discount_price,
                        'price' => $products_data->products_price,
                        'tax_price' => $products_data->products_price,
                        'price_including_tax' => (string) pd_adjust_price_with_tax($products_data->products_price),
                        'price_excluding_tax' => $products_data->products_price,
                        'tax_status' => 'taxable',
                        'tax_class' => '',
                    ],
                    "rating" => 0,
                    "attributes" => $result[$index]->attributes
                ];
                $index++;
            }
            $responseData = array('success'=>'1', 'product_data'=>$result2,  'message'=>Lang::get('website.Returned all products'), 'total_record'=>count($total_record));

        }else{
            $responseData = array('success'=>'0', 'product_data'=>$result2,  'message'=>Lang::get('website.Empty record'), 'total_record'=>count($total_record));
        }

        return($responseData);
    }

    public function reportProblem(Request $request){
        $problem = new \App\Models\AppProblem;
        $problem->email = $request->email;
        $problem->problem = $request->problem;
        $problem->save();

        $response = [
            'status'    => "success",
            'message'   => 'Problem submitted successfully!',
        ];
        return response()->json($response, 200);
    }
}
