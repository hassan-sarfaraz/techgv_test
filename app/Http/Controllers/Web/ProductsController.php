<?php
namespace App\Http\Controllers\Web;
//validator is builtin class in laravel
use Validator;

use DB;
//for password encryption or hash protected
use Hash;

//for authenitcate login data
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;

//for requesting a value
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
//for Carbon a value
use Carbon;
use Session;
use Lang;
use App\Models\Web\Index;
use App\Models\Web\Languages;
use App\Models\Web\Products;
use App\Models\Web\Currency;
//email
use Illuminate\Support\Facades\Mail;
use App\Models\Web\Cart;

class ProductsController extends Controller
{
	// call Constructor
	public function __construct(
								Index $index,
								Languages $languages,
								Products $products,
								Currency $currency,
                                Cart $cart
							)
	{
        $this->cart = $cart;
		$this->index = $index;
		$this->languages = $languages;
		$this->products = $products;
		$this->currencies = $currency;
		$this->theme = new ThemeController();
	}

	//shop
	public function shop(Request $request){
		$title = array('pageTitle' => Lang::get('website.Shop'));
		$result = array();
		$result['commonContent'] = $this->index->commonContent();
		$final_theme = $this->theme->theme();



		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Filters <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		// Pagination
		if(!empty($request->page)){
			$page_number = $request->page;
		}else{
			$page_number = 0;
		}

		// Limit
		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}

		if(!empty($request->type)){
			$type = $request->type;
		}else{
			$type = '';
		}

		// MAX/MIN Price
		if(!empty($request->price)){
			$d = explode(";",$request->price);
			$min_price = $d[0];
			$max_price = $d[1];
		}else{
			$min_price = '';
			$max_price = '';
		}

		// Category if Single exist
		if(!empty($request->category) and $request->category!='all'){

			// Joins Category with Category Description
			$category = $this->products->getCategories($request);

			$categories_id = $category[0]->categories_id;

			//for main
			if($category[0]->parent_id==0){
				$category_name = $category[0]->categories_name;
				$sub_category_name = '';
				$category_slug = '';
			}else{
			//for sub
				$main_category = $this->products->getMainCategories($category[0]->parent_id);

				$category_slug = $main_category[0]->categories_slug;
				$category_name = $main_category[0]->categories_name;
				$sub_category_name = $category[0]->categories_name;
			}

		}else{
			$categories_id = '';
			$category_name = '';
			$sub_category_name = '';
			$category_slug = '';
		}

		$result['category_name'] = $category_name;
		$result['category_slug'] = $category_slug;
		$result['sub_category_name'] = $sub_category_name;

		//search value
		if(!empty($request->search)){
			$search = $request->search;
		}else{
			$search = '';
		}


		$filters = array();


		// All Products Options getting
		if(!empty($request->filters_applied) and $request->filters_applied==1){
			$index = 0;
			$options = array();
			$option_values = array();

			$option = $this->products->getOptions();
			// Iterate all product option name and replace space with _
			foreach($option as $key=>$options_data){
				$option_name = str_replace(' ','_',$options_data->products_options_name);
				if(!empty($request->$option_name)){
					$index2 = 0;
					$values = array();
					foreach($request->$option_name as $value)
					{
						$value = $this->products->getOptionsValues($value);
						array_push($option_values, $value[0]->products_options_values_id);
					}
					array_push($options, $options_data->products_options_id);
				}
			}

			// Exception Handling
			try {
				$filters['options_count'] = count($options);
				$filters['options'] = implode(',', $options);
				$filters['option_value'] = implode(',', $option_values);

				// if( !empty($filters -> filter_attribute) ){
					$filters['filter_attribute']['options'] = $options;
					$filters['filter_attribute']['option_values'] = $option_values;

					$result['filter_attribute']['options'] = $options;
					$result['filter_attribute']['option_values'] = $option_values;
				// }
			} catch(\Exception $e){
				$msg = $e->getMessage();

				// return redirect('general_error/'.$msg);
			}
		}

		$data = array('page_number'=>$page_number, 'type'=>$type, 'limit'=>$limit, 'categories_id'=>$categories_id, 'search'=>$search, 'filters'=>$filters, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );

		$products = $this->products->products($data);
		$result['products'] = $products;

		$data = array('limit'=>$limit, 'categories_id'=>$categories_id );
		$filters = $this->filters($data);
		$result['filters'] = $filters;

