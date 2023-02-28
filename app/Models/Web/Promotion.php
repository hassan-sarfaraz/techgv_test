<?php

namespace App\Models\Web;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;


class Promotion extends Model
{

  public function get_promotion_product(){
    return $this->belongsTo('App\Models\Web\Products','product_id_from','products_id');
  }

  public function get_promotion_product_details(){
    return $this->belongsTo('App\Models\Web\ProductDescription','product_id_from','products_id');
  }

}
