<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Postpay\Exceptions\RESTfulException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Client\Response;
use Validator;
use Twilio\Rest\Client;

use DB;
//for password encryption or hash protected
use Hash;

//for authenitcate login data
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;

//for requesting a value
use Illuminate\Routing\Controller;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\ShippingAddressController;
use App\Http\Controllers\Web\AlertController;

//for Carbon a value
use Carbon;
use Session;
use Lang;
use App\Models\Web\Index;
use App\Models\Web\Languages;
use App\Models\Web\Products;
use App\Models\Web\Currency;
use App\Models\Web\Shipping;
use App\Models\Web\Cart;
use App\Models\Web\Order;


//email
use Illuminate\Support\Facades\Mail;

class PostPayController extends Controller
{



    public function postpayFullIntsallment(Request $request)
    {
        // return $request;
        $orderStatus = new Order;
        $orderStatus->place_order($request);
        $lastOrder = Order::where('customers_id', auth()->guard('customer')->user()->id)->orderBy('orders_id', 'desc')->first();
        // return $lastOrder->total_tax;
        try {
            $payload = array(
                "order_id" => $lastOrder->orders_id,
                "total_amount" => (number_format($request->amount, 2)) * 100,
                "tax_amount" => (number_format($lastOrder->total_tax, 2)) * 100,
                "currency" => "AED",
                "num_instalments" => 1
            );

            $payload['customer'] = array(
                "id" => $lastOrder->customers_id,
                "email" => $lastOrder->email,
                "first_name" => $lastOrder->customers_name
            );
            $cart = json_decode($request->productCart);
            // return $cart;

            $products = [];
            foreach ($cart as $product) {
                // return $product->final_price;

                $products[] = [
                    "reference" => $product->products_id,
                    "name" => $product->products_name,
                    "unit_price" => (number_format($product->final_price, 2)) * 100,
                    "qty" => $product->customers_basket_quantity,
                ];
            }
            $payload['items'] = $products;

            $payload['merchant'] = array(
                "confirmation_url" => "https://pd.artperfumeco.com/success",
                "cancel_url" => "https://pd.artperfumeco.com/cancel"
            );

            // return $payload;
            $response = config('postpayconfig.postpay')->post('/checkouts', $payload);

            $message = "Dear Waqar, We are working on your Order" . $lastOrder->orders_id . " It will be delivered within 48 hours.";
            $receiverNumber = " +923045488435";
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");

            $client = new Client(
                $account_sid,
                $auth_token
            );
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message
            ]);
        } catch (RESTfulException $e) {
            // return $e;
            dd($e);
            exit;
        }
        $redirect = $response->json()['redirect_url'];
        // var_dump($redirect);
        return redirect($redirect);
    }

    public function postpayIntsallments(Request $request)
    {
        // return $request;
        $orderStatus = new Order;
        $orderStatus->place_order($request);
        $lastOrder = Order::where('customers_id', auth()->guard('customer')->user()->id)->orderBy('orders_id', 'desc')->first();
        // return $request->amount;

        try {
            $payload = array(
                "order_id" => $lastOrder->orders_id,
                "total_amount" => (number_format($request->amount, 2)) * 100,
                "tax_amount" => (number_format($lastOrder->total_tax, 2)) * 100,
                "currency" => "AED"
            );

            $payload['customer'] = array(
                "id" => $lastOrder->customers_id,
                "email" => $lastOrder->email,
                "first_name" => $lastOrder->customers_name
            );
            $cart = json_decode($request->productCart);
            // return $cart;

            $products = [];
            foreach ($cart as $product) {
                // return $product->final_price;

                $products[] = [
                    "reference" => $product->products_id,
                    "name" => $product->products_name,
                    "unit_price" => (number_format($product->final_price, 2)) * 100,
                    "qty" => $product->customers_basket_quantity,
                ];
            }
            $payload['items'] = $products;

            $payload['merchant'] = array(
                "confirmation_url" => "https://pd.artperfumeco.com/success",
                "cancel_url" => "https://pd.artperfumeco.com/cancel"
            );

            // return $payload;
            $response = config('postpayconfig.postpay')->post('/checkouts', $payload);

            $message = "Dear Waqar, We are working on your Order" . $lastOrder->orders_id . " It will be delivered within 48 hours.";
            $receiverNumber = " +923045488435";
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");

            $client = new Client(
                $account_sid,
                $auth_token
            );
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message
            ]);
        } catch (RESTfulException $e) {
            // return $e;
            dd($e);
            exit;
        }
        $redirect = $response->json()['redirect_url'];
        // return $redirect;
        return redirect($redirect);
    }

    public function postpayConfirmOrder(Request $request)
    {
        // return $request->order_id;
        // return view('confirmorder');
        try {
            $response = config('postpayconfig.postpay')->post('/orders/' . $request->order_id . '/capture');
        } catch (RESTfulException $e) {
            // dd('abc');
            dd($e);
            exit;
        }

        $orderStatus = Order::where('orders_id', $request->order_id)->first();
        $orderStatus->payment_status = 1;
        $orderStatus->save();

        return redirect('orders')->with('success', Lang::get("website.Payment has been processed successfully"));
        // dd('abc');
    }

    public function postpayMobileConfirmOrder(Request $request)
    {
        // return $request->order_id;
        // return view('confirmorder');
        try {
            $response = config('postpayconfig.postpay')->post('/orders/' . $request->order_id . '/capture');
        } catch (RESTfulException $e) {
            // dd('abc');
            dd($e);
            exit;
        }

        $orderStatus = Order::where('orders_id', $request->order_id)->first();
        $orderStatus->payment_status = 1;
        $orderStatus->save();

        return "website.Payment has been processed successfully";
        // dd('abc');
    }

    public function postpayOrderCancelled(Request $request)
    {

        return redirect()->back()->with('error', Lang::get("website.Error while placing order"));
        // dd('abc');
    }
}
