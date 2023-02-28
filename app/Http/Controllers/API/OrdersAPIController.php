<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Web\AlertController;
use App\Http\Requests\APISubmitOrderPostRequest;
use App\Http\Requests\APIVerifyCouponRequest;
use App\Models\User;
use Postpay\Exceptions\RESTfulException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class OrdersAPIController extends BaseController
{

    function index(APISubmitOrderPostRequest $request)
    {
        try {
            $language_id            = 1;
            $customer_id            = $request->get('customer_id');
            $payment_method         = $request->get('payment_method');
            $payment_method_title   = $request->get('payment_method_title');
            $set_paid               = $request->get('set_paid');
            $billing_info           = $request->get('billing');
            $shipping_info          = $request->get('shipping');
            $line_items             = $request->get('line_items');
            $shipping_lines         = $request->get('shipping_lines');
            $pickup_from_store_id   = $request->get('store_id', 0);
            $date_added             = date('Y-m-d h:i:s');
            $guest_checkout         = true;
            $ordered_source         = $request->get('order_source', '2');

            $coupon_code            = $request->get('coupon', '');
            $coupon_amount          = $request->get('coupon_amount', 0.00);

            if (empty($ordered_source)) {
                $ordered_source = '2';
            }
            $response = [
                'status' => 0,
                'message' => '',
                'guest_checkout' => $guest_checkout,
                'payment_status' => 'failed',
            ];

            // Check if guest checkout or customer based.
            if (!empty($customer_id) and $customer_id > 0) {
                $response['guest_checkout'] = $guest_checkout = false;
                $user = User::where('id', '=', $customer_id)->first();
                if (empty($user)) {
                    $response['message'] = 'Invalid customer, Please provide valid customer_id.';
                    return response()->json($response, 200, [], JSON_PRETTY_PRINT);
                }
            }

            if ($guest_checkout == true) {
                $email = $billing_info['email'];
                $check = DB::table('users')->where('role_id', 2)->where('email', $email)->first();
                if ($check == null) {
                    $customers_id = DB::table('users')->insertGetId([
                        'role_id' => 2,
                        'email' => $billing_info['email'],
                        'password' => Hash::make('123456dfdfdf'),
                        'first_name' => $billing_info['first_name'],
                        'last_name' => $billing_info['last_name'],
                        'phone' => $billing_info['phone'],
                    ]);
                } else {
                    $customers_id = $check->id;
                    $email = $check->email;
                }
            } else {
                $customers_id = $user->id;
                $email = $user->email;
            }

            $delivery_company = "";
            $delivery_firstname = $shipping_info['first_name'] ?: $billing_info['first_name'];
            $delivery_lastname = $shipping_info['last_name'] ?: $billing_info['last_name'];
            $delivery_street_address = $shipping_info['address_1'] ?: $billing_info['address_1'];
            $delivery_suburb = '';
            $delivery_city = $shipping_info['city'] ?: $billing_info['city'];
            $delivery_postcode = $shipping_info['postcode'] ?: $billing_info['postcode'];
            $delivery_phone = $billing_info['phone'];
            $delivery_state = 'other';

            $delivery_country = get_country_id_by_iso($shipping_info['country'] ?? "");
            $billing_firstname = $billing_info['first_name'];
            $billing_lastname = $billing_info['last_name'];
            $billing_street_address = trim($billing_info['address_1'] . ' ' . $billing_info['address_2']);
            $billing_suburb = '';
            $billing_city = $billing_info['city'];
            $billing_postcode = $billing_info['postcode'];
            $billing_phone = $billing_info['phone'];
            $billing_company = "";
            $billing_state = 'other';
            $billing_country = get_country_id_by_iso($billing_info['country'] ?? "");

            $payment_method = $payment_method;
            $order_information = array();
            $cc_type = '';
            $cc_owner = '';
            $cc_number = '';
            $cc_expires = '';

            $last_modified = date('Y-m-d H:i:s');
            $date_purchased = date('Y-m-d H:i:s');

            //price
            if (!empty($shipping_lines) and isset($shipping_lines[0]['total'])) {
                $shipping_price = $shipping_lines[0]['total'];
            } else {
                $shipping_price = 0;
            }

            $products_total = 0;
            $total_qty = 0;
            $cart_products = [];
            foreach ($line_items as $line_item) {
                $product = DB::table('products')
                    ->select('products.*', 'products_description.products_name')
                    ->leftJoin('products_description', 'products_description.products_id', '=', 'products.products_id')
                    ->where('products.products_id', '=', $line_item['product_id'])
                    ->where('products_description.language_id', '=', $language_id)
                    ->first();
                if ($product) {
                    $prod_price         = $product->products_price;
                    $prod_final_price   = 0;
                    if (isset($line_item['is_free']) and $line_item['is_free'] == true) {
                        $prod_price = $prod_final_price = 0;
                    } else if (isset($line_item['attributes']) and !empty($line_item['attributes'])) {
                        $selected_attributes = $line_item['attributes'];
                        foreach ($selected_attributes as $selected_attribute) {
                            if (isset($selected_attribute['prefix']) and !empty($selected_attribute['prefix'])) {
                                if ($selected_attribute['prefix'] == "-") {
                                    $prod_price -= $selected_attribute['values_price'];
                                } elseif ($selected_attribute['prefix'] == "+") {
                                    $prod_price += $selected_attribute['values_price'];
                                }
                            }
                        }
                    }

                    $prod_final_price   = $prod_price * $line_item['quantity'];
                    // return $line_item;
                    $cart_products[] = [
                        'products_id' => $product->products_id,
                        'products_name' => $product->products_name,
                        'products_price' => $prod_price,
                        'final_price' => $prod_final_price,
                        'products_tax' => 0.00,
                        'products_quantity' => $line_item['quantity'],
                        'attributes' => $line_item['attributes']
                    ];
                    $products_total += $prod_final_price;
                    $total_qty += $line_item['quantity'];
                }
            }

            $tax_rate           = Config::get('global.p_constant');
            $total_tax          = (float) ($products_total * $tax_rate);
            $coupon_discount    = 0.00;
            if (isset($coupon_amount) and !empty($coupon_amount)) {
                $coupon_discount    = $coupon_amount;
            }
            $order_price        = ($products_total + $total_tax + $shipping_price) - $coupon_discount;
            $shipping_cost      = $shipping_price;
            $shipping_method    = $shipping_lines[0]['method_title'];
            $orders_status      = '1';
            $comments           = '';

            $web_setting = DB::table('settings')->get();
            $currency = $web_setting[19]->value;

            $products_tax = 1;
            $code = '';
            if (isset($coupon_code) and !empty($coupon_code)) {
                $currentDate    =   date('Y-m-d 00:00:00', time());
                $coupon_data    =   DB::table('coupons')->where([
                    ['code', '=', $coupon_code],
                ]);
                $coupons = $coupon_data->get();
                $code = json_encode($coupons);
            }
            $payment_method = $payment_method_title;
            $payment_status = 'success';

            if ($payment_status == 'success') {

                $orders_id = DB::table('orders')->insertGetId([
                    'customers_id' => $customers_id,
                    'customers_name' => $delivery_firstname . ' ' . $delivery_lastname,
                    'customers_street_address' => $delivery_street_address,
                    'customers_suburb' => $delivery_suburb,
                    'customers_city' => $delivery_city,
                    'customers_postcode' => $delivery_postcode ?? "",
                    'customers_state' => $delivery_state,
                    'customers_country' => $delivery_country,
                    'email' => $email,
                    'delivery_name' => $delivery_firstname . ' ' . $delivery_lastname,
                    'delivery_street_address' => $delivery_street_address,
                    'delivery_suburb' => $delivery_suburb,
                    'delivery_city' => $delivery_city,
                    'delivery_postcode' => $delivery_postcode ?? "",
                    'delivery_state' => $delivery_state,
                    'delivery_country' => $delivery_country,
                    'billing_name' => $billing_firstname . ' ' . $billing_lastname,
                    'billing_street_address' => $billing_street_address,
                    'billing_suburb' => $billing_suburb,
                    'billing_city' => $billing_city,
                    'billing_postcode' => $billing_postcode ?? "",
                    'billing_state' => $billing_state,
                    'billing_country' => $billing_country,
                    'payment_method' => $payment_method,
                    'cc_type' => $cc_type,
                    'cc_owner' => $cc_owner,
                    'cc_number' => $cc_number,
                    'cc_expires' => $cc_expires,
                    'last_modified' => $last_modified,
                    'date_purchased' => $date_purchased,
                    'order_price' => $order_price,
                    'shipping_cost' => $shipping_cost,
                    'shipping_method' => $shipping_method,
                    'currency' => $currency,
                    'order_information' => json_encode($order_information),
                    'coupon_code' => $code,
                    'coupon_amount' => $coupon_amount,
                    'total_tax' => $total_tax,
                    'ordered_source' => $ordered_source,
                    'delivery_phone' => $delivery_phone,
                    'billing_phone' => $billing_phone,
                    'pickup_store_id' => $pickup_from_store_id
                ]);

                //orders status history
                $orders_history_id = DB::table('orders_status_history')->insertGetId([
                    'orders_id' => $orders_id,
                    'orders_status_id' => $orders_status,
                    'date_added' => $date_added,
                    'customer_notified' => '1',
                    'comments' => $comments
                ]);

                foreach ($cart_products as $products) {
                    //get products info
                    $orders_products_id = DB::table('orders_products')->insertGetId([
                        'orders_id' => $orders_id,
                        'products_id' => $products['products_id'],
                        'products_name' => $products['products_name'],
                        'products_price' => $products['products_price'],
                        'final_price' => $products['final_price'],
                        'products_tax' => $products['products_tax'],
                        'products_quantity' => $products['products_quantity']
                    ]);

                    $inventory_ref_id = DB::table('inventory')->insertGetId([
                        'products_id' => $products['products_id'],
                        'reference_code' => '',
                        'stock' => $products['products_quantity'],
                        'admin_id' => 0,
                        'added_date' => time(),
                        'purchase_price' => 0,
                        'stock_type' => 'out',
                    ]);

                    if (isset($products['attributes']) and !empty($products['attributes'])) {
                        foreach ($products['attributes'] as $attribute) {
                            DB::table('orders_products_attributes')->insert(
                                [
                                    'orders_id'                => $orders_id,
                                    'products_id'              => $products['products_id'],
                                    'orders_products_id'       => $orders_products_id,
                                    'products_options'         => $attribute['attribute_name'],
                                    'products_options_values'  => $attribute['attribute_value'],
                                    'options_values_price'     => $attribute['values_price'],
                                    'price_prefix'             => $attribute['prefix']
                                ]
                            );

                            DB::table('inventory_detail')->insert([
                                'inventory_ref_id' => $inventory_ref_id,
                                'products_id'      => $products['products_id'],
                                'attribute_id'        => $db_attributes->products_attributes_id ?? 0,
                            ]);
                        }
                    }
                }

                //notification/email
                try {
                    $order = DB::table('orders')
                        ->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
                        ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
                        ->where('orders.orders_id', '=', $orders_id)->orderby('orders_status_history.date_added', 'DESC')->get();
                    foreach ($order as $data) {
                        $orders_id     = $data->orders_id;
                        $orders_products = DB::table('orders_products')
                            ->join('products', 'products.products_id', '=', 'orders_products.products_id')
                            ->select('orders_products.*', 'products.products_image as image')
                            ->where('orders_products.orders_id', '=', $orders_id)->get();
                        $i              = 0;
                        $total_price    = 0;
                        $product        = array();
                        $subtotal       = 0;
                        foreach ($orders_products as $orders_products_data) {
                            $product_attribute = DB::table('orders_products_attributes')
                                ->where([
                                    ['orders_products_id', '=', $orders_products_data->orders_products_id],
                                    ['orders_id', '=', $orders_products_data->orders_id],
                                ])
                                ->get();
                            $orders_products_data->attribute = $product_attribute;
                            $product[$i] = $orders_products_data;
                            //$total_tax	 = $total_tax+$orders_products_data->products_tax;
                            $total_price = $total_price + $orders_products[$i]->final_price;
                            $subtotal += $orders_products[$i]->final_price;
                            $i++;
                        }
                        $data->data = $product;
                        $orders_data[] = $data;
                    }

                    $orders_status_history = DB::table('orders_status_history')
                        ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
                        ->orderBy('orders_status_history.date_added', 'desc')
                        ->where('orders_id', '=', $orders_id)->get();

                    $orders_status = DB::table('orders_status')->get();

                    $ordersData['orders_data']                  =    $orders_data;
                    $ordersData['total_price']              =    $total_price;
                    $ordersData['orders_status']            =    $orders_status;
                    $ordersData['orders_status_history']    =    $orders_status_history;
                    $ordersData['subtotal']                    =    $subtotal;

                    $myVar = new AlertController();
                    $myVar->orderAlert($ordersData);
                } catch (\Exception $ex) {
                }

                $response = [
                    'status' => 1,
                    'message' => 'Order has been placed successfully.',
                    'guest_checkout' => $guest_checkout,
                    'payment_status' => $payment_status,
                ];
            }
        } catch (\Exception $exception) {
            $response['status']     = 0;
            $response['message']    = $exception->getMessage();
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function postpay(APISubmitOrderPostRequest $request)
    {
        // return "hhhhh";

        try {
            $language_id            = 1;
            $customer_id            = $request->get('customer_id');
            $payment_method         = $request->get('payment_method');
            $payment_method_title   = $request->get('payment_method_title');
            $set_paid               = $request->get('set_paid');
            $billing_info           = $request->get('billing');
            $shipping_info          = $request->get('shipping');
            $line_items             = $request->get('line_items');
            $shipping_lines         = $request->get('shipping_lines');
            $pickup_from_store_id   = $request->get('store_id', 0);
            $date_added             = date('Y-m-d h:i:s');
            $guest_checkout         = true;
            $ordered_source         = $request->get('order_source', '2');

            $coupon_code            = $request->get('coupon', '');
            $coupon_amount          = $request->get('coupon_amount', 0.00);

            if (empty($ordered_source)) {
                $ordered_source = '2';
            }
            $response = [
                'status' => 0,
                'message' => '',
                'guest_checkout' => $guest_checkout,
                'payment_status' => 'failed',
            ];

            // Check if guest checkout or customer based.
            if (!empty($customer_id) and $customer_id > 0) {
                $response['guest_checkout'] = $guest_checkout = false;
                $user = User::where('id', '=', $customer_id)->first();
                if (empty($user)) {
                    $response['message'] = 'Invalid customer, Please provide valid customer_id.';
                    return response()->json($response, 200, [], JSON_PRETTY_PRINT);
                }
            }

            if ($guest_checkout == true) {
                $email = $billing_info['email'];
                $check = DB::table('users')->where('role_id', 2)->where('email', $email)->first();
                if ($check == null) {
                    $customers_id = DB::table('users')->insertGetId([
                        'role_id' => 2,
                        'email' => $billing_info['email'],
                        'password' => Hash::make('123456dfdfdf'),
                        'first_name' => $billing_info['first_name'],
                        'last_name' => $billing_info['last_name'],
                        'phone' => $billing_info['phone'],
                    ]);
                } else {
                    $customers_id = $check->id;
                    $email = $check->email;
                }
            } else {
                $customers_id = $user->id;
                $email = $user->email;
            }

            $delivery_company = "";
            $delivery_firstname = $shipping_info['first_name'] ?: $billing_info['first_name'];
            $delivery_lastname = $shipping_info['last_name'] ?: $billing_info['last_name'];
            $delivery_street_address = $shipping_info['address_1'] ?: $billing_info['address_1'];
            $delivery_suburb = '';
            $delivery_city = $shipping_info['city'] ?: $billing_info['city'];
            $delivery_postcode = $shipping_info['postcode'] ?: $billing_info['postcode'];
            $delivery_phone = $billing_info['phone'];
            $delivery_state = 'other';

            $delivery_country = get_country_id_by_iso($shipping_info['country'] ?? "");
            $billing_firstname = $billing_info['first_name'];
            $billing_lastname = $billing_info['last_name'];
            $billing_street_address = trim($billing_info['address_1'] . ' ' . $billing_info['address_2']);
            $billing_suburb = '';
            $billing_city = $billing_info['city'];
            $billing_postcode = $billing_info['postcode'];
            $billing_phone = $billing_info['phone'];
            $billing_company = "";
            $billing_state = 'other';
            $billing_country = get_country_id_by_iso($billing_info['country'] ?? "");

            $payment_method = $payment_method;
            $order_information = array();
            $cc_type = '';
            $cc_owner = '';
            $cc_number = '';
            $cc_expires = '';

            $last_modified = date('Y-m-d H:i:s');
            $date_purchased = date('Y-m-d H:i:s');

            //price
            if (!empty($shipping_lines) and isset($shipping_lines[0]['total'])) {
                // echo "hhhh";
                // die;
                $shipping_price = $shipping_lines[0]['total'];
            } else {
                $shipping_price = 0;
            }

            $products_total = 0;
            $total_qty = 0;
            $cart_products = [];
            foreach ($line_items as $line_item) {
                $product = DB::table('products')
                    ->select('products.*', 'products_description.products_name')
                    ->leftJoin('products_description', 'products_description.products_id', '=', 'products.products_id')
                    ->where('products.products_id', '=', $line_item['product_id'])
                    ->where('products_description.language_id', '=', $language_id)
                    ->first();
                if ($product) {
                    $prod_price         = $product->products_price;
                    $prod_final_price   = 0;
                    if (isset($line_item['is_free']) and $line_item['is_free'] == true) {

                        $prod_price = $prod_final_price = 0;
                    } else if (isset($line_item['attributes']) and !empty($line_item['attributes'])) {
                        // echo "hhhh";
                        // die;
                        $selected_attributes = $line_item['attributes'];
                        foreach ($selected_attributes as $selected_attribute) {
                            if (isset($selected_attribute['prefix']) and !empty($selected_attribute['prefix'])) {
                                if ($selected_attribute['prefix'] == "-") {
                                    $prod_price -= $selected_attribute['values_price'];
                                } elseif ($selected_attribute['prefix'] == "+") {
                                    $prod_price += $selected_attribute['values_price'];
                                }
                            }
                        }
                    }
                    // echo "hhhh";
                    // die;

                    $prod_final_price   = $prod_price * $line_item['quantity'];
                    // return $cart_products;
                    $cart_products[] = [
                        'products_id' => $product->products_id,
                        'products_name' => $product->products_name,
                        'products_price' => $prod_price,
                        'final_price' => $prod_final_price,
                        'products_tax' => 0.00,
                        'products_quantity' => $line_item['quantity'],
                        'attributes' => $line_item['attributes']
                    ];

                    $postpay_cart_products[] = [
                        'reference' => $product->products_id,
                        'name' => $product->products_name,
                        'unit_price' => (number_format($prod_price, 2)) * 100,
                        'qty' => $line_item['quantity']
                    ];
                    $products_total += $prod_final_price;
                    $total_qty += $line_item['quantity'];
                }
            }
            // return "hhhhh";
            // return $product;

            $tax_rate           = Config::get('global.p_constant');
            $total_tax          = (float) ($products_total * $tax_rate);
            $coupon_discount    = 0.00;

            if (isset($coupon_amount) and !empty($coupon_amount)) {

                $coupon_discount    = $coupon_amount;
            }

            $order_price        = ($products_total + $total_tax + $shipping_price) - $coupon_discount;
            $shipping_cost      = $shipping_price;
            $shipping_method    = $shipping_lines[0]['method_title'];
            $orders_status      = '1';
            $comments           = '';

            $web_setting = DB::table('settings')->get();
            $currency = $web_setting[19]->value;

            $products_tax = 1;
            $code = '';
            // return "hhhhh";
            if (isset($coupon_code) and !empty($coupon_code)) {
                // echo "hhhh";
                // die;
                $currentDate    =   date('Y-m-d 00:00:00', time());
                $coupon_data    =   DB::table('coupons')->where([
                    ['code', '=', $coupon_code],
                ]);
                $coupons = $coupon_data->get();
                $code = json_encode($coupons);
            }
            $payment_method = $payment_method_title;
            $payment_status = 'success';
            // return "hhhhh";
            if ($payment_status == 'success') {
                // echo "hhhh";
                // die;
                $orders_id = DB::table('orders')->insertGetId([
                    'customers_id' => $customers_id,
                    'customers_name' => $delivery_firstname . ' ' . $delivery_lastname,
                    'customers_street_address' => $delivery_street_address,
                    'customers_suburb' => $delivery_suburb,
                    'customers_city' => $delivery_city,
                    'customers_postcode' => $delivery_postcode ?? "",
                    'customers_state' => $delivery_state,
                    'customers_country' => $delivery_country,
                    'email' => $email,
                    'delivery_name' => $delivery_firstname . ' ' . $delivery_lastname,
                    'delivery_street_address' => $delivery_street_address,
                    'delivery_suburb' => $delivery_suburb,
                    'delivery_city' => $delivery_city,
                    'delivery_postcode' => $delivery_postcode ?? "",
                    'delivery_state' => $delivery_state,
                    'delivery_country' => $delivery_country,
                    'billing_name' => $billing_firstname . ' ' . $billing_lastname,
                    'billing_street_address' => $billing_street_address,
                    'billing_suburb' => $billing_suburb,
                    'billing_city' => $billing_city,
                    'billing_postcode' => $billing_postcode ?? "",
                    'billing_state' => $billing_state,
                    'billing_country' => $billing_country,
                    'payment_method' => $payment_method,
                    'cc_type' => $cc_type,
                    'cc_owner' => $cc_owner,
                    'cc_number' => $cc_number,
                    'cc_expires' => $cc_expires,
                    'last_modified' => $last_modified,
                    'date_purchased' => $date_purchased,
                    'order_price' => $order_price,
                    'shipping_cost' => $shipping_cost,
                    'shipping_method' => $shipping_method,
                    'currency' => $currency,
                    'order_information' => json_encode($order_information),
                    'coupon_code' => $code,
                    'coupon_amount' => $coupon_amount,
                    'total_tax' => $total_tax,
                    'ordered_source' => $ordered_source,
                    'delivery_phone' => $delivery_phone,
                    'billing_phone' => $billing_phone,
                    'pickup_store_id' => $pickup_from_store_id
                ]);

                //orders status history
                $orders_history_id = DB::table('orders_status_history')->insertGetId([
                    'orders_id' => $orders_id,
                    'orders_status_id' => $orders_status,
                    'date_added' => $date_added,
                    'customer_notified' => '1',
                    'comments' => $comments
                ]);
                // return $cart_products;
                foreach ($cart_products as $products) {
                    //get products info
                    $orders_products_id = DB::table('orders_products')->insertGetId([
                        'orders_id' => $orders_id,
                        'products_id' => $products['products_id'],
                        'products_name' => $products['products_name'],
                        'products_price' => $products['products_price'],
                        'final_price' => $products['final_price'],
                        'products_tax' => $products['products_tax'],
                        'products_quantity' => $products['products_quantity']
                    ]);

                    $inventory_ref_id = DB::table('inventory')->insertGetId([
                        'products_id' => $products['products_id'],
                        'reference_code' => '',
                        'stock' => $products['products_quantity'],
                        'admin_id' => 0,
                        'added_date' => time(),
                        'purchase_price' => 0,
                        'stock_type' => 'out',
                    ]);
                    // return "hhhhh";
                    if (isset($products['attributes']) and !empty($products['attributes'])) {
                        // return "hhhhh";
                        foreach ($products['attributes'] as $attribute) {
                            DB::table('orders_products_attributes')->insert(
                                [
                                    'orders_id'                => $orders_id,
                                    'products_id'              => $products['products_id'],
                                    'orders_products_id'       => $orders_products_id,
                                    'products_options'         => $attribute['attribute_name'],
                                    'products_options_values'  => $attribute['attribute_value'],
                                    'options_values_price'     => $attribute['values_price'],
                                    'price_prefix'             => $attribute['prefix']
                                ]
                            );
                            // return "hhhhh";
                            DB::table('inventory_detail')->insert([
                                'inventory_ref_id' => $inventory_ref_id,
                                'products_id'      => $products['products_id'],
                                'attribute_id'        => $db_attributes->products_attributes_id ?? 0,
                            ]);
                        }
                    }
                }
                // return "hhhhh";
                if ($request->is_installment == 0) {
                    // echo "hhh";
                    // die;
                    try {
                        $payload = array(
                            "order_id" => $orders_id,
                            "total_amount" => (number_format($order_price, 2)) * 100,
                            "tax_amount" => (number_format($total_tax, 2)) * 100,
                            "currency" => "AED",
                            "num_instalments" => 1
                        );

                        $payload['customer'] = array(
                            "id" => $customers_id,
                            "email" => $email,
                            "first_name" => $delivery_firstname . ' ' . $delivery_lastname
                        );
                        // return $cart;

                        $payload['items'] = $postpay_cart_products;

                        $payload['merchant'] = array(
                            "confirmation_url" => "https://pd.artperfumeco.com/mobileSuccess",
                            "cancel_url" => "https://pd.artperfumeco.com/cancel"
                        );
                        // return "hhhhh";
                        // return $payload;
                        $response = config('postpayconfig.postpay')->post('/checkouts', $payload);
                    } catch (RESTfulException $e) {
                        // return $e;
                        dd($e);
                        exit;
                    }
                    $redirect = $response->json()['redirect_url'];
                }
                if ($request->is_installment == 1) {
                    // echo "llll";
                    // die;
                    try {
                        $payload = array(
                            "order_id" => $orders_id,
                            "total_amount" => (number_format($order_price, 2)) * 100,
                            "tax_amount" => (number_format($total_tax, 2)) * 100,
                            "currency" => "AED"
                        );

                        $payload['customer'] = array(
                            "id" => $customers_id,
                            "email" => $email,
                            "first_name" => $delivery_firstname . ' ' . $delivery_lastname
                        );
                        // return $cart;

                        $payload['items'] = $postpay_cart_products;

                        $payload['merchant'] = array(
                            "confirmation_url" => "https://pd.artperfumeco.com/mobileSuccess",
                            "cancel_url" => "https://pd.artperfumeco.com/cancel"
                        );

                        // return $payload;
                        $response = config('postpayconfig.postpay')->post('/checkouts', $payload);
                    } catch (RESTfulException $e) {
                        // return $e;
                        dd($e);
                        exit;
                    }
                    $redirect = $response->json()['redirect_url'];
                }

                //notification/email
                try {
                    // return "hhhhh";
                    $order = DB::table('orders')
                        ->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
                        ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
                        ->where('orders.orders_id', '=', $orders_id)->orderby('orders_status_history.date_added', 'DESC')->get();
                    foreach ($order as $data) {
                        $orders_id     = $data->orders_id;
                        $orders_products = DB::table('orders_products')
                            ->join('products', 'products.products_id', '=', 'orders_products.products_id')
                            ->select('orders_products.*', 'products.products_image as image')
                            ->where('orders_products.orders_id', '=', $orders_id)->get();
                        $i              = 0;
                        $total_price    = 0;
                        $product        = array();
                        $subtotal       = 0;
                        foreach ($orders_products as $orders_products_data) {
                            $product_attribute = DB::table('orders_products_attributes')
                                ->where([
                                    ['orders_products_id', '=', $orders_products_data->orders_products_id],
                                    ['orders_id', '=', $orders_products_data->orders_id],
                                ])
                                ->get();
                            $orders_products_data->attribute = $product_attribute;
                            $product[$i] = $orders_products_data;
                            //$total_tax	 = $total_tax+$orders_products_data->products_tax;
                            $total_price = $total_price + $orders_products[$i]->final_price;
                            $subtotal += $orders_products[$i]->final_price;
                            $i++;
                        }
                        $data->data = $product;
                        $orders_data[] = $data;
                    }

                    $orders_status_history = DB::table('orders_status_history')
                        ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
                        ->orderBy('orders_status_history.date_added', 'desc')
                        ->where('orders_id', '=', $orders_id)->get();

                    $orders_status = DB::table('orders_status')->get();

                    $ordersData['orders_data']                  =    $orders_data;
                    $ordersData['total_price']              =    $total_price;
                    $ordersData['orders_status']            =    $orders_status;
                    $ordersData['orders_status_history']    =    $orders_status_history;
                    $ordersData['subtotal']                    =    $subtotal;

                    $myVar = new AlertController();
                    $myVar->orderAlert($ordersData);
                } catch (\Exception $ex) {
                }

                $response = [
                    'status' => 1,
                    'message' => 'success',
                    'redirect_url' => $redirect,
                    'confirmation_url' => "https://pd.artperfumeco.com/mobileSuccess",
                    'cancel_url' => "https://pd.artperfumeco.com/cancel",
                ];
            }
        } catch (\Exception $exception) {
            $response['status']     = 0;
            $response['message']    = $exception->getMessage();
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    function my_orders()
    {
        if (Auth::check()) {
            $result         = array();
            $total_price    = array();
            $language_id    = 1;
            $authUser       = Auth::user();
            $orders         = DB::table('orders')->orderBy('date_purchased', 'DESC')
                ->where('customers_id', '=', $authUser->id)
                ->get();

            foreach ($orders as $index => $orders_data) {
                $orders_products = DB::table('orders_products')
                    ->select('final_price', DB::raw('SUM(final_price) as total_price'))
                    ->where('orders_id', '=', $orders_data->orders_id)
                    ->get();

                $orders[$index]->total_price = $orders_products[0]->total_price;

                $orders_status_history = DB::table('orders_status_history')
                    ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
                    ->LeftJoin('orders_status_description', 'orders_status_description.orders_status_id', '=', 'orders_status.orders_status_id')
                    ->select('orders_status_description.orders_status_name', 'orders_status.orders_status_id')
                    ->where('orders_id', '=', $orders_data->orders_id)
                    ->where('orders_status_description.language_id', $language_id)
                    ->orderby('orders_status_history.orders_status_history_id', 'DESC')
                    ->limit(1)
                    ->get();

                $orders[$index]->orders_status_id = $orders_status_history[0]->orders_status_id;
                $orders[$index]->orders_status = $orders_status_history[0]->orders_status_name;

                list($billing_firstname, $billing_lastname) = explode(" ", $orders_data->billing_name);
                list($shipping_firstname, $shipping_lastname) = explode(" ", $orders_data->delivery_name);

                $product_image      = '';
                $line_items         = [];
                $line_items_data    = DB::table('orders_products')
                    ->where('orders_id', '=', $orders_data->orders_id)
                    ->get();

                foreach ($line_items_data as $index => $line_items_datum) {

                    $product_image_data  = DB::table('products')
                        ->select('products.products_id AS id', 'products_description.products_name AS name', 'image_categories.path as image')
                        ->leftJoin('products_description', 'products_description.products_id', '=', 'products.products_id')
                        ->LeftJoin('image_categories', 'products.products_image', '=', 'image_categories.image_id')
                        ->where('products_description.language_id', '=', $language_id)
                        ->where('products.products_id', $line_items_datum->products_id)
                        ->groupBy('products.products_id')
                        ->orderBy('products.products_id', 'DESC')
                        ->first();

                    if ($product_image_data) {
                        $product_image = asset($product_image_data->image);
                    }

                    $line_items[$index] = [
                        "id" => $line_items_datum->products_id,
                        "name" => $line_items_datum->products_name,
                        "product_id" => $line_items_datum->products_id,
                        "variation_id" => $line_items_datum->products_id,
                        "quantity" => $line_items_datum->products_quantity,
                        "tax_class" => "",
                        "subtotal" => $line_items_datum->final_price,
                        "subtotal_tax" => $line_items_datum->products_tax,
                        "total" => $line_items_datum->final_price,
                        "total_tax" => $line_items_datum->products_tax,
                        "taxes" => [
                            "id" => 1,
                            "total" => $line_items_datum->products_tax,
                            "subtotal" => $line_items_datum->products_tax,
                        ],
                        "meta_data" => [],
                        "sku" => "",
                        "price" => $line_items_datum->products_price,
                        "product_image" => $product_image
                    ];
                }


                $result[] = [
                    "id" => $orders_data->orders_id,
                    "parent_id" => 0,
                    "number" => $orders_data->orders_id,
                    "order_key" => $orders_data->orders_id,
                    "created_via" => "checkout",
                    "version" => "3.8",
                    "status" => $orders_status_history[0]->orders_status_name,
                    "currency" => $orders_data->currency,
                    "date_created" => $orders_data->date_purchased,
                    "date_created_gmt" => $orders_data->date_purchased,
                    "date_modified" => $orders_data->last_modified,
                    "date_modified_gmt" => $orders_data->last_modified,
                    "discount_total" => 0.00,
                    "discount_tax" => 0.00,
                    "shipping_total" => $orders_data->shipping_cost,
                    "shipping_tax" => 0.00,
                    "cart_tax" => $orders_data->total_tax,
                    "total" => $orders_data->total_price,
                    "total_tax" => $orders_data->total_tax,
                    "prices_include_tax" => $orders_data->order_price,
                    "customer_id" => $orders_data->customers_id,
                    "customer_ip_address" => "",
                    "customer_user_agent" => "",
                    "customer_note" => "",
                    "billing" => [
                        "first_name" => $billing_firstname ?? "",
                        "last_name" => $billing_lastname ?? "",
                        "company" => $orders_data->billing_company,
                        "address_1" => $orders_data->billing_street_address,
                        "address_2" => "",
                        "city" => $orders_data->billing_city,
                        "state" => $orders_data->billing_state,
                        "postcode" => $orders_data->billing_postcode ?? "",
                        "country" => $orders_data->billing_country,
                        "email" => $orders_data->email,
                        "phone" => $orders_data->billing_phone
                    ],
                    "shipping" => [
                        "first_name" => $shipping_firstname ?? "",
                        "last_name" => $shipping_lastname ?? "",
                        "company" => $orders_data->delivery_company,
                        "address_1" => $orders_data->delivery_street_address,
                        "address_2" => "",
                        "city" => $orders_data->delivery_city,
                        "state" => $orders_data->delivery_state,
                        "postcode" => $orders_data->delivery_postcode,
                        "country" => $orders_data->delivery_country,
                        "email" => $orders_data->email,
                        "phone" => $orders_data->delivery_phone
                    ],
                    "payment_method" => $orders_data->payment_method,
                    "payment_method_title" => $orders_data->payment_method,
                    "transaction_id" => $orders_data->transaction_id,
                    "date_paid" => $orders_data->date_purchased,
                    "date_paid_gmt" => $orders_data->date_purchased,
                    "date_completed" => $orders_data->orders_date_finished,
                    "date_completed_gmt" => $orders_data->orders_date_finished,
                    "cart_hash" => $orders_data->orders_id,
                    "meta_data" => [],
                    "line_items" => $line_items,
                    "tax_lines" => [
                        "id" => "",
                        "rate_code" => "",
                        "rate_id" => "",
                        "label" => "",
                        "compound" => "",
                        "tax_total" => "",
                        "shipping_tax_total" => "",
                        "rate_percent" => "",
                        "meta_data" => [],
                    ],
                    "shipping_lines" => [
                        "id" => "",
                        "method_title" => "",
                        "method_id" => "",
                        "instance_id" => "",
                        "total" => "",
                        "total_tax" => "",
                        "taxes" => [
                            "id" => "",
                            "total" => "",
                            "subtotal" => ""
                        ],
                        "meta_data" => []
                    ],
                    "fee_lines" => [],
                    "coupon_lines" => [
                        "coupon_code"   => $orders_data->coupon_code,
                        "coupon_amount" => $orders_data->coupon_amount,
                    ],
                    "refunds" => [],
                    "order_tracking_data" => [],
                ];
            }
            return response()->json($result, 200);
        } else {
            $response = [
                'status'    => "error",
                'message'   => "Unauthorised.",
            ];
            return response()->json($response, 401);
        }
    }

    function verify_coupon(APIVerifyCouponRequest $request)
    {
        try {
            $language_id       = 1;
            $currentDate       = date('Y-m-d 00:00:00', time());
            $coupon            = $request->get('coupon');
            $response          = [
                'status' => 0,
                'message' => 'Invalid Coupon Code provided.',
            ];


            $coupon_data       =  DB::table('coupons')->where([
                ['code', '=', $coupon],
                ['expiry_date', '>', $currentDate],
            ])->first();

            if ($coupon_data) {
                if (!empty(auth()->guard('customer')->user()->email) and in_array(auth()->guard('customer')->user()->email, explode(',', $coupon_data->email_restrictions))) {
                    $response['status']     = 0;
                    $response['message']    = Lang::get("website.You are not allowed to use this coupon");
                } else {
                    if ($coupon_data->usage_limit > 0 and $coupon_data->usage_limit == $coupon_data->usage_count) {
                        $response['status']     = 0;
                        $response['message']    = Lang::get("website.This coupon has been reached to its maximum usage limit");
                    } else {
                        $response = [
                            'status' => 1,
                            'message' => 'Provided coupon code is valid.',
                            'data' => [
                                'id'                    => $coupon_data->coupans_id,
                                'code'                  => $coupon_data->code,
                                'discount_type'         => $coupon_data->discount_type,
                                'amount'                => $coupon_data->amount,
                                'expiry_date'           => $coupon_data->expiry_date,
                                'individual_use'        => $coupon_data->individual_use,
                                'free_shipping'         => $coupon_data->free_shipping,
                                'exclude_sale_items'    => $coupon_data->exclude_sale_items,
                                'minimum_amount'        => $coupon_data->minimum_amount,
                                'maximum_amount'        => $coupon_data->maximum_amount,
                                'product_ids'           => $coupon_data->product_ids,
                                'exclude_product_ids'   => $coupon_data->exclude_product_ids,
                                'usage_limit'           => $coupon_data->usage_limit,
                                'usage_limit_per_user'  => $coupon_data->usage_limit_per_user,
                                'product_categories'    => $coupon_data->product_categories,
                                'excluded_product_categories' => $coupon_data->excluded_product_categories
                            ]
                        ];
                    }
                }
            }
        } catch (\Exception $exception) {
            $response['status']     = 0;
            $response['message']    = $exception->getMessage();
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }
}
