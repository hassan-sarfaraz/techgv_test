<?php
namespace App\Http\Controllers\Web;
//use Mail;
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
use App\Models\Web\Products;
use App\Models\Web\Index;
use App\Models\Web\Cart;
use Illuminate\Support\Facades\Log;
//for Carbon a value
use Carbon;
use Session;
use Lang;

class CartController extends Controller
{

	public function __construct(
		                  Index $index,
											Products $products,
											Cart $cart
											)
	{
		$this->index = $index;
		$this->products = $products;
		$this->cart = $cart;
		$this->theme = new ThemeController();

	}

	//myCart
	public function viewcart(Request $request){

		$title = array('pageTitle' => Lang::get("website.View Cart"));
		$result = array();
		$data = array();
		$result['commonContent'] = $this->index->commonContent();
		$final_theme = $this->theme->theme();

//		 print_r($final_theme['cart']);exit;
        $result['cart'] = $this->cart->myCart($data);
//
        foreach( $result['cart'] as $products )
        {
            $products->promotion = $this->cart->get_promotions($products->products_id)[0] ?? '';
        }
//        print_r($result['cart']);
//		 exit();
//		$promotions = $this->cart->get_promotions($result['cart']);
		$promotions = [];
		// print_r($promotions);
		$result['promotions'] = $promotions;
		//apply coupon
		if(session('coupon')){
			$session_coupon_data = session('coupon');
			session(['coupon' => array()]);
			$response = array();
			if(!empty($session_coupon_data)){
				foreach($session_coupon_data as $key=>$session_coupon){
						$response = $this->cart->common_apply_coupon($session_coupon->code);
				}
			}
		}
		//dd($result['cart']);
		return view("web.carts.viewcart", ['title' => $title,'final_theme' => $final_theme])->with('result', $result);
	}

	//eidtCart
	public function editcart(Request $request,$id,$slug){


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

				$products = $this->products->getProductsBySlug($slug);

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

				//liked products
				$result['liked_products'] = $this->products->likedProducts();

	     	$result['cart'] = $this->cart->myCart($id);

		return view("web.detail", ['title' => $title, 'final_theme' => $final_theme])->with('result', $result);

	}

	//deleteCart
	public function deleteCart(Request $request){

        $check = $this->cart->deleteCart($request);
		//apply coupon
		if(!empty(session('coupon')) and count(session('coupon'))>0){
			$session_coupon_data = session('coupon');
			session(['coupon' => array()]);
			//if(count($session_coupon_data)=='2'){
				$response = array();
				if(!empty($session_coupon_data)){
					foreach($session_coupon_data as $key=>$session_coupon){
							$response = $this->cart->common_apply_coupon($session_coupon->code);
					}
				}
			//}
		}

		if(!empty($request->type) and $request->type=='header cart'){
			$result['commonContent'] = $this->index->commonContent();
			if(empty($check)){
				$message = Lang::get("website.Cart item has been deleted successfully");
				return redirect('/viewcart')->with('message', $message);

			}else{
				$message = Lang::get("website.Cart item has been deleted successfully");
				return view("web.headers.cartButtons.cartButton")->with('result', $result);
			}
		}else{
			if(empty($check)){
				$message = Lang::get("website.Cart item has been deleted successfully");
				return redirect('/viewcart')->with('message', $message);

			}else{
				$message = Lang::get("website.Cart item has been deleted successfully");
				return redirect()->back()->with('message', $message);
			}
		}
	}


	//getCart
	public function cartIdArray($request){
      $this->cart->cartIdArray($request);
	}

	//updatesinglecart
	public function updatesinglecart(Request $request){
    $this->cart->updatesinglecart($request);
		return view("web.headers.cartButtons.cartButton")->with('result', $result);
	}



	//addToCart
	public function addToCart(Request $request){
		$result = $this->cart->addToCart($request);
		if(!empty($result['status']) && $result['status'] == 'exceed'){
			return $result;
		}
		return view("web.headers.cartButtons.cartButton")->with('result', $result);
	}
	//updateCart
	public function updateCart(Request $request){

		if(empty(session('customers_id'))){
			$customers_id					=	'';
		}else{
			$customers_id					=	session('customers_id');
		}
		$session_id							=	Session::getId();
		foreach($request->cart as $key=>$customers_basket_id){
       $this->cart->updateRecord($customers_basket_id,$customers_id,$session_id,$request->quantity[$key]);
		}

		$message = Lang::get("website.Cart has been updated successfully");
		return redirect()->back()->with('message', $message);

	}




	//apply_coupon
	public function apply_coupon(Request $request){

		$result = array();
		$coupon_code = $request->coupon_code;


		$carts = $this->cart->myCart(array());
		$session_coupon_data = session('coupon');
		if(empty($session_coupon_data)){
			if(count($carts)>0){
				// Log::Info($coupon_code);
				$response = $this->cart->common_apply_coupon($coupon_code);
			}else{
				$response = array('success'=>'0', 'message'=>Lang::get("website.Coupon can not be apllied to empty cart"));
			}
		}else{
			$response = array('success'=>'0', 'message'=>Lang::get("You can not apply multiple Coupons at once."));
		}
		print_r(json_encode($response));
	}


	//removeCoupon
	public function removeCoupon(Request $request){
		$coupons_id = $request->id;

		$session_coupon_data = session('coupon');
		session(['coupon' => array()]);
		session(['coupon_discount' => 0]);
		$response = array();
		if(!empty($session_coupon_data)){
			foreach($session_coupon_data as $key=>$session_coupon){
				if($session_coupon->coupans_id != $coupons_id){
					$response = $this->cart->common_apply_coupon($session_coupon->code);
				}
			}
		}

		$message = Lang::get("website.Coupon has been removed successfully");
		return redirect()->back()->with('message', $message);

	}

}