		$cart = '';
		$result['cartArray'] = $this->products->cartIdArray($cart);

		if($limit > $result['products']['total_record']){
			$result['limit'] = $result['products']['total_record'];
		}else{
			$result['limit'] = $limit;
		}

		//liked products
		$result['liked_products'] = $this->products->likedProducts();
		$result['categories'] = $this->products->categories();

		$result['min_price'] = $min_price;
		$result['max_price'] = $max_price;

		return view("web.shop", ['title' => $title,'final_theme' => $final_theme])->with('result', $result);

	}

	public function filterProducts(Request $request){

		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}

		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}

		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}

		if(!empty($request->type)){
			$type = $request->type;
		}else{
			$type = '';
		}

		//if(!empty($request->category_id)){
		if(!empty($request->category) and $request->category!='all'){
			$category = DB::table('categories')->leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->where('categories_slug',$request->category)->where('language_id',Session::get('language_id'))->get();

			$categories_id = $category[0]->categories_id;
		}else{
			$categories_id = '';
		}

		//search value
		if(!empty($request->search)){
			$search = $request->search;
		}else{
			$search = '';
		}

		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}

		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}

		if(!empty($request->filters_applied) and $request->filters_applied==1){
			$filters['options_count'] = count($request->options_value);
			$filters['options'] = $request->options;
			$filters['option_value'] = $request->options_value;
		}else{
			$filters = array();
		}


		$data = array('page_number'=>$request->page_number, 'type'=>$type, 'limit'=>$limit, 'categories_id'=>$categories_id, 'search'=>$search, 'filters'=>$filters, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$products = $this->products->products($data);
		$result['products'] = $products;

		$cart = '';
		$result['cartArray'] =  $this->products->cartIdArray($cart);
		$result['limit'] = $limit;
		return view("web.filterproducts")->with('result', $result);

	}

	public function ModalShow(Request $request){

				$title 			= 	array('pageTitle' => Lang::get('website.Product Detail'));
				$result 		= 	array();
				$result['commonContent'] = $this->index->commonContent();
				$final_theme = $this->theme->theme();
				//min_price
				if(!empty($request->min_price)){
					$min_price = $request->min_price;
				}else{
					$min_price = '';
				}

				//max_price
				if(!empty($request->max_price)){
					$max_price = $request->max_price;
				}else{
					$max_price = '';
				}

				if(!empty($request->limit)){
					$limit = $request->limit;
				}else{
					$limit = 15;
				}

				$products = $this->products->getProductsById($request->products_id);

				//category
				$category = $this->products->getCategoryByParent($products[0]->products_id);


				if(!empty($category)){
					$category_slug = $category[0]->categories_slug;
					$category_name = $category[0]->categories_name;
				}else{
					$category_slug = '';
					$category_name = '';
				}
				$sub_category = $this->products->getSubCategoryByParent($products[0]->products_id);

				if(!empty($sub_category) and count($sub_category)>0){
					$sub_category_name = $sub_category[0]->categories_name;
					$sub_category_slug = $sub_category[0]->categories_slug;
				}else{
					$sub_category_name = '';
					$sub_category_slug = '';
				}

				$result['category_name'] = $category_name;
				$result['category_slug'] = $category_slug;
				$result['sub_category_name'] = $sub_category_name;
				$result['sub_category_slug'] = $sub_category_slug;

				$isFlash = $this->products->getFlashSale($products[0]->products_id);


				if(!empty($isFlash) and count($isFlash)>0){
					$type = "flashsale";
				}else{
					$type = "";
				}

				$data = array('page_number'=>'0', 'type'=>$type, 'products_id'=>$products[0]->products_id, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price);
				$detail = $this->products->products($data);
				$result['detail'] = $detail;

				$i = 0;
				foreach($result['detail']['product_data'][0]->categories as $postCategory){
					if($i==0){
						$postCategoryId = $postCategory->categories_id;
						$i++;
					}
				}

				$data = array('page_number'=>'0', 'type'=>'', 'categories_id'=>$postCategoryId, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price);
				$simliar_products = $this->products->products($data);
				$result['simliar_products'] = $simliar_products;

				$cart = '';
				$result['cartArray'] = $this->products->cartIdArray($cart);

                $promotions = $this->cart->get_promotions($detail['product_data'][0]->products_id);
                $result['promotions'] = $promotions;

                if(!empty( $promotions[0]->product_id_from ) && $detail['product_data'][0]->products_id == $promotions[0]->product_id_from){
                    $result['has_promotion'] = true;
                }

				//liked products
				$result['liked_products'] = $this->products->likedProducts();
		return view("web.common.modal1")->with('result', $result);
	}

	//access object for custom pagination
	function accessObjectArray($var){
	  return $var;
	}

	//productDetail
	public function productDetail(Request $request){

		$title 			= 	array('pageTitle' => Lang::get('website.Product Detail'));
		$result 		= 	array();
		$result['commonContent'] = $this->index->commonContent();
		$final_theme = $this->theme->theme();
		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}

		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}

		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}

		$products = $this->products->getProductsBySlug($request->slug);

		//category
		$category = $this->products->getCategoryByParent($products[0]->products_id ?? 0);


		if(!empty($category) and isset($category[0])){
			$category_slug = $category[0]->categories_slug;
			$category_name = $category[0]->categories_name;
		}else{
			$category_slug = '';
			$category_name = '';
		}
		$sub_category = $this->products->getSubCategoryByParent($products[0]->products_id ?? 0);

		if(!empty($sub_category) and count($sub_category) > 0){
			$sub_category_name = $sub_category[0]->categories_name;
			$sub_category_slug = $sub_category[0]->categories_slug;
		}else{
			$sub_category_name = '';
			$sub_category_slug = '';
		}

		$result['category_name'] = $category_name;
		$result['category_slug'] = $category_slug;
		$result['sub_category_name'] = $sub_category_name;
		$result['sub_category_slug'] = $sub_category_slug;

		$isFlash = $this->products->getFlashSale($products[0]->products_id ?? 0);


		if(!empty($isFlash) and count($isFlash)>0){
			$type = "flashsale";
		}else{
			$type = "";
		}

		$data = array('page_number'=>'0', 'type'=>$type, 'products_id'=>$products[0]->products_id ?? 0, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price);
		$detail = $this->products->products($data);
		$result['detail'] = $detail;


		$i = 0;
		foreach($result['detail']['product_data'][0]->categories as $postCategory){
			if($i==0){
				$postCategoryId = $postCategory->categories_id;
				$i++;
			}
		}

		$data = array('page_number'=>'0', 'type'=>'', 'categories_id'=>$postCategoryId, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price);
		$simliar_products = $this->products->products($data);
		$result['simliar_products'] = $simliar_products;

		$cart = '';
		$result['cartArray'] = $this->products->cartIdArray($cart);

        $result['cart'] = $this->cart->myCart($data);

        $promotions = $this->cart->get_promotions($detail['product_data'][0]->products_id);
        $result['promotions'] = $promotions;

        foreach($promotions as $promotion){
            if(!empty( $promotion->product_id_from ) && $detail['product_data'][0]->products_id == $promotion->product_id_from){
                $result['has_promotion'] = true;
            }
        }

