<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Web\Order;
use App\Models\Core\Payments_setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ShippingAPIController extends BaseController
{

    function __construct(Order $order)
    {
        $this->order = $order;
    }

    function index(Request $request)
    {
        $language_id                     = 1;
        $cart_total                      = $request->get('cart_total');
        $country_code                    = $request->get('country_code');
        $shipping_methods["shippings"]   = [];


        $shippings = $this->shipping_methods($language_id);

        foreach ($shippings as $shipping) {
            if ($shipping['success'] == 1) {
                $shipping_methods["shippings"][] = [
                    'id'        => $shipping['id'],
                    // 'name'      => $shipping['name'],
                    'name'       => 'Home Delivery',
                    'is_default' => $shipping['is_default'],
                    'charges'   => $shipping['services'][0]['rate'],
                    'currency'  => $shipping['services'][0]['currencyCode'],
                    // 'method'    => $shipping['services'][0]['shipping_method'],
                    'method'    => 'home delivery',
                    'vat'       => 0,
                ];
            }
        }

        return response()->json($shipping_methods, 200, [], JSON_PRETTY_PRINT);
    }

    //shipping methods
    private function shipping_methods($language_id = 1)
    {

        $result                = array();
        $countries_id       = '';
        $toPostalCode       = '';
        $toCity                = '';
        $toAddress            = '';
        $toCountry          = '';
        $zone_id            = '';
        $index              = '0';
        $total_weight       = '0';
        $products_weight    = $total_weight;
        $websiteURL         =  "https://" . $_SERVER['SERVER_NAME'] . '/';
        $replaceURL         = str_replace('getRate', '', $websiteURL);
        $requiredURL        = $replaceURL . 'app/ups/ups.php';

        //default shipping method
        $shippings = $this->order->getShippingMethods();
        $result     = array();
        $mainIndex  = 0;
        foreach ($shippings as $shipping_methods) {

            $shippings_detail = $this->order->getShippingDetail($shipping_methods);

            //ups shipping rate
            if ($shipping_methods->methods_type_link == 'upsShipping' and $shipping_methods->status == '1') {

                $result2        = array();
                $is_transaction = '0';
                $ups_shipping   = $this->order->getUpsShipping();

                //shipp from and all credentials
                $accessKey  = $ups_shipping[0]->access_key;
                $userId     = $ups_shipping[0]->user_name;
                $password     = $ups_shipping[0]->password;

                //ship from address
                $fromAddress        = $ups_shipping[0]->address_line_1;
                $fromPostalCode     = $ups_shipping[0]->post_code;
                $fromCity           = $ups_shipping[0]->city;
                $fromState          = $ups_shipping[0]->state;
                $fromCountry        = $ups_shipping[0]->country;

                //production or test mode
                if ($ups_shipping[0]->shippingEnvironment == 1) {             #production mode
                    $useIntegration = true;
                } else {
                    $useIntegration = false;                                #test mode
                }

                $serviceData = explode(',', $ups_shipping[0]->serviceType);

                $index = 0;
                foreach ($serviceData as $value) {
                    if ($value == "US_01") {
                        $name = Lang::get('website.Next Day Air');
                        $serviceTtype = "1DA";
                    } else if ($value == "US_02") {
                        $name = Lang::get('website.2nd Day Air');
                        $serviceTtype = "2DA";
                    } else if ($value == "US_03") {
                        $name = Lang::get('website.Ground');
                        $serviceTtype = "GND";
                    } else if ($value == "US_12") {
                        $name = Lang::get('website.3 Day Select');
                        $serviceTtype = "3DS";
                    } else if ($value == "US_13") {
                        $name = Lang::get('website.Next Day Air Saver');
                        $serviceTtype = "1DP";
                    } else if ($value == "US_14") {
                        $name = Lang::get('website.Next Day Air Early A.M.');
                        $serviceTtype = "1DM";
                    } else if ($value == "US_59") {
                        $name = Lang::get('website.2nd Day Air A.M.');
                        $serviceTtype = "2DM";
                    } else if ($value == "IN_07") {
                        $name = Lang::get('website.Worldwide Express');
                        $serviceTtype = "UPSWWE";
                    } else if ($value == "IN_08") {
                        $name = Lang::get('website.Worldwide Expedited');
                        $serviceTtype = "UPSWWX";
                    } else if ($value == "IN_11") {
                        $name = Lang::get('website.Standard');
                        $serviceTtype = "UPSSTD";
                    } else if ($value == "IN_54") {
                        $name = Lang::get('website.Worldwide Express Plus');
                        $serviceTtype = "UPSWWEXPP";
                    }

                    $some_data = array(
                        'access_key' => $accessKey,                          # UPS License Number
                        'user_name' => $userId,                                # UPS Username
                        'password' => $password,                             # UPS Password
                        'pickUpType' => '03',                                # Drop Off Location
                        'shipToPostalCode' => $toPostalCode,                 # Destination  Postal Code
                        'shipToCountryCode' => $toCountry,                    # Destination  Country
                        'shipFromPostalCode' => $fromPostalCode,             # Origin Postal Code
                        'shipFromCountryCode' => $fromCountry,                # Origin Country
                        'residentialIndicator' => 'IN',                     # Residence Shipping and for commercial shipping "COM"
                        'cServiceCodes' => $serviceTtype,                     # Sipping rate for UPS Ground
                        'packagingType' => '02',
                        'packageWeight' => $products_weight
                    );

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_URL, $requiredURL);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
                    $rate = curl_exec($curl);
                    curl_close($curl);

                    if (is_numeric($rate)) {
                        $success = array('success' => '1', 'message' => "Rate is returned.", 'id' => $shipping_methods->shipping_methods_id, 'name' => $shippings_detail[0]->name, 'is_default' => $shipping_methods->isDefault);
                        $result2[$index] = array('name' => $name, 'rate' => $rate, 'currencyCode' => 'USD', 'shipping_method' => 'upsShipping');
                        $index++;
                    } else {
                        $success = array('success' => '0', 'message' => "Selected regions are not supported for UPS shipping", 'name' => $shippings_detail[0]->name);
                    }
                    $success['services'] = $result2;
                }
                $result[$mainIndex] = $success;
                $mainIndex++;
            } else if ($shipping_methods->methods_type_link == 'flateRate' and $shipping_methods->status == '1') {
                $ups_shipping = $this->order->getUpsShippingRate();
                $data2 =  array('name' => $shippings_detail[0]->name, 'rate' => $ups_shipping[0]->flate_rate, 'currencyCode' => $ups_shipping[0]->currency, 'shipping_method' => 'flateRate');
                if (count($ups_shipping) > 0) {
                    $success = array('success' => '1', 'message' => "Rate is returned.", 'id' => $shipping_methods->shipping_methods_id, 'name' => $shippings_detail[0]->name, 'is_default' => $shipping_methods->isDefault);
                    $success['services'][0] = $data2;
                    $result[$mainIndex] = $success;
                    $mainIndex++;
                }
            } else if ($shipping_methods->methods_type_link == 'localPickup' and $shipping_methods->status == '1') {

                $data2 =  array('name' => $shippings_detail[0]->name, 'rate' => '0', 'currencyCode' => 'USD', 'shipping_method' => 'localPickup');
                $success = array('success' => '1', 'message' => "Rate is returned.", 'id' => $shipping_methods->shipping_methods_id, 'name' => $shippings_detail[0]->name, 'is_default' => $shipping_methods->isDefault);
                $success['services'][0] = $data2;
                $result[$mainIndex] = $success;
                $mainIndex++;
            } else if ($shipping_methods->methods_type_link == 'freeShipping'  and $shipping_methods->status == '1') {

                $data2 =  array('name' => $shippings_detail[0]->name, 'rate' => '0', 'currencyCode' => 'USD', 'shipping_method' => 'freeShipping');
                $success = array('success' => '1', 'message' => "Rate is returned.", 'id' => $shipping_methods->shipping_methods_id, 'name' => $shippings_detail[0]->name, 'is_default' => $shipping_methods->isDefault);
                $success['services'][0] = $data2;
                $result[$mainIndex] = $success;
                $mainIndex++;
            } else if ($shipping_methods->methods_type_link == 'shippingByWeight'  and $shipping_methods->status == '1') {

                $weight         = 0;
                $priceByWeight  = $this->order->priceByWeight($weight);

                if (!empty($priceByWeight) and count($priceByWeight) > 0) {
                    $price = $priceByWeight[0]->weight_price;
                } else {
                    $price = 0;
                }

                $data2      =  array('name' => $shippings_detail[0]->name, 'rate' => $price, 'currencyCode' => 'USD', 'shipping_method' => 'Shipping By Weight');
                $success    = array('success' => '1', 'message' => "Rate is returned.", 'id' => $shipping_methods->shipping_methods_id, 'name' => $shippings_detail[0]->name, 'is_default' => $shipping_methods->isDefault);
                $success['services'][0] = $data2;
                $result[$mainIndex] = $success;
                $mainIndex++;
            }
        }

        return $result;
    }
}
