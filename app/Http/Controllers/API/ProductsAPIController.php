<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\APICheckStocksPostRequest;
use App\Http\Requests\APIVariableCheckStockPostRequest;
use App\Models\Web\Products;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Http\Requests\APILiveSearchPostRequest;
use App\Models\Web\Cart;

class ProductsAPIController extends BaseController
{

    // call Constructor
    public function __construct(Cart $cart, Products $products)
    {
        $this->cart = $cart;
        $this->products = $products;
    }

    function index(Request $request)
    {
        $language_id     = 1;
        $category_id     = 0;
        $product_id      = 0;
        $page_no         = 0;
        $limit           = 10;
        $brand           = 0;
        $search          = "";
        $is_test         = false;

        if ($request->has('lang') and $request->get('lang') == 'ar') {
            $language_id     = 2;
        }

        if ($request->has('category')) {
            $category_id     = $request->get('category');
        }

        if ($request->has('include')) {
            $product_id     = $request->get('include');
        }

        if ($request->has('page')) {
            $page_no     = $request->get('page');
        }

        if ($request->has('search')) {
            $search     = $request->get('search');
        }

        if ($request->has('brand')) {
            $brand      = $request->get('brand');
        }

        if ($request->has('limit')) {
            $limit      = (int) $request->get('limit');
        }

        if ($request->has('is_test')) {
            $is_test      = $request->get('is_test');
        }

        $filters         = [
            'page_number'   => $page_no,
            'products_id'   => $product_id,
            'categories_id' => $category_id,
            'type'          => $request->get('type') ?? "",
            'limit'         => $limit,
            'min_price'     => '',
            'max_price'     => '',
            'search'        => $search,
            'brand'         => $brand,
            'is_test'       => $is_test
        ];
        $all_products    = $this->products($filters, $language_id);;

        return response()->json($all_products, 200, [], JSON_PRETTY_PRINT);
    }

