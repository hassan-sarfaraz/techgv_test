<?php

namespace App\Models\Web;

use App\Models\Core\Manufacturers;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Core\Categories;
use App\Models\Web\Cart;
use Illuminate\Support\Facades\Lang;
use App\Models\Web\News;
use Session;


class Index extends Model
{
  public function slides($currentDate){
    $slides = DB::table('sliders_images')
    	 ->leftJoin('image_categories','sliders_images.sliders_image','=','image_categories.image_id')
    	 ->select('sliders_id as id',
    						'sliders_title as title',
    						'sliders_url as url',
    						 'sliders_image as image',
    						 'type',
    						 'sliders_title as title',
    						 'image_categories.path'
    						 )
    	 ->where('status', '=', '1')
    	 ->where('languages_id', '=', session('language_id'))
       ->orwhere('status', '=', '1')
       ->where('languages_id', '=', 1)
    	 ->get();
       return $slides;
  }

  public function slidesByCarousel($currentDate,$carousel_id){
    $slides = DB::table('sliders_images')
			 ->leftJoin('image_categories','sliders_images.sliders_image','=','image_categories.image_id')
			 ->select('sliders_id as id',
								'sliders_title as title',
								'sliders_url as url',
								 'sliders_image as image',
								 'type',
								 'sliders_title as title',
								 'image_categories.path'
								 )
				->where('status', '=', '1')
			 ->where('carousel_id', '=', $carousel_id)
			 ->where('languages_id', '=', session('language_id'))

       ->orwhere('status', '=', '1')
       ->where('carousel_id', '=', $carousel_id)
       ->where('languages_id', '=', 1)
			 ->groupBy('sliders_images.sliders_id')
			 ->get();
       return $slides;
  }

  public function compareCount(){
    $count	= DB::table('compare')->where('customer_id',auth()->guard('customer')->user()->id)->count();
    return $count;
  }

  public function finalTheme(){
    $data = DB::table('current_theme')->first();
    return $data;
  }

  public function commonContent(){
    		$languages = DB::table('languages')
    			 					->leftJoin('image_categories','languages.image','image_categories.image_id')
    			 					->select('languages.*','image_categories.path as image_path')
    								->where('languages.is_default','1')
    			 					->get();
        $currency = DB::table('currencies')
                         ->where('is_default',1)
                         ->where('is_current',1)
                         ->first();
        if(empty(Session::get('currency_id'))){
     			session(['currency_id' => $currency->id]);
     		}
        if(empty(Session::get('currency_title'))){
          session(['currency_title' => $currency->code]);
        }
        if(empty(Session::get('symbol_right')) && empty(Session::get('symbol_left'))){
          session(['symbol_right' => $currency->symbol_right]);
        }
        if(empty(Session::get('symbol_left')) && empty(Session::get('symbol_right'))){
          session(['symbol_left' => $currency->symbol_left]);
        }
        if(empty(Session::get('currency_code'))){
          session(['currency_code' => $currency->code]);
        }

    		if(empty(Session::get('language_id'))){
    			session(['language_id' => $languages[0]->languages_id]);
    		}
    		if(empty(Session::get('locale'))){
    			session(['locale' => $languages[0]->code]);
    		}
    		if(empty(Session::get('language_image'))){
    			session(['language_image' => $languages[0]->image_path]);
    		}
    		if(empty(Session::get('language_name'))){
    			session(['language_name' => $languages[0]->name]);
    		}


    		$result = array();


    		$data 		=	array();
    		$categories = DB::table('news_categories')
    			->LeftJoin('news_categories_description', 'news_categories_description.categories_id', '=', 'news_categories.categories_id')
    			->select('news_categories.categories_id as id',
    				 'news_categories.categories_image as image',
    				 'news_categories.news_categories_slug as slug',
    				 'news_categories_description.categories_name as name'
    				 )
    			->where('news_categories_description.language_id','=', Session::get('language_id'))->get();

    		if(count($categories)>0){
    			foreach($categories as $categories_data){
    				$categories_id = $categories_data->id;
    				$news = DB::table('news_categories')
    						->LeftJoin('news_to_news_categories', 'news_to_news_categories.categories_id', '=', 'news_categories.categories_id')
    						->LeftJoin('news', 'news.news_id', '=', 'news_to_news_categories.news_id')
    						->select('news_categories.categories_id', DB::raw('COUNT(DISTINCT news.news_id) as total_news'))
    						->where('news_categories.categories_id','=', $categories_id)
    						->get();

    				$categories_data->total_news = $news[0]->total_news;
    				array_push($data,$categories_data);
    			}
    		}
        $result['newsCategories'] = $data;

        $myVar 		  = new News();
        $data 		  = array('page_number'=>0, 'type'=>'', 'is_feature'=>'1', 'limit'=>5, 'categories_id'=>'', 'load_news'=>0);
        $featuredNews = $myVar->getAllNews($data);
        $result['featuredNews'] = $featuredNews;
        $data = array('type'=>'header');
        $cart = $this->cart($data);
    		$result['cart'] = $cart;
    		if(count($result['cart'])==0){
    			session(['step' => '0']);
    			session(['coupon' => array()]);
    			session(['coupon_discount' => array()]);
    			session(['billing_address' => array()]);
    			session(['shipping_detail' => array()]);
    			session(['payment_method' => array()]);
    			session(['braintree_token' => array()]);
    			session(['order_comments' => '']);
    		}

    		$result['setting'] = DB::table('settings')->get();

        //home banners

    		$homeBanners = DB::table('constant_banners')
        ->leftJoin('image_categories','constant_banners.banners_image','=','image_categories.image_id')
        ->select('constant_banners.*','image_categories.path')
    		->where('languages_id', session('language_id'))
        ->orwhere('languages_id', 1)
        ->groupBy('constant_banners.banners_id')
    		->orderby('type','ASC')
    		->get();
    		$result['homeBanners'] = $homeBanners;

    		$result['pages'] = DB::table('pages')
    							->leftJoin('pages_description', 'pages_description.page_id', '=', 'pages.page_id')
    							->where([['type','2'],['status','1'],['pages_description.language_id',session('language_id')]])
                  ->orwhere([['type','2'],['status','1'],['pages_description.language_id',1]])->orderBy('pages_description.name', 'ASC')->get();

        //produt categories
        $result['categories'] = $this->categories();
        $result['brands'] = $this->brands();
    		return ($result);
  }

