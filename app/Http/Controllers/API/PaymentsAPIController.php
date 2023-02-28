<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Core\Payments_setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PaymentsAPIController extends BaseController
{

    function index(Request $request)
    {
        $language_id                            = 1;
        $shipping_id                            = $request->get('shipping_id');
        $country_code                           = $request->get('country_code');
        $is_store_pickup                        = $request->get('store_pickup', false);
        $payment_gateways["payment_gateways"]   = [];

        $payments = DB::table('payment_methods')
            ->leftjoin('payment_description', 'payment_description.payment_methods_id', '=', 'payment_methods.payment_methods_id')
            ->select('payment_description.name', 'payment_methods.payment_methods_id', 'payment_methods.environment', 'payment_methods.status', 'payment_methods.payment_method')
            ->where('language_id', $language_id)
            ->where('payment_methods.status', 1);

        if (isset($is_store_pickup) and $is_store_pickup  == true) {
            $payments = $payments->where('payment_methods.payment_methods_id', '=', '8');
        } else {
            $payments = $payments->where('payment_methods.payment_methods_id', '!=', '8');
        }
        $payments = $payments->get();
        // return $payments;
        foreach ($payments as $payment) {
            $image = '';
            $subTitle = "";
            $name = "";
            if (strtolower($payment->payment_method) == 'cash_on_delivery') {
                $image = asset('images/cash-on-delivery-icon-14.jpg');
                $name = "Pay on Delivery";
                $subTitle = "Cash or Card on delivery with service fee of AED 30. Card payment is subject to availability";
            }
            // else if (strtolower($payment->payment_method) == 'payd') {
            //     $image = asset('images/payd.png');
            // } 
            elseif (strtolower($payment->payment_method) == 'pay_in_installments') {
                $image = asset('images/postpay.jpg');
                $name = "Pay in 3 with Postpay";
                $subTitle = "Split your payment into 3 interest free installments No fees";
            } elseif (strtolower($payment->payment_method) == 'pay_with_credit_card') {
                $name = "Credit/Debit Card Payment";
                $subTitle = "We accept Visa or Mastercard";
            }
            $payment_gateways["payment_gateways"][] = [
                'id'        => $payment->payment_methods_id,
                'name'      => $name,
                'sub_title' => $subTitle,
                'image'     => $image,
                'type'      => $payment->payment_method,
                'charges'   => 0,
                'vat'       => 0,
            ];
        }

        return response()->json($payment_gateways, 200, [], JSON_PRETTY_PRINT);
    }
}
