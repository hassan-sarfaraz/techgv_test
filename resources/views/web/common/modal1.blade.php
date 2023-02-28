<div class="row">
	<div class="col-lg-5">
		<div id="quickView" class="carousel slide" data-ride="carousel">
			<!-- The slideshow -->
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img class="img-fluid" src="{{asset('').$result['detail']['product_data'][0]->image_path }}" alt="image">
				</div>
				@foreach( $result['detail']['product_data'][0]->images as $key=>$images )
				@if($images->image_type == 'ACTUAL')
				<div class="carousel-item">
					<img class="img-fluid" src="{{asset('').$images->image_path }}" alt="image">
				</div>
				@endif
				@endforeach
			</div>
			<!-- Left and right controls -->
			<a class="carousel-control-prev" href="#quickView" data-slide="prev">
				<span class="fas fa-angle-left angle"></span>
			</a>
			<a class="carousel-control-next" href="#quickView" data-slide="next">
				<span class="fas fa-angle-right angle"></span>
			</a>
		</div>
	</div>
	<div class="col-12 col-lg-7  product-page-one">
		<h1>{{$result['detail']['product_data'][0]->products_name}}</h1>
		<div class="list-main">
			<div class="icon-liked">
				<a class="icon active is_liked" products_id="{{$result['detail']['product_data'][0]->products_id}}">
					<i class="fas fa-heart"></i>
					<span class="badge badge-secondary counter">{{$result['detail']['product_data'][0]->products_liked}}</span>
				</a>
			</div>
			<ul class="list">
				@if(!empty($result['category_name']) and !empty($result['sub_category_name']))
				<li>{{$result['sub_category_name']}}</li>
				@elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
				<li>{{$result['category_name']}}</li>
				@endif
				<li> {{$result['detail']['product_data'][0]->products_ordered}}&nbsp;@lang('website.Order(s)')</li>
				@if($result['detail']['product_data'][0]->products_type == 0)
				@if($result['detail']['product_data'][0]->defaultStock == 0)
				<li class="outstock"><i class="fas fa-check"></i>@lang('website.Out of Stock')</li>
				@else
				<li class="instock"><i class="fas fa-check"></i>@lang('website.In stock')</li>
				@endif
				@endif
			</ul>
		</div>
		<form name="attributes" id="add-Product-form" method="post">
			<input type="hidden" name="products_id" value="{{$result['detail']['product_data'][0]->products_id}}">
			@if(!empty($result['detail']['product_data'][0]->flash_price))
			<?php
			$field_price  = $result['detail']['product_data'][0]->flash_price;
			?>
			@elseif(!empty($result['detail']['product_data'][0]->discount_price))
			<?php $field_price  = $result['detail']['product_data'][0]->discount_price; ?>
			@else
			<?php $field_price = $result['detail']['product_data'][0]->products_price; ?>
			@endif
			<?php
			// $tax = ($field_price * Config::get('global.p_constant'));
			// $field_price = $field_price + $tax;
			// $field_price = round($field_price);
			$field_price = pd_adjust_price_with_tax($field_price);
			?>
			<input type="hidden" name="products_price" id="products_price" value="{{$field_price+0}}">
			<input type="hidden" name="checkout" id="checkout_url" value="@if(!empty(app('request')->input('checkout'))) {{ app('request')->input('checkout') }} @else false @endif">
			<input type="hidden" id="max_order" value="@if(!empty($result['detail']['product_data'][0]->products_max_stock)) {{ $result['detail']['product_data'][0]->products_max_stock }} @else 0 @endif">
			@if(!empty($result['cart']))
			<input type="hidden" name="customers_basket_id" value="{{$result['cart'][0]->customers_basket_id}}">
			@endif
			<div class="product-controls row">
				@if(count($result['detail']['product_data'][0]->attributes)>0)
				<?php $index = 0; ?>
				@foreach( $result['detail']['product_data'][0]->attributes as $key=>$attributes_data )
				<?php $functionValue = 'function_' . $key++; ?>
				<input type="hidden" name="option_name[]" value="{{ $attributes_data['option']['name'] }}">
				<input type="hidden" name="option_id[]" value="{{ $attributes_data['option']['id'] }}">
				<input type="hidden" name="{{ $functionValue }}" id="{{ $functionValue }}" value="0">
				<input id="attributeid_{{$index}}" type="hidden" value="">
				<input id="attribute_sign_{{$index}}" type="hidden" value="">
				<input id="attributeids_{{$index}}" type="hidden" name="attributeid[]" value="">
				<div class="col-12 col-md-4 box">
					<label>{{ $attributes_data['option']['name'] }}</label>
					<div class="select-control ">
						<select name="{{ $attributes_data['option']['id'] }}" onChange="getQuantityPopup();" class="currentstock form-control attributeid_<?= $index++ ?>" attributeid="{{ $attributes_data['option']['id'] }}">
							@if(!empty($result['cart']))
							@php
							$value_ids = array();
							foreach($result['cart'][0]->attributes as $values){
							$value_ids[] = $values->options_values_id;
							}
							@endphp
							@foreach($attributes_data['values'] as $values_data)
							@if(!empty($result['cart']))
							<option @if(in_array($values_data['id'],$value_ids)) selected @endif attributes_value="{{ $values_data['products_attributes_id'] }}" value="{{ $values_data['id'] }}" prefix='{{ $values_data['price_prefix'] ?? '-'}}' value_price="{{ pd_adjust_price_with_tax($values_data['price']+0) }}">{{ $values_data['value'] }}
							</option>
							@endif
							@endforeach
							@else
							@foreach($attributes_data['values'] as $values_data)
							<option attributes_value="{{ $values_data['products_attributes_id'] }}" value="{{ $values_data['id'] }}" prefix='{{ $values_data['price_prefix'] ?? '-' }}' value_price="{{ pd_adjust_price_with_tax($values_data['price']+0) }}">{{ $values_data['value'] }}
							</option>
							@endforeach
							@endif
						</select>
					</div>
				</div>
				@endforeach
				@endif

				<div class="col-12 col-md-4 box Qty" @if(!empty($result['detail']['product_data'][0]->flash_start_date) and $result['detail']['product_data'][0]->server_time < $result['detail']['product_data'][0]->flash_start_date ) style="display: none" @endif>
						<label>Quantity</label>
						<div class="Qty">
							<div class="input-group">
								<span class="input-group-btn first qtyminus">
									<button class="btn btn-defualt" type="button"><i class="fa fa-minus" aria-hidden="true"></i></button>
								</span>
								<input style="width:-20px;" type="text" readonly name="quantity" id="qty" value=" @if(!empty($result['cart'])) {{$result['cart'][0]->customers_basket_quantity}} @else @if($result['detail']['product_data'][0]->products_min_order>0 and $result['detail']['product_data'][0]->defaultStock > $result['detail']['product_data'][0]->products_min_order) {{$result['detail']['product_data'][0]->products_min_order}} @else 1 @endif @endif" min="@if($result['detail']['product_data'][0]->products_min_order>0 and $result['detail']['product_data'][0]->defaultStock > $result['detail']['product_data'][0]->products_min_order) {{$result['detail']['product_data'][0]->products_min_order}} @else 1 @endif" max="@if(!empty($result['detail']['product_data'][0]->products_max_stock) and $result['detail']['product_data'][0]->products_max_stock>0 and $result['detail']['product_data'][0]->defaultStock > $result['detail']['product_data'][0]->products_max_stock){{ $result['detail']['product_data'][0]->products_max_stock}}@else{{ $result['detail']['product_data'][0]->defaultStock}}@endif" class="form-control qty">
								<span class="input-group-btn last qtyplus">
									<button class="btn btn-defualt" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
								</span>
							</div>
						</div>
				</div>
			</div>

			@foreach($result['promotions'] as $promotion)
			<div class="promotion_product-{{ $promotion->promotion_attribute_id }}">
				@if(!empty($result['has_promotion']))
				<h2 style="color: green;margin-top: 30px;">@if(session()->get('locale') == 'en')
					This Product has Promotion Buy
					@else
					هذا المنتج لديه ترقية شراء
					@endif
					{{$promotion->buy_frequency}}
					@if(session()->get('locale') == 'en')
					Get
					@else
					احصل على
					@endif {{$promotion->get_frequency}} @if(session()->get('locale') == 'en')
					<span style="color: red">free!</span>
					@else
					<span style="color: red">مجانا.</span> @endif
				</h2>
				<div class="product-controls row promotion_product">
					@if(count($result['detail']['product_data'][0]->attributes)>0)
					<?php $index = 0; ?>
					@foreach( $result['detail']['product_data'][0]->attributes as $key=>$attributes_data )
					<?php
					$functionValue = 'function_' . $key++;
					$at_p_from = !empty($promotion) ? $promotion->promotion_attribute_id : 0;
					$at_p_to = !empty($promotion) ? $promotion->promotion_attribute_id_to : 0;
					if ($attributes_data['option']['name'] == 'Size') {
					}
					?>
					<div class="col-12 col-md-4 box">
						<label>{{ $attributes_data['option']['name'] }}</label>
						<div class="select-control ">
							<select class="currentstock form-control" @if($attributes_data['option']['name']=='Size' ) disabled style="background: grey" @else name="promotion_{{ $attributes_data['option']['id'] }}" @endif>

								@foreach($attributes_data['values'] as $values_data)
								<option @if($values_data['products_attributes_id']==$at_p_to) selected @endif attributes_value="{{ $values_data['products_attributes_id'] }}" value="{{ $values_data['id'] }}" prefix='{{ $values_data['price_prefix'] ?? '-' }}' value_price="0">{{ $values_data['value'] }}
								</option>
								@endforeach
							</select>
						</div>
					</div>
					@endforeach
					@endif

					<div class="col-12 col-md-4 box Qty_promo" @if(!empty($result['detail']['product_data'][0]->flash_start_date) and $result['detail']['product_data'][0]->server_time < $result['detail']['product_data'][0]->flash_start_date ) style="display: none" @endif>
							<label>Quantity</label>
							<div class="Qty">
								<div class="input-group">
									<input style="width:-20px;" type="text" value="1" disabled class="form-control qty_promo-{{ $promotion->promotion_attribute_id }}">
								</div>
							</div>
					</div>

				</div>
			</div>
			@endif
			@endforeach

			<div class="product-buttons">
				<h2>Total Price:
					<span class="total_price">
						<?php
						$default_currency = DB::table('currencies')->where('is_default', 1)->first();
						if ($default_currency->id == Session::get('currency_id')) {
							if (!empty($result['detail']['product_data'][0]->discount_price)) {
								$discount_price = $result['detail']['product_data'][0]->discount_price;
							}
							if (!empty($result['detail']['product_data'][0]->flash_price)) {
								$flash_price = $result['detail']['product_data'][0]->flash_price;
							}
							$orignal_price = $result['detail']['product_data'][0]->products_price;
							/*$tax = ($orignal_price* Config::get('global.p_constant'));
						$orignal_price = $orignal_price + $tax;
						$orignal_price = round($orignal_price, 2);*/
						} else {
							$session_currency = DB::table('currencies')->where('id', Session::get('currency_id'))->first();
							if (!empty($result['detail']['product_data'][0]->discount_price)) {
								$discount_price = $result['detail']['product_data'][0]->discount_price * $session_currency->value;
							}
							if (!empty($result['detail']['product_data'][0]->flash_price)) {
								$flash_price = $result['detail']['product_data'][0]->flash_price * $session_currency->value;
							}
							$orignal_price = $result['detail']['product_data'][0]->products_price;
							/*$tax = ($orignal_price* Config::get('global.p_constant'));
						$orignal_price = $orignal_price + $tax;
						$orignal_price = round($orignal_price, 2);*/
							$orignal_price = $orignal_price * $session_currency->value;
						}
						if (!empty($result['detail']['product_data'][0]->discount_price)) {
							if (($orignal_price + 0) > 0) {
								$discounted_price = $orignal_price - $discount_price;
								$discount_percentage = $discounted_price / $orignal_price * 100;
								$discounted_price = $result['detail']['product_data'][0]->discount_price;
							} else {
								$discount_percentage = 0;
								$discounted_price = 0;
							}
						} else {
							$discounted_price = $orignal_price;
						}
						?>
						@if(!empty($result['detail']['product_data'][0]->flash_price))
						{{Session::get('symbol_left')}} {{pd_format_number(pd_adjust_price_with_tax($flash_price+0))}}{{Session::get('symbol_right')}}
						@elseif(!empty($result['detail']['product_data'][0]->discount_price))
						{{Session::get('symbol_left')}} {{pd_format_number(pd_adjust_price_with_tax($discount_price+0))}}{{Session::get('symbol_right')}}
						@else
						{{Session::get('symbol_left')}} {{pd_format_number(pd_adjust_price_with_tax($orignal_price+0))}}{{Session::get('symbol_right')}}
						@endif
				</h2>
				@if($result['detail']['product_data'][0]->products_min_order>0)
				<p>&nbsp; @lang('website.Min Order Limit:') {{ $result['detail']['product_data'][0]->products_min_order }}</p>
				@endif

				<div class="buttons">
					@if(!empty($result['detail']['product_data'][0]->flash_start_date) and $result['detail']['product_data'][0]->server_time < $result['detail']['product_data'][0]->flash_start_date )
						@else
						@if($result['detail']['product_data'][0]->products_type == 0)
						@if($result['detail']['product_data'][0]->defaultStock == 0)
						<button class="btn  btn-block  btn-danger " type="button">@lang('website.Out of Stock')</button>
						@else
						<button class="btn btn-block btn-secondary add-to-new-Cart" type="button" products_id="{{$result['detail']['product_data'][0]->products_id}}">@lang('website.Add to Cart')</button>
						@endif
						@else
						<button class="btn btn-secondary btn-block  add-to-new-Cart stock-cart" type="button" products_id="{{$result['detail']['product_data'][0]->products_id}}">@lang('website.Add to Cart')</button>
						<button class="btn btn-danger btn-block  stock-out-cart" hidden type="button">@lang('website.Out of Stock')</button>
						@endif
						@endif
				</div>

			</div>
		</form>

		<div class="pro-dsc-module">
			<div class="tab-list">
				<div class="nav nav-pills" role="tablist">
					<a class="nav-link nav-item nav-index active show" href="#description" id="description-tab" data-toggle="pill" role="tab">Description</a>
					<a class="nav-link nav-item nav-index show" href="#details" id="details-tab" data-toggle="pill" role="tab">Details</a>
				</div>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade active show" id="description" aria-labelledby="description-tab">
						<div class="tabs-pera">
							{!! stripslashes($result['detail']['product_data'][0]->products_description) !!}
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade show" id="details" aria-labelledby="details-tab">
						<div class="tabs-pera">
							{!! stripslashes($result['detail']['product_data'][0]->products_details) !!}
						</div>
					</div>

					<div role="tabpanel" class="tab-pane fade" id="review" aria-labelledby="review-tab">
						<div class="tabs-pera">
							<p>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit,
								sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
								nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
								reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
								Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Duis aute irure dolor in
								reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.Duis aute irure dolor in
								reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
							</p>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit,
								sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
								Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@php