    function live_search(APILiveSearchPostRequest $request)
    {
        $search_term        = $request->get('search');
        $language_id        = 1;
        $response           = [];
        $products           = DB::table('products')
            ->select('products.products_id AS id', 'products_description.products_name AS name', 'image_categories.path as image')
            ->leftJoin('products_description', 'products_description.products_id', '=', 'products.products_id')
            ->LeftJoin('image_categories', 'products.products_image', '=', 'image_categories.image_id')
            ->where('products_description.products_name', 'LIKE', '%' . $search_term . '%')
            ->where('products_description.language_id', '=', $language_id)
            ->groupBy('products.products_id')
            ->orderBy('products.products_id', 'DESC');

        if ($products->count() > 0) {
            foreach ($products->get() as $product) {
                $response[] = [
                    "id"    => $product->id,
                    "name"  => $product->name,
                    "image" => asset($product->image),
                ];
            }
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    function check_stock(APICheckStocksPostRequest $request)
    {
        $products_id = explode(',', $request->get('products_id'));
        $response = [
            'status'    => "success",
            'message'   => "Stock results fetched successfully.",
            'data'      => []
        ];

        foreach ($products_id as $product_id) {
            $product    = DB::table('products')->where('products_id', '=', $product_id)->first();
            $stocks     = 0;
            $stockOut   = 0;
            if ($product) {
                $product_type = $product->products_type;
                if ($product_type == 0) {
                    $stocks = DB::table('inventory')->where('products_id', $product->products_id)->where('stock_type', 'in')->sum('stock');
                    $stockOut = DB::table('inventory')->where('products_id', $product->products_id)->where('stock_type', 'out')->sum('stock');
                }

                $response['data'][$product_id] = [
                    'product_id' => $product_id,
                    "manage_stock" => ($product->products_type == '0') ? true : false,
                    "stock_quantity" => $stocks - $stockOut,
                    "in_stock" => (($stocks - $stockOut) > 0) ? true : false,
                ];
            }
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    function variable_check_stock(APIVariableCheckStockPostRequest $request)
    {
        $data = array();
        $data['products_id'] = $request->get('product_id');
        $data['attributes'] = $request->get('attributes');

        $result = $this->products->productQuantity($data);
        return response()->json($result, 200, [], JSON_PRETTY_PRINT);
    }

    private function products($data, $language_id)
    {
        if (empty($data['page_number']) or $data['page_number'] == 1) {
            $skip                                = 0;
        } else {
            $skip                                = ($data['limit'] * $data['page_number']) - $data['limit'];
        }

        $min_price                                 = $data['min_price'];
        $max_price                                 = $data['max_price'];
        $take                                    = $data['limit'];
        $type                                    = $data['type'];
        $brand                                  = $data['brand'];
        $currentDate                             = time();
        $customer_id                            = 0;
        $specials_prod_ids                      = [];
        $all_sales_data                         = [];
        // Adjustment for specials, flash sales and promotions

        // Promotions
        $promotions = DB::table('promotions')
            ->whereDate('end', '>', Carbon::now()->toDateString())
            ->where('is_active', '=', 1)
            ->get();
        if (count($promotions) > 0) {
            foreach ($promotions as $promotion) {
                $specials_prod_ids[$promotion->product_id_from] = $promotion->product_id_from;
                $all_sales_data['promotions'][$promotion->product_id_from] = $promotion;
            }
        }
        // Flash Sales
        $flash_sales = DB::table('flash_sale')
            ->where('flash_expires_date', '>', $currentDate)
            ->where('flash_status', '=', 1)
            ->get();
        if (count($flash_sales) > 0) {
            foreach ($flash_sales as $flash_sale) {
                $specials_prod_ids[$flash_sale->products_id] = $flash_sale->products_id;
                $all_sales_data['flash_sales'][$flash_sale->products_id] = $flash_sale;
            }
        }
        // Specials Sales
        $specials_sales = DB::table('specials')
            ->where('expires_date', '>', $currentDate)
            ->where('status', '=', 1)
            ->get();
        if (count($specials_sales) > 0) {
            foreach ($specials_sales as $specials_sale) {
                $specials_prod_ids[$specials_sale->products_id] = $specials_sale->products_id;
                $all_sales_data['specials'][$specials_sale->products_id] = $specials_sale;
            }
        }

        if ($type == "special") {
            if (count($specials_prod_ids) > 0) {
                $type = "atoz";
                $data['products_id'] = implode(',', $specials_prod_ids);
            }
        }

        if ($type == "atoz") {
            $sortby                                = "products_name";
            $order                                = "ASC";
        } elseif ($type == "ztoa") {
            $sortby                                = "products_name";
            $order                                = "DESC";
        } elseif ($type == "hightolow") {
            $sortby                                = "products_price";
            $order                                = "DESC";
        } elseif ($type == "lowtohigh") {
            $sortby                                = "products_price";
            $order                                = "ASC";
        } elseif ($type == "topseller") {
            $sortby                                = "products_ordered";
            $order                                = "DESC";
        } elseif ($type == "mostliked") {
            $sortby                                = "products_liked";
            $order                                = "DESC";
        } elseif ($type == "special") {
            $sortby                             = "specials.products_id";
            $order                              = "desc";
        } elseif ($type == "flashsale") {
            $sortby                             = "flash_sale.flash_start_date";
            $order                              = "asc";
        } else {
            $sortby                             = "products.products_id";
            $order                              = "desc";
        }

        $filterProducts = array();
        $eliminateRecord = array();

        $categories = DB::table('products')
            ->leftJoin('manufacturers', 'manufacturers.manufacturers_id', '=', 'products.manufacturers_id')
            ->leftJoin('manufacturers_info', 'manufacturers.manufacturers_id', '=', 'manufacturers_info.manufacturers_id')
            ->leftJoin('products_description', 'products_description.products_id', '=', 'products.products_id')
            ->LeftJoin('image_categories', 'products.products_image', '=', 'image_categories.image_id');

        if (!empty($data['categories_id'])) {
            $categories->LeftJoin('products_to_categories', 'products.products_id', '=', 'products_to_categories.products_id')
                ->leftJoin('categories', 'categories.categories_id', '=', 'products_to_categories.categories_id')
                ->LeftJoin('categories_description', 'categories_description.categories_id', '=', 'products_to_categories.categories_id');
        }

        if (!empty($data['filters']) and empty($data['search'])) {
            $categories->leftJoin('products_attributes', 'products_attributes.products_id', '=', 'products.products_id');
        }

        if (!empty($data['search'])) {
            $categories->leftJoin('products_attributes', 'products_attributes.products_id', '=', 'products.products_id')
                ->leftJoin('products_options', 'products_options.products_options_id', '=', 'products_attributes.options_id')
                ->leftJoin('products_options_values', 'products_options_values.products_options_values_id', '=', 'products_attributes.options_values_id');
        }
        //wishlist customer id
        if ($type == "wishlist") {
            $categories->LeftJoin('liked_products', 'liked_products.liked_products_id', '=', 'products.products_id')
                ->select('products.*', 'image_categories.path as image_path', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url');
        }
        //parameter special
        elseif ($type == "special") {
            $categories->LeftJoin('specials', 'specials.products_id', '=', 'products.products_id')
                ->select('products.*', 'image_categories.path as image_path', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price', 'specials.specials_new_products_price as discount_price');
        } elseif ($type == "flashsale") {
            //flash sale
            $categories->LeftJoin('flash_sale', 'flash_sale.products_id', '=', 'products.products_id')
                ->select(DB::raw(time() . ' as server_time'), 'products.*', 'image_categories.path as image_path', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'flash_sale.flash_start_date', 'flash_sale.flash_expires_date', 'flash_sale.flash_sale_products_price as flash_price');
        } elseif ($type == "compare") {
            //flash sale
            $categories->LeftJoin('flash_sale', 'flash_sale.products_id', '=', 'products.products_id')
                ->select(DB::raw(time() . ' as server_time'), 'products.*', 'image_categories.path as image_path', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'flash_sale.flash_start_date', 'flash_sale.flash_expires_date', 'flash_sale.flash_sale_products_price as discount_price');
        } else {
            $categories->LeftJoin('specials', function ($join) use ($currentDate) {
                $join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
            })->select('products.*', 'image_categories.path as image_path', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price');
        }

        if ($type == "special") { //deals special products
            $categories->where('specials.status', '=', '1')->where('expires_date', '>',  $currentDate);
        }

        if ($type == "flashsale") { //flashsale
            $categories->where('flash_sale.flash_status', '=', '1')->where('flash_expires_date', '>',  $currentDate);
        } elseif ($type != "compare") {
            /*$categories->whereNotIn('products.products_id',function($query) use ($currentDate) {
                $query->select('flash_sale.products_id')->from('flash_sale')->where('flash_sale.flash_status','=', '1');
            });*/
        }

        //get single products
        if (!empty($data['products_id']) && $data['products_id'] != "") {
            //$categories->where('products.products_id','=', $data['products_id']);
            $categories->whereIn('products.products_id', (array) explode(',', $data['products_id']));
        }

        //for min and maximum price
        if (!empty($max_price)) {
            $categories->whereBetween('products.products_price', [$min_price, $max_price]);
        }

        if (!empty($data['search'])) {

            $searchValue = $data['search'];
            $categories->where('products_options.products_options_name', 'LIKE', '%' . $searchValue . '%')->where('products_status', '=', 1);

            if (!empty($data['categories_id'])) {
                $categories->where('products_to_categories.categories_id', '=', $data['categories_id']);
            }

            if (!empty($data['filters'])) {
                $temp_key = 0;
                if (!empty($data['filters']['filter_attribute'])) {
                    foreach ($data['filters']['filter_attribute']['option_values'] as $option_id_temp) {
                        if ($temp_key == 0) {
                            $categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);
                            if (count($data['filters']['filter_attribute']['options']) > 1) {
                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in (' . $data['filters']['options'] . ') and `products_attributes`.`options_values_id` in (' . $data['filters']['option_value'] . '))'), '>=', $data['filters']['options_count']);
                            }
                        } else {
                            $categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);

                            if (count($data['filters']['filter_attribute']['options']) > 1) {
                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in (' . $data['filters']['options'] . ') and `products_attributes`.`options_values_id` in (' . $data['filters']['option_value'] . '))'), '>=', $data['filters']['options_count']);
                            }
                        }
                        $temp_key++;
                    }
                }
            }
            if (!empty($max_price)) {
                $categories->whereBetween('products.products_price', [$min_price, $max_price]);
            }

            $categories->orWhere('products_options_values.products_options_values_name', 'LIKE', '%' . $searchValue . '%')->where('products_status', '=', 1);
            if (!empty($data['categories_id'])) {
                $categories->where('products_to_categories.categories_id', '=', $data['categories_id']);
            }

            if (!empty($data['filters'])) {
                $temp_key = 0;
                if (!empty($data['filters']['filter_attribute'])) {
                    foreach ($data['filters']['filter_attribute']['option_values'] as $option_id_temp) {

                        if ($temp_key == 0) {

                            $categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);
                            if (count($data['filters']['filter_attribute']['options']) > 1) {

                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in (' . $data['filters']['options'] . ') and `products_attributes`.`options_values_id` in (' . $data['filters']['option_value'] . '))'), '>=', $data['filters']['options_count']);
                            }
                        } else {
                            $categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);

                            if (count($data['filters']['filter_attribute']['options']) > 1) {
                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in (' . $data['filters']['options'] . ') and `products_attributes`.`options_values_id` in (' . $data['filters']['option_value'] . '))'), '>=', $data['filters']['options_count']);
                            }
                        }
                        $temp_key++;
                    }
                }
            }

            if (!empty($max_price)) {
                $categories->whereBetween('products.products_price', [$min_price, $max_price]);
            }

            $categories->orWhere('products_name', 'LIKE', '%' . $searchValue . '%')->where('products_status', '=', 1);

            if (!empty($data['categories_id'])) {
                $categories->where('products_to_categories.categories_id', '=', $data['categories_id']);
            }

            if (!empty($data['filters'])) {
                $temp_key = 0;
                if (!empty($data['filters']['filter_attribute'])) {
                    foreach ($data['filters']['filter_attribute']['option_values'] as $option_id_temp) {

                        if ($temp_key == 0) {

                            $categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);
                            if (count($data['filters']['filter_attribute']['options']) > 1) {

                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in (' . $data['filters']['options'] . ') and `products_attributes`.`options_values_id` in (' . $data['filters']['option_value'] . '))'), '>=', $data['filters']['options_count']);
                            }
                        } else {
                            $categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);

                            if (count($data['filters']['filter_attribute']['options']) > 1) {
                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in (' . $data['filters']['options'] . ') and `products_attributes`.`options_values_id` in (' . $data['filters']['option_value'] . '))'), '>=', $data['filters']['options_count']);
                            }
                        }
                        $temp_key++;
                    }
                }
            }

            if (!empty($max_price)) {
                $categories->whereBetween('products.products_price', [$min_price, $max_price]);
            }

            $categories->orWhere('products_model', 'LIKE', '%' . $searchValue . '%')->where('products_status', '=', 1);

            if (!empty($data['categories_id'])) {
                $categories->where('products_to_categories.categories_id', '=', $data['categories_id']);
            }

            if (!empty($data['filters'])) {
                $temp_key = 0;
                if (!empty($data['filters']['filter_attribute'])) {
                    foreach ($data['filters']['filter_attribute']['option_values'] as $option_id_temp) {

                        if ($temp_key == 0) {

                            $categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);
                            if (count($data['filters']['filter_attribute']['options']) > 1) {

                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in (' . $data['filters']['options'] . ') and `products_attributes`.`options_values_id` in (' . $data['filters']['option_value'] . '))'), '>=', $data['filters']['options_count']);
                            }
                        } else {
                            $categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
                                ->where('products_attributes.options_values_id', $option_id_temp);

                            if (count($data['filters']['filter_attribute']['options']) > 1) {
                                $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in (' . $data['filters']['options'] . ') and `products_attributes`.`options_values_id` in (' . $data['filters']['option_value'] . '))'), '>=', $data['filters']['options_count']);
                            }
                        }
                        $temp_key++;
                    }
                }
            }
        }

        if (!empty($data['filters'])) {
            $temp_key = 0;
            if (!empty($data['filters']['filter_attribute'])) {
                foreach ($data['filters']['filter_attribute']['option_values'] as $option_id_temp) {

                    if ($temp_key == 0) {
                        $categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
                            ->where('products_attributes.options_values_id', $option_id_temp);

                        if (count($data['filters']['filter_attribute']['options']) > 1) {
                            $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in (' . $data['filters']['options'] . ') and `products_attributes`.`options_values_id` in (' . $data['filters']['option_value'] . '))'), '>=', $data['filters']['options_count']);
                        }
                    } else {
                        $categories->orwhereIn('products_attributes.options_id', [$data['filters']['options']])
                            ->where('products_attributes.options_values_id', $option_id_temp);

                        if (count($data['filters']['filter_attribute']['options']) > 1) {
                            $categories->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in (' . $data['filters']['options'] . ') and `products_attributes`.`options_values_id` in (' . $data['filters']['option_value'] . '))'), '>=', $data['filters']['options_count']);
                        }
                    }
                    $temp_key++;
                } //foreach
            } //if conditon filter_attributes not empty
        }

        //wishlist customer id
        if ($type == "wishlist") {
            $categories->where('liked_customers_id', '=', $customer_id);
        }

        //wishlist customer id
        if ($type == "is_feature") {
            $categories->where('products.is_feature', '=', 1);
        }

        $categories->where('products_description.language_id', '=', $language_id)->where('products_status', '=', 1);

        //get single category products
        if (!empty($data['categories_id'])) {
            $categories->where('products_to_categories.categories_id', '=', $data['categories_id']);
            $categories->where('categories_description.language_id', '=', $language_id);
        }

        if (isset($brand) and !empty($brand)) {
            $categories->where('manufacturers.manufacturers_id', '=', $brand);
        }

        $categories->orderBy($sortby, $order)->groupBy('products.products_id');

        //count
        $total_record = $categories->get();
        $products  = $categories->skip($skip)->take($take)->get();

        $result = array();
        $result2 = array();

        //check if record exist
        if (count($products) > 0) {

            $index = 0;
            foreach ($products as $products_data) {
                $products_id = $products_data->products_id;

                //multiple images
                $products_images = DB::table('products_images')
                    ->LeftJoin('image_categories', 'products_images.image', '=', 'image_categories.image_id')
                    ->select('image_categories.path as image_path', 'image_categories.image_type')
                    ->where('products_id', '=', $products_id)
                    ->orderBy('sort_order', 'ASC')
                    //->groupBy('image_categories.image_id')
                    ->get();
                $products_data->images =  $products_images;

                $default_image_thumb = DB::table('products')
                    ->LeftJoin('image_categories', 'products.products_image', '=', 'image_categories.image_id')
                    ->select('image_categories.path as image_path', 'image_categories.image_type')
                    ->where('products_id', '=', $products_id)
                    ->where('image_type', '=', 'THUMBNAIL')
                    ->first();

                $products_data->default_thumb =  $default_image_thumb;

                //categories
                $categories = DB::table('products_to_categories')
                    ->leftjoin('categories', 'categories.categories_id', 'products_to_categories.categories_id')
                    ->leftjoin('categories_description', 'categories_description.categories_id', 'products_to_categories.categories_id')
                    ->select('categories.categories_id', 'categories.categories_slug', 'categories_description.categories_name', 'categories.categories_image', 'categories.categories_icon', 'categories.parent_id')
                    ->where('products_id', '=', $products_id)
                    ->where('categories_description.language_id', '=', $language_id)->get();

                $products_data->categories =  $categories;
                array_push($result, $products_data);

                $options = array();
                $attr = array();

                $stocks = 0;
                $stockOut = 0;
                if ($products_data->products_type == '0') {
                    $stocks = DB::table('inventory')->where('products_id', $products_data->products_id)->where('stock_type', 'in')->sum('stock');
                    $stockOut = DB::table('inventory')->where('products_id', $products_data->products_id)->where('stock_type', 'out')->sum('stock');
                }

                $result[$index]->defaultStock = $stocks - $stockOut;

                //like product
                if (!empty($customer_id)) {
                    $liked_customers_id                        =    $customer_id;
                    $categories = DB::table('liked_products')->where('liked_products_id', '=', $products_id)->where('liked_customers_id', '=', $liked_customers_id)->get();

                    if (count($categories) > 0) {
                        $result[$index]->isLiked = '1';
                    } else {
                        $result[$index]->isLiked = '0';
                    }
                } else {
                    $result[$index]->isLiked = '0';
                }

                // fetch all options add join from products_options table for option name
                $products_attribute = DB::table('products_attributes')->where('products_id', '=', $products_id)->groupBy('options_id')->get();
                if (count($products_attribute)) {
                    $index2 = 0;
                    foreach ($products_attribute as $attribute_data) {

                        $option_name = DB::table('products_options')
                            ->leftJoin('products_options_descriptions', 'products_options_descriptions.products_options_id', '=', 'products_options.products_options_id')->select('products_options.products_options_id', 'products_options_descriptions.options_name as products_options_name', 'products_options_descriptions.language_id')->where('language_id', '=', $language_id)->where('products_options.products_options_id', '=', $attribute_data->options_id)->get();

                        if (count($option_name) > 0) {

                            $temp = array();
                            $temp_option['id'] = $attribute_data->options_id;
                            $temp_option['name'] = $option_name[0]->products_options_name;
                            $temp_option['is_default'] = $attribute_data->is_default;
                            $attr[$index2]['option'] = $temp_option;

                            // fetch all attributes add join from products_options_values table for option value name
                            $attributes_value_query =  DB::table('products_attributes')->where('products_id', '=', $products_id)->where('options_id', '=', $attribute_data->options_id)->get();
                            $k = 0;
                            foreach ($attributes_value_query as $products_option_value) {

                                $option_value = DB::table('products_options_values')->leftJoin('products_options_values_descriptions', 'products_options_values_descriptions.products_options_values_id', '=', 'products_options_values.products_options_values_id')->select('products_options_values.products_options_values_id', 'products_options_values_descriptions.options_values_name as products_options_values_name')->where('products_options_values_descriptions.language_id', '=', $language_id)->where('products_options_values.products_options_values_id', '=', $products_option_value->options_values_id)->get();


                                $attributes = DB::table('products_attributes')->where([['products_id', '=', $products_id], ['options_id', '=', $attribute_data->options_id], ['options_values_id', '=', $products_option_value->options_values_id]])->get();

                                $temp_i['products_attributes_id'] = $attributes[0]->products_attributes_id;
                                $temp_i['id'] = $products_option_value->options_values_id;
                                $temp_i['value'] = $option_value[0]->products_options_values_name;
                                $temp_i['price'] = $products_option_value->options_values_price;
                                $temp_i['price_prefix'] = $products_option_value->price_prefix;
                                array_push($temp, $temp_i);
                            }
                            $attr[$index2]['values'] = $temp;
                            $result[$index]->attributes =     $attr;
                            $index2++;
                        }
                    }
                } else {
                    $result[$index]->attributes =     array();
                }

                $product_categories_data    = [];
                foreach ($categories as $category) {
                    $product_categories_data[]    = [
                        "id"        => $category->categories_id,
                        "name"      => $category->categories_name,
                        "slug"      => $category->categories_slug,
                        "parent_id" => $category->parent_id,
                    ];
                }

                $product_categories         = collect($categories)->map(function ($category) {
                    return $category->categories_id;
                });
                $similar_products           = DB::table('products_to_categories')
                    ->select('products_id')
                    ->where('products_id', '!=', $products_data->products_id)
                    ->whereIn('categories_id', $product_categories)
                    ->pluck('products_id');

                $is_sale_item = (isset($products_data->discount_price) and !empty($products_data->discount_price)) ? true : false;
                $sale_banner  = "";
                if ($is_sale_item === true) {
                    $percent     = (($products_data->products_price - $products_data->discount_price) * 100) / $products_data->products_price;
                    $sale_banner = number_format($percent, '2') . "% Sale";
                }

                $result2[$index] = [
                    "id"                    => $products_data->products_id,
                    "name"                  => $products_data->products_name,
                    "slug"                  => $products_data->products_slug,
                    "permalink"             => URL::to('/product-detail/' . $products_data->products_slug),
                    "date_created"          => $products_data->products_date_added,
                    "date_created_gmt"      => Carbon::parse($products_data->products_date_added)->format('Y-m-d\TH:i:s'),
                    "date_modified"         => $products_data->products_last_modified,
                    "date_modified_gmt"     => Carbon::parse($products_data->products_last_modified)->format('Y-m-d\TH:i:s'),
                    "type"                  => "simple",
                    "status"                => ($products_data->products_status == 1) ? 'publish' : 'pending',
                    "featured"              => ($products_data->is_feature == 1) ? true : false,
                    "catalog_visibility"    => "visible",
                    "description"           => $products_data->products_description,
                    "details"               => $products_data->products_details,
                    "short_description"     => Str::of(strip_tags($products_data->products_description))->limit(100),
                    "sku"                   => "",
                    'price'                 => $products_data->products_price,
                    'tax_price'             => $products_data->products_price,
                    'price_excluding_tax'   => $products_data->products_price,
                    'price_including_tax'   => (string) pd_adjust_price_with_tax($products_data->products_price),
                    'regular_price'         => $products_data->products_price,
                    'sale_price'            => $products_data->discount_price ?? "",
                    'date_on_sale_from'     => null,
                    'date_on_sale_from_gmt' => null,
                    'date_on_sale_to'       => null,
                    'date_on_sale_to_gmt'   => null,
                    "price_html"            => "",
                    "on_sale"               => (isset($products_data->discount_price) and !empty($products_data->discount_price)) ? true : false,
                    "sale_banner"           => $sale_banner,
                    "purchasable"           => true,
                    "total_sales"           => DB::table('orders_products')->where('products_id', $products_data->products_id)->count(),
                    "virtual"               => false,
                    "downloadable"          => false,
                    "downloads"             => [],
                    "download_limit"        => -1,
                    "download_expiry"       => -1,
                    "external_url"          => '',
                    "button_text"           => '',
                    "tax_status"            => 'taxable',
                    "tax_class"             => '',
                    "manage_stock"          => ($products_data->products_type == '0') ? true : false,
                    "stock_quantity"        => $stocks - $stockOut,
                    "in_stock"              => (($stocks - $stockOut) > 0) ? true : false,
                    "backorders"            => "no",
                    "backorders_allowed"    => false,
                    "backordered"           => false,
                    "sold_individually"     => false,
                    "weight"                => $products_data->products_weight,
                    "dimensions"            => ["length" => "", "width" => "", "height" => ""],
                    "shipping_required"     => true,
                    "shipping_class_id"     => 0,
                    "reviews_allowed"       => true,
                    "average_rating"        => 0,
                    "rating_count"          => 0,
                    "related_ids"           => $similar_products,
                    "upsell_ids"            => [],
                    "cross_sell_ids"        => [],
                    "parent_id"             => 0,
                    "purchase_note"         => '',
                    "categories"            => $product_categories_data,
                    "tags"                  => [],
                    "images"                => [[
                        'id'                => 1,
                        'date_created'      => Carbon::now()->format('Y-m-d H:i:s'),
                        'date_created_gmt'  => Carbon::now()->format('Y-m-d\TH:i:s'),
                        'date_modified'     => Carbon::now()->format('Y-m-d H:i:s'),
                        'date_modified_gmt' => Carbon::now()->format('Y-m-d\TH:i:s'),
                        'src'               => asset($products_data->image_path),
                        'name'              => basename($products_data->image_path),
                        'alt'               => $products_data->products_name,
                        'position'          => 0
                    ]],
                    "app_thumbnail"         => asset($default_image_thumb->image_path) ?: '',
                    "attributes"            => $result[$index]->attributes,
                    "default_attributes"    => [],
                    "variations"            => [],
                    "grouped_products"      => [],
                    "menu_order"            => 0,
                    "meta_data"             => [],
                    "rewards_message"       => '',
                    "addition_info_html"    => '',
                    "seller_info"           => ['is_seller' => false]
                ];

                /* Promotional Info */
                $promotions = $this->cart->get_promotions($products_data->products_id);
                $result2[$index]['promotions'] = $promotions;

                if (!empty($promotions[0]->product_id_from) && $products_data->products_id == $promotions[0]->product_id_from) {
                    $result2[$index]['has_promotion'] = true;
                    $result2[$index]['banner_text'] = "Buy " . $promotions[0]->buy_frequency . " Get " . $promotions[0]->get_frequency . " Free";
                }

                if (
                    isset($all_sales_data) and
                    !empty($all_sales_data['flash_sales']) and
                    isset($all_sales_data['flash_sales'][$products_data->products_id]) and
                    !empty($all_sales_data['flash_sales'][$products_data->products_id])
                ) {
                    $result2[$index]['sale_price']  = $all_sales_data['flash_sales'][$products_data->products_id]->flash_sale_products_price;
                    $percent_flash_sale             = (($products_data->products_price - $result2[$index]['sale_price']) * 100) / $products_data->products_price;
                    $result2[$index]['banner_text'] = number_format($percent_flash_sale, 0) . "% Sale";
                    $result2[$index]['on_sale']     = true;
                }

                $index++;
            }
            $responseData = $result2;
        } else {
            $responseData = $result2;
        }

        return ($responseData);
    }
}
