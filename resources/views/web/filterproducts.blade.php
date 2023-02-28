@if($result['products']['success']==1)
@foreach($result['products']['product_data'] as $key=>$products)
<div class="col-12 col-sm-12 col-md-6 col-lg-4">
	<!-- Product -->
	<div class="product">
		<article>
			<div class="thumb">
				<div class="icons mobile-icons d-lg-none d-xl-none">
					<div class="icon-liked">
						<a class="icon active is_liked" products_id="{{$products->products_id}}">
							<i class="fas fa-heart"></i>
							<span class="badge badge-secondary counter">{{$products->products_liked}}</span>
						</a>
					</div>
					<div class="icon"><i class="fas fa-eye"></i></div>
					<a href="{{url('compare')}}" class="icon"><i class="fas fa-align-right" data-fa-transform="rotate-90"></i></a>
				</div>
				<a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"><img class="img-fluid" src="{{asset('').$products->image_path}}" alt="{{$products->products_name}}"></a>
			</div>
			<?php
			$default_currency = DB::table('currencies')->where('is_default', 1)->first();
			if ($default_currency->id == Session::get('currency_id')) {
				if (!empty($products->discount_price)) {
					$discount_price = pd_adjust_price_with_tax($products->discount_price);
				}
				$orignal_price = pd_adjust_price_with_tax($products->products_price);
			} else {
				$session_currency = DB::table('currencies')->where('id', Session::get('currency_id'))->first();
				if (!empty($products->discount_price)) {
					$discount_price = pd_adjust_price_with_tax(($products->discount_price * $session_currency->value));
				}
				$orignal_price = pd_adjust_price_with_tax(($products->products_price * $session_currency->value));
			}
			if (!empty($products->discount_price)) {
				if (($orignal_price + 0) > 0) {
					$discounted_price = $orignal_price - $discount_price;
					$discount_percentage = $discounted_price / $orignal_price * 100;
				} else {
					$discount_percentage = 0;
					$discounted_price = 0;
				}
			?>
				<span class="discount-tag"><?php echo (int)$discount_percentage; ?>%</span>
			<?php }
			$current_date = date("Y-m-d", strtotime("now"));

			$string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
			$date = date_create($string);
			date_add($date, date_interval_create_from_date_string($web_setting[20]->value . " days"));

			//echo $top_seller->products_date_added . "<br>";
			$after_date = date_format($date, "Y-m-d");

			if ($after_date >= $current_date) {
				print '<span class="discount-tag">';
				print __('website.New');
				print '</span>';
			}
			?>
			<span class="tag">
			</span>
			<!-- <span class="tag">
        @foreach($products->categories as $key=>$category)
            {{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif
        @endforeach
      </span> -->
			<h2 class="title text-center"><a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" style="font-weight: 800;">{{$products->products_name}}</a></h2>
			<div class="price">

				<?php
				$orignal_price2 = '';
				$session_currency = DB::table('currencies')->where('id', Session::get('currency_id'))->first();
				?>
				@foreach($products->attributes as $key=>$attributes_data )
				@foreach($attributes_data['values'] as $values_data)
				@if($values_data['price'] > 0)
				@if($values_data['price_prefix'] == '+')
				<?php
				$p2 = ($products->products_price + $values_data['price']);
				$p2 = pd_adjust_price_with_tax(($p2 * $session_currency->value));
				$orignal_price2 = "/" . pd_format_number(($p2), 2);
				?>
				@else
				<?php
				$p2 = ($products->products_price - $values_data['price']);
				$p2 = pd_adjust_price_with_tax(($p2 * $session_currency->value));
				$orignal_price2 =  pd_format_number(($p2), 2);
				?>
				@break
				@endif
				@endif
				@endforeach
				@endforeach


				@if(!empty($products->discount_price))
				{{Session::get('symbol_left')}}{{pd_format_number(($discount_price+0),2)}}{{ $orignal_price2 }}{{Session::get('symbol_right')}}
				<span> {{Session::get('symbol_left')}}<span class="price_value">{{pd_format_number(($orignal_price+0),2)}}{{ $orignal_price2 }}</span>{{Session::get('symbol_right')}}</span>
				@elseif(!empty($orignal_price2))
				{{Session::get('symbol_left')}} {{ $orignal_price2 }}/{{pd_format_number(($orignal_price+0),2)}} {{Session::get('symbol_right')}}
				@else
				{{Session::get('symbol_left')}} {{pd_format_number(($orignal_price+0),2)}} {{Session::get('symbol_right')}}
				@endif

			</div>
			<div class="product-hover d-none d-lg-block d-xl-block">
				<div class="icons">
					<div class="icon-liked">
						<a class="icon active is_liked" products_id="{{$products->products_id}}">
							<i class="fas fa-heart"></i>
							<span class="badge badge-secondary counter">{{$products->products_liked}}</span>
						</a>
					</div>
					<div class="icon modal_show" data-toggle="modal" data-target="#myModal" products_id="{{$products->products_id}}"><i class="fas fa-eye"></i></div>
					<a onclick="myFunction3({{$products->products_id}})" class="icon"><i class="fas fa-align-right" data-fa-transform="rotate-90"></i></a>
				</div>
				@include('web.common.scripts.addToCompare')
				<div class="buttons">
					@if($products->products_type==0)
					@if(!in_array($products->products_id,$result['cartArray']))
					@if($products->defaultStock==0)

					<button type="button" class="btn btn-block btn-danger" products_id="{{$products->products_id}}">@lang('website.Out of Stock')</button>
					@elseif($products->products_min_order>1)
					<a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
					@else
					<button type="button" class="btn btn-block btn-secondary cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
					@endif
					@else
					<button type="button" class="btn btn-block btn-secondary active">@lang('website.Added')</button>
					@endif
					@elseif($products->products_type==1)
					<a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
					@elseif($products->products_type==2)
					<a href="{{$products->products_url}}" target="_blank" class="btn btn-block btn-secondary">@lang('website.External Link')</a>
					@endif
				</div>
			</div>
			<div class="mobile-buttons d-lg-none d-xl-none">
				@if($products->products_type==0)
				@if(!in_array($products->products_id,$result['cartArray']))
				@if($products->defaultStock==0)
				<button type="button" class="btn btn-block btn-danger" products_id="{{$products->products_id}}">@lang('website.Out of Stock')</button>
				@elseif($products->products_min_order>1)
				<a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
				@else
				<button type="button" class="btn btn-block btn-secondary cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
				@endif
				@else
				<button type="button" class="btn btn-block btn-secondary active">@lang('website.Added')</button>
				@endif
				@elseif($products->products_type==1)
				<a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
				@elseif($products->products_type==2)
				<a href="{{$products->products_url}}" target="_blank" class="btn btn-block btn-secondary">@lang('website.External Link')</a>
				@endif
			</div>
		</article>
	</div>
</div>

@endforeach
<input id="filter_total_record" type="hidden" value="{{$result['products']['total_record']}}">

@if(count($result['products']['product_data'])> 0 and $result['limit'] > $result['products']['total_record'])
<style>
	#load_products {
		display: none;
	}

	#loaded_content {
		display: block !important;
	}

	#loaded_content_empty {
		display: none !important;
	}
</style>
@endif
@elseif(count($result['products']['product_data'])==0 or $result['products']['success']==0)
<style>
	#load_products {
		display: none;
	}

	#loaded_content {
		display: none !important;
	}

	#loaded_content_empty {
		display: block !important;
	}
</style>
@endif