//        print_r( $result['promotions'] );exit;
		//liked products
		$result['liked_products'] = $this->products->likedProducts();
		return view("web.detail", ['title' => $title, 'final_theme' => $final_theme])->with('result', $result);
	}

	//filters
	public function filters($data){
    $response = $this->products->filters($data);
		return($response);
		}

	//getquantity
	public function getquantity(Request $request){
		$data = array();
		$data['products_id'] = $request->products_id;
		$data['attributes'] = $request->attributeid;

		$result = $this->products->productQuantity($data);
		print_r(json_encode($result));
	}

	public function ajaxsearch(Request $request) {
		$search_term 		= stripslashes(trim($request->get('query')));
        $language_id        = 1;
        $response           = [];
        $products           = DB::table('products')
                                ->select('products.products_id AS id', 'products_description.products_name AS name','image_categories.path as image','products.products_slug as slug')
                                ->leftJoin('products_description','products_description.products_id','=','products.products_id')
                                ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id')
                                ->where('products_description.products_name','LIKE', '%'.$search_term.'%')
                                ->where('products_description.language_id','=', $language_id)
                                ->groupBy('products.products_id')
                                ->orderBy('products.products_id', 'DESC');

        if($products->count() > 0) {
            foreach ($products->get() as $product) {
                $response[] = [
                    "id"    => $product->id,
                    "name"  => $product->name,
                    "image" => asset($product->image),
					"link"  => url('/product-detail/'.$product->slug)
                ];
            }
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
	}

}