$default_currency = DB::table('currencies')->where('is_default',1)->first();
if($default_currency->id == Session::get('currency_id')){
$currency_value = 1;
}else{
$session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();
$currency_value = $session_currency->value;
}
@endphp

<!--- one signal-->
@if(Request::path() == 'checkout')
<!------- //paypal -------->

<script type="text/javascript">
	//notification
	jQuery(document).on('click', '.shipping_data', function(e) {
		getZonesBilling();
	});

	function getZonesBilling() {
		var field_name = jQuery('.shipping_data:checked');
		var mehtod_name = jQuery(field_name).attr('method_name');
		var shipping_price = jQuery(field_name).attr('shipping_price');
		jQuery("#mehtod_name").val(mehtod_name);
		jQuery("#shipping_price").val(shipping_price);
	}

	window.onload = function(e) {
		var paypal_public_key = document.getElementById('paypal_public_key').value;
		var acount_type = document.getElementById('paypal_environment').value;
		if (acount_type == 'Test') {
			var paypal_environment = 'sandbox'
		} else if (acount_type == 'Live') {
			var paypal_environment = 'production'
		}
		paypal.Button.render({
			env: paypal_environment, // sandbox | production
			style: {
				label: 'checkout',
				size: 'small', // small | medium | large | responsive
				shape: 'pill', // pill | rect
				color: 'gold' // gold | blue | silver | black
			},
			// PayPal Client IDs - replace with your own
			// Create a PayPal app: https://developer.paypal.com/developer/applications/create
			client: {
				sandbox: paypal_public_key,
				production: paypal_public_key
			},
			// Show the buyer a 'Pay Now' button in the checkout flow
			commit: true,
			// payment() is called when the button is clicked
			payment: function(data, actions) {
				var payment_currency = document.getElementById('payment_currency').value;
				var total_price = '<?php echo pd_adjust_price_with_tax((float)$total_price + 0); ?>';
				// Make a call to the REST api to create the payment
				return actions.payment.create({
					payment: {
						transactions: [{
							amount: {
								total: total_price,
								currency: payment_currency
							}
						}]
					}
				});
			},
			// onAuthorize() is called when the buyer approves the payment
			onAuthorize: function(data, actions) {
				// Make a call to the REST api to execute the payment
				return actions.payment.execute().then(function() {
					jQuery('#update_cart_form').prepend('<input type="hidden" name="nonce" value=' + JSON.stringify(data) + '>');
					jQuery("#update_cart_form").submit();
				});
			}
		}, '#paypal_button');
	};
