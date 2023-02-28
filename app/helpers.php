<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

/*
 * Returns country ID by ISO.
 * */
if(!function_exists('get_country_id_by_iso')) {
    function get_country_id_by_iso($iso) {
        $iso_upper      = Str::upper($iso);
        $country_name   = "";
        $country = \App\Models\Core\Countries::where(function ($query) use ($iso_upper) {
            $query->where('countries_iso_code_2', '=', $iso_upper)->orWhere('countries_iso_code_3', '=', $iso_upper);
        })->first();
        if($country) {
            $country_name = $country->countries_id;
        }
        return $country_name;
    }
}

/*
 * Formats numeric values (Prices)
 * */
if(!function_exists('pd_format_number')) {
    function pd_format_number($number, $decimals = 2) {
        return number_format($number,$decimals);
    }
}

/*
 * Adjust prices to nearest .25
 * */
if(!function_exists('pd_adjust_price')) {
    function pd_adjust_price($price) {
        return round($price*4, 0, PHP_ROUND_HALF_UP)/4;
    }
}

/*
 * Add tax and Adjust prices to nearest .25
 * */
if(!function_exists('pd_adjust_price_with_tax')) {
    function pd_adjust_price_with_tax($price) {
        $tax_rate       = Config::get('global.p_constant');
        $tax            = ($price * $tax_rate);
        $price          = $price + $tax;
        return round($price*4, 0, PHP_ROUND_HALF_UP)/4;
    }
}
