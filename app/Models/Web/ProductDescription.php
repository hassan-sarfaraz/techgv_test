<?php

namespace App\Models\Web;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;


class ProductDescription extends Model
{
	protected $table = 'products_description';

}