</script>
<script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function(e) {
		braintree.setup(
			// Replace this with a client token from your server
			" <?php print session('braintree_token') ?>",
			"dropin", {
				container: "payment-form"
			});
	});
</script>
<script src="{!! asset('web/js/stripe_card.js') !!}" data-rel-js></script>
<script type="application/javascript">
	(function() {
		'use strict';
		var elements = stripe.elements({
			fonts: [{
				cssSrc: 'https://fonts.googleapis.com/css?family=Source+Code+Pro',
			}, ],
			// Stripe's examples are localized to specific languages, but if
			// you wish to have Elements automatically detect your user's locale,
			// use `locale: 'auto'` instead.
			locale: window.__exampleLocale
		});

		// Floating labels
		var inputs = document.querySelectorAll('.cell.example.example2 .input');
		Array.prototype.forEach.call(inputs, function(input) {
			input.addEventListener('focus', function() {
				input.classList.add('focused');
			});
			input.addEventListener('blur', function() {
				input.classList.remove('focused');
			});
			input.addEventListener('keyup', function() {
				if (input.value.length === 0) {
					input.classList.add('empty');
				} else {
					input.classList.remove('empty');
				}
			});
		});

		var elementStyles = {
			base: {
				color: '#32325D',
				fontWeight: 500,
				fontFamily: 'Source Code Pro, Consolas, Menlo, monospace',
				fontSize: '16px',
				fontSmoothing: 'antialiased',

				'::placeholder': {
					color: '#CFD7DF',
				},
				':-webkit-autofill': {
					color: '#e39f48',
				},
			},
			invalid: {
				color: '#E25950',

				'::placeholder': {
					color: '#FFCCA5',
				},
			},
		};

		var elementClasses = {
			focus: 'focused',
			empty: 'empty',
			invalid: 'invalid',
		};

		var cardNumber = elements.create('cardNumber', {
			style: elementStyles,
			classes: elementClasses,
		});
		cardNumber.mount('#example2-card-number');

		var cardExpiry = elements.create('cardExpiry', {
			style: elementStyles,
			classes: elementClasses,
		});
		cardExpiry.mount('#example2-card-expiry');

		var cardCvc = elements.create('cardCvc', {
			style: elementStyles,
			classes: elementClasses,
		});
		cardCvc.mount('#example2-card-cvc');

		registerElements([cardNumber, cardExpiry, cardCvc], 'example2');
	})();