  private function brands(){

      $manufacturers =  Manufacturers::sortable(['manufacturers_id'=>'desc'])->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
          ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
          ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
          ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
          ->where('manufacturers_info.languages_id', '1')->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL')->paginate(4);


      return $manufacturers;
  }

  private function categories(){

    $result 	= 	array();

    $categories = DB::table('categories')
      ->LeftJoin('categories_description', 'categories_description.categories_id', '=', 'categories.categories_id')
      ->leftJoin('image_categories','categories.categories_image','=','image_categories.image_id')
      ->select('categories.categories_id as id',
         'categories.categories_image as image',
         'categories.categories_icon as icon',
         'categories.sort_order as order',
         'categories.categories_slug as slug',
         'categories.parent_id',
         'categories_description.categories_name as name',
         'image_categories.path as path'
         )
      ->where('categories_description.language_id','=', Session::get('language_id'))
      ->where('parent_id','0')
      ->groupBy('categories.categories_id')
      ->get();

    $index = 0;
    foreach($categories as $categories_data){
      $categories_id = $categories_data->id;

      $products = DB::table('categories')
          ->LeftJoin('categories as sub_categories', 'sub_categories.parent_id', '=', 'categories.categories_id')
          ->LeftJoin('products_to_categories', 'products_to_categories.categories_id', '=', 'sub_categories.categories_id')
          ->LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
          ->select('categories.categories_id', DB::raw('COUNT(DISTINCT products.products_id) as total_products'))
          ->where('categories.categories_id','=', $categories_id)
          ->get();

      $categories_data->total_products = $products[0]->total_products;
      array_push($result,$categories_data);

      $sub_categories = DB::table('categories')
        ->LeftJoin('categories_description', 'categories_description.categories_id', '=', 'categories.categories_id')
        ->select('categories.categories_id as sub_id',
           'categories.categories_image as sub_image',
           'categories.categories_icon as sub_icon',
           'categories.sort_order as sub_order',
          'categories.categories_slug as sub_slug',
           'categories.parent_id',
           'categories_description.categories_name as sub_name'
           )
        ->where('categories_description.language_id','=', Session::get('language_id'))
        ->where('parent_id',$categories_id)
        ->get();

      $data = array();
      $index2 = 0;
      foreach($sub_categories as $sub_categories_data){
        $sub_categories_id = $sub_categories_data->sub_id;

        $individual_products = DB::table('products_to_categories')
          ->LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
          ->select('products_to_categories.categories_id', DB::raw('COUNT(DISTINCT products.products_id) as total_products'))
          ->where('products_to_categories.categories_id','=', $sub_categories_id)
          ->get();

        $sub_categories_data->total_products = $individual_products[0]->total_products;
        $data[$index2++] = $sub_categories_data;

      }

      $result[$index++]->sub_categories = $data;

    }
    return($result);

  }

  public function cart($request){

    $cart = DB::table('customers_basket')
    ->join('products', 'products.products_id','=', 'customers_basket.products_id')
    ->join('products_description', 'products_description.products_id','=', 'products.products_id')
    ->LeftJoin('image_categories', function ($join) {
        $join->on('image_categories.image_id', '=', 'products.products_image')
            ->where(function ($query) {
                $query->where('image_categories.image_type', '=', 'THUMBNAIL')
                    ->where('image_categories.image_type', '!=', 'THUMBNAIL')
                    ->orWhere('image_categories.image_type', '=', 'ACTUAL');
            });
    })
    ->select('customers_basket.*', 'products.products_model as model', 'image_categories.path as image', 'products_description.products_name as products_name', 'products.products_quantity as quantity', 'products.products_price as price', 'products.products_weight as weight', 'products.products_weight_unit as unit' )->where('customers_basket.is_order', '=', '0')->where('products_description.language_id','=', Session::get('language_id') );

      if(empty(session('customers_id'))){
        $cart->where('customers_basket.session_id', '=', Session::getId());
      }else{
        $cart->where('customers_basket.customers_id', '=', session('customers_id'));
      }

    $baskit = $cart->get();
    return($baskit);

  }
}
