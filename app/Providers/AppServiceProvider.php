<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(empty(session()->has('locale'))) {
            session()->put('locale','en');
        }
        if(empty(session()->has('language_id'))) {
            session()->put('language_id',1);
        }
        Schema::defaultStringLength(191);

        /*if(file_exists(storage_path('installed'))){*/
        $result = array();
        $orders = DB::table('orders')
            ->leftJoin('customers','customers.customers_id','=','orders.customers_id')
            ->where('orders.is_seen','=', 0)
            ->orderBy('orders_id','desc')
            ->get();

        $index = 0;
        foreach($orders as $orders_data){

            array_push($result,$orders_data);
            $orders_products = DB::table('orders_products')
                ->where('orders_id', '=' ,$orders_data->orders_id)
                ->get();

            $result[$index]->price = $orders_products;
            $result[$index]->total_products = count($orders_products);
            $index++;
        }

        //new customers
        $newCustomers = DB::table('users')
            ->where('is_seen','=', 0)
            ->orderBy('id','desc')
            ->get();

        //products low in quantity
        $lowInQunatity = DB::table('products')
            ->LeftJoin('products_description', 'products_description.products_id', '=', 'products.products_id')
            ->whereColumn('products.products_quantity', '<=', 'products.low_limit')
            ->where('products_description.language_id', '=', '1')
            ->where('products.low_limit', '>', 0)
            //->get();
            ->paginate(10);

        $languages = DB::table('languages')->get();
        view()->share('languages', $languages);

        $web_setting = DB::table('settings')->get();
        view()->share('web_setting', $web_setting);

        view()->share('unseenOrders', $result);
        view()->share('newCustomers', $newCustomers);
        view()->share('lowInQunatity', $lowInQunatity);

        $postpay=new \Postpay\Postpay([
            'merchant_id' => 'id_7fa0b0e9b2ad4972aeb81407ffdc49d9',
            'secret_key' => 'sk_test_45f2b26029c2996d00773eb8c39451939cb927dc',
        ]);
        config(['postpayconfig.postpay' => $postpay]);
    }
}
