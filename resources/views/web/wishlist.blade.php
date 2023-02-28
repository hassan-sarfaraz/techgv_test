@extends('web.layout')
@section('content')
<!-- wishlist Content -->
<section class="wishlist-content my-4">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-3">
				<div class="heading">
						<h2>
								@lang('website.My Account')
						</h2>
						<hr >
					</div>

				<ul class="list-group">
				<li class="list-group-item">
                   <a class="nav-link" href="{{ URL::to('/profile')}}">
                       <i class="fas fa-user"></i>
                       @if(session()->get('locale') == 'en') 
                     @lang('website.Profile')
                     @else 
                     الملف الشخصي
                     @endif
                   </a>
               </li>
               <li class="list-group-item">
                   <a class="nav-link" href="{{ URL::to('/wishlist')}}">
                       <i class="fas fa-heart"></i>
                       @if(session()->get('locale') == 'en') 
                    @lang('website.Wishlist')
                    @else 
                    الأماني
                     @endif
                   </a>
               </li>
               <li class="list-group-item">
                   <a class="nav-link" href="{{ URL::to('/orders')}}">
                       <i class="fas fa-shopping-cart"></i>
                       @if(session()->get('locale') == 'en') 
                     @lang('website.Orders')
                     @else 
                     أوامر
                     @endif
                   </a>
               </li>
               <li class="list-group-item">
                   <a class="nav-link" href="{{ URL::to('/shipping-address')}}">
                       <i class="fas fa-map-marker-alt"></i>
                       @if(session()->get('locale') == 'en') 
                    @lang('website.Shipping Address')
                    @else 
                    عنوان الشحن
                     @endif
                   </a>
               </li>
               <li class="list-group-item">
                   <a class="nav-link" href="{{ URL::to('/logout')}}">
                       <i class="fas fa-power-off"></i>
                       @if(session()->get('locale') == 'en') 
                     @lang('website.Logout')
                     @else 
                     الخروج
                     @endif
                   </a>
               </li>
					</ul>

			</div>
			<div class="col-12 col-lg-9 ">
					<div class="heading">
							<h2>
							@if(session()->get('locale') == 'en') 
                    @lang('website.Wishlist')
                    @else 
                    الأماني
                     @endif
							</h2>
							<hr >
						</div>

					<div class="col-12 media-main">
						@foreach($result['products']['product_data'] as $key=>$products)
								<div class="media">
										<img class="img-fluid" src="{{asset('').$products->image_path}}" alt="{{$products->products_name}}">
										<div class="media-body">
											<div class="row">
												<div class="col-12 col-md-8  texting">
													<?php
														 $default_currency = DB::table('currencies')->where('is_default',1)->first();
														 if($default_currency->id == Session::get('currency_id')){
															 if(!empty($products->discount_price)){
															 $discount_price = $products->discount_price;
															 }
															 $orignal_price = $products->products_price;
														 }else{
															 $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();
															 if(!empty($products->discount_price)){
																 $discount_price = $products->discount_price * $session_currency->value;
															 }
															 $orignal_price = $products->products_price * $session_currency->value;
														 }
															if(!empty($products->discount_price)){

															 if(($orignal_price+0)>0){
															$discounted_price = $orignal_price-$discount_price;
															$discount_percentage = $discounted_price/$orignal_price*100;
															}else{
																$discount_percentage = 0;
																$discounted_price = 0;
														}
													?>
													<span class="discount-tag"><?php echo (int)$discount_percentage; ?>%</span>
												 <?php }
												 $current_date = date("Y-m-d", strtotime("now"));

												 $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
												 $date=date_create($string);
												 date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));

												 //echo $top_seller->products_date_added . "<br>";
												 $after_date = date_format($date,"Y-m-d");

												 if($after_date>=$current_date){
													 print '<span class="discount-tag">';
													 print __('website.New');
													 print '</span>';
												 }
													?>
													<h5><a href="{{url('/shop')}}">{{$products->products_name}}</a></h5>
													<h6>Total Price:
														@if(!empty($products->discount_price))
					                  {{Session::get('symbol_left')}}{{$discounted_price+0}}{{Session::get('symbol_right')}}
					                  <span> {{Session::get('symbol_left')}}{{$products->price+0}}{{Session::get('symbol_right')}}</span>
					                  @else
					                  {{Session::get('symbol_left')}}{{$orignal_price+0}}{{Session::get('symbol_right')}}
					                  @endif
													</h6>
													<p class="d-none d-lg-block d-xl-block"><?=stripslashes($products->products_description)?></p>
													<div class="buttons">
															@if($products->products_type==0)
																	@if(!in_array($products->products_id,$result['cartArray']))
																			<a  class="btn btn-secondary cart" products_id="{{$products->products_id}}">
																				@if(session()->get('locale') == 'en') 
																				@lang('website.Add to Cart')
																				@else 
																				إضافة إلى السلة
																				@endif	
																			</a>
																	@elseif($products->products_min_order>1)
																			<a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">
																			@if(session()->get('locale') == 'en') 
																			@lang('website.View Detail')
																				@else 
																				عرض التفاصيل
																				@endif
																				</a>
																	@else
																			<a  class="btn btn-secondary active">
																				@if(session()->get('locale') == 'en') 
																					@lang('website.Added')
																				@else 
																					واضاف
																				@endif
																			</a>
																	@endif
															@elseif($products->products_type==1)
																	<a class="btn  btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
															@elseif($products->products_type==2)
																	<a href="{{$products->products_url}}" target="_blank" class="btn btn-block btn-secondary">@lang('website.External Link')</a>
															@endif
													</div>
												</div>
												<div class="col-12 col-md-4 detail">
													<div class="share"><a href="{{ URL::to("/UnlikeMyProduct")}}/{{$products->products_id}}"> @if(session()->get('locale') == 'en') Remove @else يزيل @endif &nbsp;<i class="fas fa-trash-alt"></i></a> </div>
												</div>
												</div>
										</div>

								</div>
								<hr class="border-line">
						@endforeach
					</div>
					<div class="toolbar mb-3 loaded_content">
							<div class="form-inline">
								<div class="form-group col-12 col-md-4"></div>

									<div class="form-group col-12 col-md-4"></div>
									<div class="form-group col-12 col-md-4">
											<label class="col-12 col-lg-4 col-form-label">@lang('website.Limit')</label>
											<select class="col-12 col-lg-3 form-control sortbywishlist" name="limit">
													<option value="15" @if(app('request')->input('limit')=='15') selected @endif">15</option>
													<option value="30" @if(app('request')->input('limit')=='30') selected @endif>30</option>
													<option value="45" @if(app('request')->input('limit')=='45') selected @endif>45</option>
											</select>
											<label class="col-12 col-lg-5 col-form-label">@lang('website.per page')</label>
									</div>
							</div>
					</div>
					<hr class="border-line">

				<!-- ............the end..... -->
			</div>
		</div>
	</div>
</section>
@endsection