</script>
@endif
<script type="application/javascript">
	@if(Request::path() != 'shop')
	jQuery(function() {
		jQuery("#datepicker").datepicker({
			changeMonth: true,
			changeYear: true,
			maxDate: '0',
		});
	});
	@endif
	/*const closeDialog = () => {
	  const body = document.body;
	  const scrollY = body.style.top;
	  body.style.position = '';
	  body.style.top = '';
	  window.scrollTo(0, parseInt(scrollY || '0') * -1);
	  document.getElementById('dialog').classList.remove('show');
	}
	window.addEventListener('scroll', () => {
	  document.documentElement.style.setProperty('--scroll-y', `${window.scrollY}px`);
	});*/
	jQuery(document).on('click', '.alertTopclose', function(e) {
		jQuery('#alertTop').hide();
	});
	jQuery(document).ready(function() {
		if (!sessionStorage.getItem('shown-home-promo')) {
			jQuery('#promotionPop').modal({
				show: true
			});
			sessionStorage.setItem('shown-home-promo', 'true');
		}
		/*const body = document.body;
  body.style.position = 'fixed';*/
		/*$("#myModal").on("show.bs.modal", function () {
  var top = $("body").scrollTop(); $("body").css('position','fixed').css('overflow','hidden').css('top',-top).css('width','100%').css('height',top+5000);
}).on("hide.bs.modal", function () {
  var top = $("body").position().top; $("body").css('position','relative').css('overflow','auto').css('top',0).scrollTop(-top);
});*/
		jQuery('#loader').hide();
		@if($result['commonContent']['setting'][54] - > value == 'onesignal')
		OneSignal.push(function() {
			OneSignal.registerForPushNotifications();
			OneSignal.on('subscriptionChange', function(isSubscribed) {
				if (isSubscribed) {
					OneSignal.getUserId(function(userId) {
						device_id = userId;
						//ajax request
						jQuery.ajax({
							headers: {
								'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
							},
							url: '{{ URL::to("/subscribeNotification")}}',
							type: "POST",
							data: '&device_id=' + device_id,
							success: function(res) {},
						});
						//$scope.oneSignalCookie();
					});
				}
			});
		});
		@endif

		//load google map
		@if(Request::path() == 'contact-us')
		initialize();
		@endif

		@if(Request::path() == 'checkout')
		getZonesBilling();
		paymentMethods();
		@endif

		// $.noConflict();
		//stripe_ajax
		jQuery(document).on('click', '#stripe_ajax', function(e) {
			jQuery('#loader').css('display', 'flex');
			jQuery.ajax({
				headers: {
					'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ URL::to("/stripeForm")}}',
				type: "POST",
				success: function(res) {
					if (res.trim() == "already added") {} else {
						jQuery('.head-cart-content').html(res);
						jQuery(parent).removeClass('cart');
						jQuery(parent).addClass('active');
					}
					message = "@lang('website.Product is added')";
					notification(message);
					jQuery('#loader').hide();
				},
			});
		});
		//default product cart
		jQuery(document).on('click', '.cart', function(e) {
			var parent = jQuery(this);
			var products_id = jQuery(this).attr('products_id');
			var message;
			jQuery(function($) {
				jQuery.ajax({

					url: '{{ URL::to("/addToCart")}}',
					headers: {
						'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
					},

					type: "POST",
					data: '&products_id=' + products_id,
					success: function(res) {
						console.log("bb");
						if (res.status == 'exceed') {
							swal("Something Happened To Stock", "@lang('website.Ops! Product is available in stock But Not Active For Sale. Please contact to the admin')", "error");
						} else {
							jQuery('.head-cart-content').html(res);
							jQuery(parent).removeClass('cart');
							jQuery(parent).addClass('active');
							jQuery(parent).html("@lang('website.Added')");
							@if(session() - > get('locale') == 'en')
							swal("Congrates!", "Product Added Successfully Thanks.Continue Shopping", "success");
							@else
							swal("مبروك!", "تمت إضافة المنتج بنجاح ، شكرًا. استمر في التسوق", "success");
							@endif

						}

					},
				});
			});
		});

		jQuery(document).on('click', '.modal_show', function(e) {
			var parent = jQuery(this);
			var products_id = jQuery(this).attr('products_id');
			var message;
			jQuery(function($) {
				jQuery.ajax({

					url: '{{ URL::to("/modal_show")}}',
					headers: {
						'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
					},

					type: "POST",
					data: '&products_id=' + products_id,
					success: function(res) {
						jQuery("#products-detail").html(res);
						jQuery('#myModal').modal({
							show: true
						});
					},
				});
			});
		});
		//commeents
		jQuery(document).on('focusout', '#order_comments', function(e) {
			jQuery('#loader').css('display', 'flex');
			var comments = jQuery('#order_comments').val();
			jQuery.ajax({
				headers: {
					'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ URL::to("/commentsOrder")}}',
				type: "POST",
				data: '&comments=' + comments,
				async: false,
				success: function(res) {
					jQuery('#loader').hide();
				},
			});
		});
		//hyperpayresponse
		var resposne = jQuery('#hyperpayresponse').val();
		if (typeof resposne !== "undefined") {
			if (resposne.trim() == 'success') {
				jQuery('#loader').css('display', 'flex');
				jQuery("#update_cart_form").submit();
			} else if (resposne.trim() == 'error') {
				jQuery.ajax({
					headers: {
						'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
					},
					url: '{{ URL::to("/checkout/payment/changeresponsestatus")}}',
					type: "POST",
					async: false,
					success: function(res) {},
				});
				jQuery('#paymentError').css('display', 'block');
			}
		}
		//cash_on_delivery_button

		//shipping_mehtods_form
		jQuery(document).on('submit', '#shipping_mehtods_form', function(e) {
			jQuery('.error_shipping').hide();
			var checked = jQuery(".shipping_data:checked").length > 0;
			if (!checked) {
				jQuery('.error_shipping').show();
				return false;
			}
		});
		//update_cart
		jQuery(document).on('click', '#update_cart', function(e) {
			jQuery('#loader').css('display', 'flex');
			jQuery("#update_cart_form").submit();
		});
		//shipping_data

		//billling method
		jQuery(document).on('click', '#same_billing_address', function(e) {
			if (jQuery(this).prop('checked') == true) {
				jQuery("#billing_firstname").val(jQuery("#firstname").val());
				jQuery("#billing_lastname").val(jQuery("#lastname").val());
				jQuery("#billing_company").val(jQuery("#company").val());
				jQuery("#billing_street").val(jQuery("#street").val());
				jQuery("#billing_city").val(jQuery("#city").val());
				jQuery("#billing_zip").val(jQuery("#postcode").val());
				jQuery("#billing_countries_id").val(jQuery("#entry_country_id").val());
				jQuery("#billing_zone_id").val(jQuery("#entry_zone_id").val());

				jQuery(".same_address").attr('readonly', 'readonly');
				jQuery(".same_address_select").attr('disabled', 'disabled');
			} else {
				jQuery(".same_address").removeAttr('readonly');
				jQuery(".same_address_select").removeAttr('disabled');
			}
		});
		//apply_coupon_cart
		jQuery(document).on('submit', '#apply_coupon', function(e) {
			jQuery('#coupon_code').remove('error');
			jQuery('#coupon_require_error').hide();
			jQuery('#loader').css('display', 'flex');

			if (jQuery('#coupon_code').val().length > 0) {
				var formData = jQuery(this).serialize();
				jQuery.ajax({
					headers: {
						'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
					},
					url: '{{ URL::to("/apply_coupon")}}',
					type: "POST",
					data: formData,
					success: function(res) {
						console.log(res);
						var obj = JSON.parse(res);
						var message = obj.message;
						jQuery('#loader').hide();
						if (obj.success == 0) {
							jQuery("#coupon_error").html(message).show();
							return false;
						} else if (obj.success == 2) {
							jQuery("#coupon_error").html(message).show();
							return false;
						} else if (obj.success == 1) {
							window.location.reload(true);
						}
					},
				});
			} else {
				jQuery('#loader').css('display', 'none');
				jQuery('#coupon_code').addClass('error');
				jQuery('#coupon_require_error').show();
				return false;
			}
			jQuery('#loader').hide();
			return false;
		});
		//coupon_code
		jQuery(document).on('keyup', '#coupon_code', function(e) {
			jQuery("#coupon_error").hide();
			if (jQuery(this).val().length > 0) {
				jQuery('#coupon_code').removeClass('error');
				jQuery('#coupon_require_error').hide();
			} else {
				jQuery('#coupon_code').addClass('error');
				jQuery('#coupon_require_error').show();
			}

		});

		jQuery(document).on('click', '.is_liked', function(e) {

			var products_id = jQuery(this).attr('products_id');
			var selector = jQuery(this);
			jQuery('#loader').css('display', 'flex');
			var user_count = jQuery('#wishlist-count').html();
			jQuery.ajax({
				beforeSend: function(xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', jQuery('[name="_csrfToken"]').val());
				},
				url: '{{ URL::to("/likeMyProduct")}}',
				type: "POST",
				data: {
					"products_id": products_id,
					"_token": "{{ csrf_token() }}"
				},

				success: function(res) {
					var obj = JSON.parse(res);
					var message = obj.message;

					if (obj.success == 0) {
						jQuery('#alert-login-first').show();
						setTimeout(function() {
							jQuery('#alert-login-first').hide();
						}, 3000);

					} else if (obj.success == 2) {
						jQuery(selector).children('span').html(obj.total_likes);
						jQuery('#alert-liked').show();
						setTimeout(function() {
							jQuery('#alert-liked').hide();
						}, 3000);
					} else if (obj.success == 1) {
						jQuery('#alert-disliked').show();
						setTimeout(function() {
							jQuery('#alert-disliked').hide();
						}, 3000);
						jQuery(selector).children('span').html(obj.total_likes);
					}


				},
			});

		});
		//sortby
		jQuery(document).on('change', '.sortby', function(e) {
			jQuery('#loader').css('display', 'flex');
			jQuery("#load_products_form").submit();
		});

		jQuery(function() {
			jQuery.widget("custom.iconselectmenu", jQuery.ui.selectmenu, {
				_renderItem: function(ul, item) {
					var li = jQuery("<li>"),
						wrapper = jQuery("<div>", {
							text: item.label
						});

					if (item.disabled) {
						li.addClass("ui-state-disabled");
					}

					jQuery("<span>", {
							style: item.element.attr("data-style"),
							"class": "ui-icon " + item.element.attr("data-class")
						})
						.appendTo(wrapper);

					return li.append(wrapper).appendTo(ul);
				}
			});

			jQuery("#change_language")
				.iconselectmenu({
					create: function(event, ui) {
						var widget = jQuery(this).iconselectmenu("widget");
						$span = jQuery('<span id="' + this.id + '_image" class="ui-selectmenu-image"> ').html("&nbsp;").appendTo(widget);
						$span.attr("style", jQuery(this).children(":selected").data("style"));

					},
					change: function(event, ui) {
						jQuery("#" + this.id + '_image').attr("style", ui.item.element.data("style"));
						var locale = jQuery(this).val();
						changeLanguage(locale);

					}
				}).iconselectmenu("menuWidget").addClass("ui-menu-icons customicons");

		});

		jQuery(function() {
			jQuery("#category_id").selectmenu();
			jQuery(".attributes_data").selectmenu();
		});



		function validd() {
			var max = parseInt(document.detailform.quantity.max);
			var value = parseInt(document.detailform.quantity.value);

			if (value > max || value < 1) {

				jQuery('#alert-exceed').show();
				setTimeout(function() {
					jQuery('#alert-exceed').hide();
				}, 6000);
				document.detailform.quantity.focus();
				return false;
			} else {
				var formData = jQuery("#add-Product-form").serialize();
				var url = jQuery('#checkout_url').val();
				var message;
				jQuery.ajax({
					url: '{{ URL::to("/addToCart")}}',
					headers: {
						'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
					},

					type: "POST",
					data: formData,

					success: function(res) {
						console.log("aa");
						if (res.trim() == "already added") {
							//notification
							message = 'Product is added!';
						} else {
							jQuery('.head-cart-content').html(res);
							message = 'Product is added!';
							jQuery(parent).addClass('active');
						}
						if (url.trim() == 'true') {
							window.location.href = '{{ URL::to("/checkout")}}';
						} else {
							if (res == 'exceed') {
								swal("Something Happened To Stock", "@lang('website.Ops! Product is available in stock But Not Active For Sale. Please contact to the admin')", "error");
							} else {
								@if(session() - > get('locale') == 'en')
								swal("Congrates!", "Product Added Successfully Thanks.Continue Shopping", "success");
								@else
								swal("مبروك!", "تمت إضافة المنتج بنجاح ، شكرًا. استمر في التسوق", "success");
								@endif

							}
						}
					},
				});
			}
		}

		//add-to-new-Cart with custom options
		jQuery(document).on('click', '.add-to-new-Cart', function(e) {
			var formData = jQuery("#add-Product-form").serialize();
			var url = jQuery('#checkout_url').val();
			var message;
			jQuery.ajax({
				url: '{{ URL::to("/addToCart")}}',
				headers: {
					'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				},

				type: "POST",
				data: formData,

				success: function(res) {
					console.log("ss");
					if (res['status'] == 'exceed') {
						swal("Something Happened To Stock", "@lang('website.Ops! Product is available in stock But Not Active For Sale. Please contact to the admin')", "error");
					} else {
						jQuery('.head-cart-content').html(res);
						jQuery(parent).addClass('active');
						swal("Congrates!", "Product Added Successfully Thanks.Continue Shopping", "success", {
							button: false
						});

					}

				}
			});
		});

		jQuery(document).on('click', '.add-to-new-Cart-from-detail', function(e) {
			e.preventDefault();
			if (!validd()) {
				return false;
			}

		});

		function cart_item_price() {

			var subtotal = 0;
			jQuery(".cart_item_price").each(function() {
				subtotal = parseFloat(subtotal) + parseFloat(jQuery(this).val()) * <?= $currency_value ?>;
			});
			jQuery('#subtotal').html('<?= Session::get('symbol_left') ?>' + ' ' + subtotal + '<?= Session::get('symbol_right') ?>');

			var discount = 0;
			jQuery(".discount_price_hidden").each(function() {
				discount = parseFloat(discount) - parseFloat(jQuery(this).val());
			});

			jQuery('.discount_price').val(Math.abs(discount));

			jQuery('#discount').html('<?= Session::get('symbol_left') ?>' + ' ' + Math.abs(discount) * <?= $currency_value ?> + '<?= Session::get('symbol_right') ?>');

			//total value
			var total_price = parseFloat(subtotal) - parseFloat(discount) * <?= $currency_value ?>;
			jQuery('#total_price').html('<?= Session::get('symbol_left') ?>' + ' ' + total_price + '<?= Session::get('symbol_right') ?>');
		};
		//default_address
		jQuery(document).on('click', '.default_address', function(e) {
			jQuery('#loader').css('display', 'flex');
			var address_id = jQuery(this).attr('address_id');
			jQuery.ajax({
				headers: {
					'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ URL::to("/myDefaultAddress")}}',
				type: "POST",
				data: '&address_id=' + address_id,
				success: function(res) {
					window.location = 'shipping-address?action=default';
				},
			});
		});

		jQuery(function() {
			var maximum_price = jQuery(".maximum_price").val();
			jQuery("#slider-range").slider({
				range: true,
				min: 0,
				max: maximum_price,
				values: [0, maximum_price],
				slide: function(event, ui) {
					jQuery('#min_price').val(ui.values[0]);
					jQuery('#max_price').val(ui.values[1]);

					jQuery('#min_price_show').val(ui.values[0]);
					jQuery('#max_price_show').val(ui.values[1]);
				},
				create: function(event, ui) {
					jQuery(this).slider('value', 20);
				}
			});
			jQuery("#min_price_show").val(jQuery("#slider-range").slider("values", 0));
			jQuery("#max_price_show").val(jQuery("#slider-range").slider("values", 1));
			//jQuery( "#slider-range" ).slider( "option", "max", 50 );
		});
		//tooltip enable
		jQuery(function() {
			jQuery('[data-toggle="tooltip"]').tooltip()
		});
		//default product cart
	});


	jQuery(document).ready(function() {
		@if(!empty($result['detail']['product_data'][0] - > attributes))
		@foreach($result['detail']['product_data'][0] - > attributes as $key => $attributes_data)
		@php
		$functionValue = 'attributeid_'.$key;
		$attribute_sign = 'attribute_sign_'.$key++;
		@endphp

		//{{ $functionValue }}();
		function {
			{
				$functionValue
			}
		}() {
			var value_price = jQuery('option:selected', ".{{$functionValue}}").attr('value_price');
			jQuery("#{{ $functionValue }}").val(value_price);
		}
		//change_options
		jQuery(document).on('change', '.{{ $functionValue }}', function(e) {
			var {
				{
					$functionValue
				}
			} = jQuery("#{{ $functionValue }}").val();
			var old_sign = jQuery("#{{ $attribute_sign }}").val();
			var value_price = jQuery('option:selected', this).attr('value_price');
			var prefix = jQuery('option:selected', this).attr('prefix');
			var current_price = jQuery('#products_price').val();
			var {
				{
					$attribute_sign
				}
			} = jQuery("#{{ $attribute_sign }}").val(prefix);

			//calculateAttributePrice();

			if (old_sign.trim() == '+') {
				var current_price = parseFloat(current_price) - parseFloat({
					{
						$functionValue
					}
				});
			}
			if (old_sign.trim() == '-') {
				var current_price = parseFloat(current_price) + parseFloat({
					{
						$functionValue
					}
				});
			}
			if (prefix.trim() == '+') {
				var total_price = parseFloat(current_price) + parseFloat(value_price);
			}
			if (prefix.trim() == '-') {
				total_price = current_price - value_price;
			}

			jQuery("#{{ $functionValue }}").val(value_price);
			jQuery('#products_price').val(total_price);
			var qty = jQuery('.qty').val();
			var products_price = jQuery('#products_price').val();
			var total_price = qty * products_price * <?= $currency_value ?>;
			jQuery('.total_price').html('<?= Session::get('symbol_left') ?>' + ' ' + total_price.toFixed(2) + '<?= Session::get('symbol_right') ?>');

		});
		@endforeach

		calculateAttributePrice();

		function calculateAttributePrice() {
			var products_price = jQuery('#products_price').val();

			//var p_constant = '<?= Config::get('global.p_constant') ?>';
			//var tax = (parseFloat(products_price) * parseFloat(p_constant));
			//products_price = parseFloat(tax) + parseFloat(products_price);
			// products_price = Math.round(products_price,2);
			products_price = parseFloat(products_price);

			jQuery(".currentstock").each(function() {
				var value_price = jQuery('option:selected', this).attr('value_price');
				var prefix = jQuery('option:selected', this).attr('prefix');
				if (prefix.trim() == '+') {
					products_price = parseFloat(products_price) + parseFloat(value_price);
				}
				if (prefix.trim() == '-') {
					products_price = parseFloat(products_price) - parseFloat(value_price);
				}
			});
			jQuery('#products_price').val(products_price);
			jQuery('.total_price').html('<?= Session::get('symbol_left') ?>' + ' ' + products_price.toFixed(2) + '<?= Session::get('symbol_right') ?>');
		}
		@endif
	});

	@if(!empty($result['detail']['product_data'][0] - > products_type) and $result['detail']['product_data'][0] - > products_type == 1)
	getQuantityPopup();
	cartPrice();
	@endif

	function cartPrice() {
		var i = 0;
		jQuery(".currentstock").each(function() {
			var value_price = jQuery('option:selected', this).attr('value_price');
			var attributes_value = jQuery('option:selected', this).attr('attributes_value');
			var prefix = jQuery('option:selected', this).attr('prefix');
			jQuery('#attributeid_' + i).val(value_price);
			jQuery('#attribute_sign_' + i++).val(prefix);

		});
	}

	//ajax call for add option value
	function getQuantityPopup() {
		var attributeid = [];
		var i = 0;

		let attributes_value = jQuery('option:selected', jQuery(".attributeid_0")).attr('attributes_value');
		var qty = jQuery('.qty').val();

		@foreach($result['promotions'] as $promotion)
		if (attributes_value == {
				{
					!empty($promotion) ? $promotion - > promotion_attribute_id : 0
				}
			} &&
			parseFloat(qty) >= parseFloat({
				{
					$promotion - > buy_frequency
				}
			})) {
			jQuery(".promotion_product-{{ $promotion->promotion_attribute_id }}").show()
		} else {
			jQuery(".promotion_product-{{ $promotion->promotion_attribute_id }}").hide()
		}
		@endforeach

		jQuery(".currentstock").each(function() {
			var value_price = jQuery('option:selected', this).attr('value_price');
			var attributes_value = jQuery('option:selected', this).attr('attributes_value');
			jQuery('#function_' + i).val(value_price);
			jQuery('#attributeids_' + i++).val(attributes_value);
		});

		var formData = jQuery('#add-Product-form').serialize();
		jQuery.ajax({
			headers: {
				'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			},
			url: '{{ URL::to("getquantity")}}',
			type: "POST",
			data: formData,
			dataType: "json",
			success: function(res) {

				jQuery('#current_stocks').html(res.remainingStock);
				var min_level = 0;
				var max_level = 0;
				var inventory_ref_id = res.inventory_ref_id;

				if (res.minMax != '') {
					min_level = res.minMax[0].min_level;
					max_level = res.minMax[0].max_level;
				}

				if (res.remainingStock > 0) {

					jQuery('.stock-cart').removeAttr('hidden');
					jQuery('.stock-out-cart').attr('hidden', true);
					var max_order = jQuery('#max_order').val();

					if (max_order.trim() != 0) {
						if (max_order.trim() >= res.remainingStock) {
							jQuery('.qty').attr('max', res.remainingStock);
						} else {
							jQuery('.qty').attr('max', max_order);
						}
					} else {
						jQuery('.qty').attr('max', res.remainingStock);
					}


				} else {
					jQuery('.stock-out-cart').removeAttr('hidden');
					jQuery('.stock-cart').attr('hidden', true);
					jQuery('.qty').attr('max', 0);
				}

			},
		});
	}

	jQuery(document).on('click', '.qtyplus', function(e) {

		var qty = jQuery('.qty').val();
		let attributes_value = jQuery('option:selected', jQuery(".attributeid_0")).attr('attributes_value');
		@foreach($result['promotions'] as $promotion)
		@if(!empty($promotion))


		if (attributes_value == {
				{
					!empty($promotion) ? $promotion - > promotion_attribute_id : 0
				}
			} &&
			parseFloat(qty) >= parseFloat({
				{
					$promotion - > buy_frequency
				}
			})) {
			jQuery(".promotion_product-{{ $promotion->promotion_attribute_id }}").show()
		} else {
			jQuery(".promotion_product-{{ $promotion->promotion_attribute_id }}").hide()
		}

		if (parseFloat({
				{
					$promotion - > buy_frequency
				}
			}) > parseFloat({
				{
					$promotion - > get_frequency
				}
			})) {
			jQuery(".qty_promo-{{ $promotion->promotion_attribute_id }}").val(parseInt(parseFloat(qty) / parseFloat({
				{
					$promotion - > buy_frequency
				}
			})))
		} else {
			jQuery(".qty_promo-{{ $promotion->promotion_attribute_id }}").val(parseInt(parseFloat(qty) * parseFloat({
				{
					$promotion - > get_frequency
				}
			})))
		}
		@endif;
		@endforeach
	});




	jQuery(document).on('click', '.qtyminus', function(e) {
		var qty = jQuery('.qty').val();
		let attributes_value = jQuery('option:selected', jQuery(".attributeid_0")).attr('attributes_value');
		@foreach($result['promotions'] as $promotion)
		@if(!empty($promotion))
		if (attributes_value == {
				{
					!empty($promotion) ? $promotion - > promotion_attribute_id : 0
				}
			} &&
			parseFloat(qty) >= parseFloat({
				{
					$promotion - > buy_frequency
				}
			})) {
			jQuery(".promotion_product-{{ $promotion->promotion_attribute_id }}").show()
		} else {
			jQuery(".promotion_product-{{ $promotion->promotion_attribute_id }}").hide()
		}
		if (parseFloat({
				{
					$promotion - > buy_frequency
				}
			}) > parseFloat({
				{
					$promotion - > get_frequency
				}
			})) {
			jQuery(".qty_promo-{{ $promotion->promotion_attribute_id }}").val(parseInt(parseFloat(qty) / parseFloat({
				{
					$promotion - > buy_frequency
				}
			})))
		} else {
			jQuery(".qty_promo-{{ $promotion->promotion_attribute_id }}").val(parseInt(parseFloat(qty) * parseFloat({
				{
					$promotion - > get_frequency
				}
			})))
		}
		@endif;
		@endforeach
	});

	var qty = jQuery('.qty').val();
	let attributes_value = jQuery('option:selected', jQuery(".attributeid_0")).attr('attributes_value');
	@foreach($result['promotions'] as $promotion)
	@if(!empty($promotion))
	if (attributes_value == {
			{
				!empty($promotion) ? $promotion - > promotion_attribute_id : 0
			}
		} &&
		parseFloat(qty) >= parseFloat({
			{
				$promotion - > buy_frequency
			}
		})) {
		jQuery(".promotion_product-{{ $promotion->promotion_attribute_id }}").show()
	} else {
		jQuery(".promotion_product-{{ $promotion->promotion_attribute_id }}").hide()
	}
	if (parseFloat({
			{
				$promotion - > buy_frequency
			}
		}) > parseFloat({
			{
				$promotion - > get_frequency
			}
		})) {
		jQuery(".qty_promo-{{ $promotion->promotion_attribute_id }}").val(parseInt(parseFloat(qty) / parseFloat({
			{
				$promotion - > buy_frequency
			}
		})))
	} else {
		jQuery(".qty_promo-{{ $promotion->promotion_attribute_id }}").val(parseInt(parseFloat(qty) * parseFloat({
			{
				$promotion - > get_frequency
			}
		})))
	}
	@endif;
	@endforeach
</script>