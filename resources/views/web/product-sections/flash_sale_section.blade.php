@if($result['flash_sale']['success']==1)
<!-- Products content -->
<section class="products-content">
  <div class="container bg-white p-4 mb-4">
    @if($result['flash_sale']['success']==1)
    <div class="products-area">

      <div class="row">
        <div class="col-12">
          <div class="heading">
            <h2>
              @lang('website.Flash Sale')
            </h2>
            <hr style="margin-bottom: 0;">
          </div>
          <div id="owl-flash" class="owl-flash owl-carousel" style="margin-bottom: 10px;">
            @foreach($result['flash_sale']['product_data'] as $key=>$products)

            @if( date("Y-m-d", $products->server_time) >= date("Y-m-d", $products->flash_start_date))
            <div class="product">
                <article>
                    <div class="thumb">
                      <div class="icons mobile-icons d-lg-none d-xl-none">
                          <div class="icon-liked">
                            <a onclick="myLike({{$products->products_id}})" class="icon active">
                              <i class="fas fa-heart"></i>
                              <span class="badge badge-secondary">{{$products->products_liked}}</span>
                            </a>
                          </div>
                          <div class="icon" data-toggle="modal" data-target="#myModal"><i class="fas fa-eye"></i></div>
                          <a href="compare.html" class="icon"><i class="fas fa-align-right" data-fa-transform="rotate-90"></i></a>
                        </div>
                      <img class="img-fluid" src="{{asset('').$products->image_path}}" alt="{{$products->products_name}}">
                   </div>
                   <?php
                      $default_currency = DB::table('currencies')->where('is_default',1)->first();
                      if($default_currency->id == Session::get('currency_id')){
                        if(!empty($products->flash_price)){
                        $discount_price = $products->flash_price;
                        }
                        $orignal_price = $products->products_price;
                        $tax = ($orignal_price* Config::get('global.p_constant'));
                        $orignal_price = $orignal_price + $tax;
                        $orignal_price = round($orignal_price, 2);
                      }else{
                        $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();
                        if(!empty($products->flash_price)){
                          $discount_price = $products->flash_price * $session_currency->value;
                        }

                        $orignal_price = $products->products_price * $session_currency->value;
                        $tax = ($orignal_price* Config::get('global.p_constant'));
                        $orignal_price = $orignal_price + $tax;
                        $orignal_price = round($orignal_price, 2);
                      }
                       if(!empty($products->flash_price)){

                        if(($orignal_price+0)>0){
                       $discounted_price = $orignal_price-$discount_price;
                       $discount_percentage = $discounted_price/$orignal_price*100;
                       $discounted_price =$products->flash_price;

                       }else{
                         $discount_percentage = 0;
                         $discounted_price = 0;
                     }
                   ?>
                   <span class="discount-tag" style="font-size: 1.5em;"><?php echo (int)$discount_percentage; ?>%</span>
                  <?php }?>
                   <div class="discount-tag timer-tag" style="display: none;">
                     <span>Flash sale end&rsquo;s</span>
                     <span id="counter_{{$products->products_id}}"></span>
                     <i class="far fa-clock"></i>
                   </div>
                  <span class="tag">
                    @foreach($products->categories as $key=>$category)
                        {{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif
                    @endforeach
                  </span>
                  <h2 class="title text-center"><a href="{{ URL::to('/product-detail/'.$products->products_slug)}}">{{$products->products_name}}</a></h2>
                  <div class="price" style="display: grid;grid-template-columns: 1fr;grid-template-rows: repeat(2, 1fr);grid-column-gap: 0px;grid-row-gap: 0;text-align: center;">
                    @if(!empty($products->flash_price))
                    <span style="font-size: 0.8em;">
                        <span class="price_value">{{Session::get('symbol_left')}} {{pd_format_number($orignal_price+0)}} {{Session::get('symbol_right')}}</span>
                    </span>
                    {{Session::get('symbol_left')}}{{pd_adjust_price_with_tax($discounted_price+0)}}{{Session::get('symbol_right')}}
                    @else
                      {{Session::get('symbol_left')}}
                        <span class="price_value">{{pd_format_number($orignal_price+0)}}</span>
                      {{Session::get('symbol_right')}}
                    @endif
                  </div>
                  <div class="product-hover d-none d-lg-block d-xl-block">
                    <div class="icons">
                        <div class="icon-liked">
                          <a class="icon active is_liked" products_id="<?=$products->products_id?>">
                            <i class="fas fa-heart"></i>
                            <span  class="badge badge-secondary counter"  >{{$products->products_liked}}</span>
                          </a>
                        </div>
                      <div class="icon modal_show" data-toggle="modal" data-target="#myModal" products_id="{{$products->products_id}}"><i class="fas fa-eye"></i></div>
                        <a onclick="myFunction3({{$products->products_id}})"class="icon"><i class="fas fa-align-right" data-fa-transform="rotate-90"></i></a>
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


            @endif
            @endforeach
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
</section>
@endif
<script>

jQuery(document).ready(function(e) {

   @if(!empty($result['flash_sale']['success']) and $result['flash_sale']['success']==1)
       @foreach($result['flash_sale']['product_data'] as $key=>$products)
	   @if( date("Y-m-d",$products->server_time) >= date("Y-m-d",$products->flash_start_date))
	    var product_div_{{$products->products_id}} = 'product_div_{{$products->products_id}}';
		var  counter_id_{{$products->products_id}} = 'counter_{{$products->products_id}}';
		var inputTime_{{$products->products_id}} = "{{date('M d, Y H:i:s' ,$products->flash_expires_date)}}";

		// Set the date we're counting down to
		var countDownDate_{{$products->products_id}} = new Date(inputTime_{{$products->products_id}}).getTime();

		// Update the count down every 1 second
		var x_{{$products->products_id}} = setInterval(function() {

		  // Get todays date and time
		  var now = new Date().getTime();

		  // Find the distance between now and the count down date
		  var distance_{{$products->products_id}} = countDownDate_{{$products->products_id}} - now;

		  // Time calculations for days, hours, minutes and seconds
		  var days_{{$products->products_id}} = Math.floor(distance_{{$products->products_id}} / (1000 * 60 * 60 * 24));
		  var hours_{{$products->products_id}} = Math.floor((distance_{{$products->products_id}} % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		  var minutes_{{$products->products_id}} = Math.floor((distance_{{$products->products_id}} % (1000 * 60 * 60)) / (1000 * 60));
		  var seconds_{{$products->products_id}} = Math.floor((distance_{{$products->products_id}} % (1000 * 60)) / 1000);

		  // Display the result in the element with id="demo"
		  document.getElementById(counter_id_{{$products->products_id}}).innerHTML = days_{{$products->products_id}} + "d " + hours_{{$products->products_id}} + "h "
		  + minutes_{{$products->products_id}} + "m " + seconds_{{$products->products_id}} + "s ";

		  // If the count down is finished, write some text
		  if (distance_{{$products->products_id}} < 0) {
			clearInterval(x_{{$products->products_id}});
			//document.getElementById(counter_id_{{$products->products_id}}).innerHTML = "EXPIRED";
			document.getElementById('product_div_{{$products->products_id}}').remove();
		  }
		}, 1000);
  	   @endif
	 @endforeach
   @endif

	@if(!empty($result['detail']['product_data'][0]->flash_start_date))
		@if( $result['detail']['product_data'][0]->server_time >= $result['detail']['product_data'][0]->flash_start_date)

			var inputTime = "{{date('M d, Y H:i:s' ,$result['detail']['product_data'][0]->flash_expires_date)}}";

			var countDownDate = new Date(inputTime).getTime();

			// Update the count down every 1 second
			var x = setInterval(function() {

			  // Get todays date and time
			  var now = new Date().getTime();

			  // Find the distance between now and the count down date
			  var distance = countDownDate - now;

			  // Time calculations for days, hours, minutes and seconds
			  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
			  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

			  // Display the result in the element with id="demo"
			  document.getElementById("counter_product").innerHTML = days + "d " + hours + "h "
			  + minutes + "m " + seconds + "s ";
				document.getElementById("counter_product").style.display = 'block';
			  // If the count down is finished, write some text
			  if (distance < 0) {
				clearInterval(x);
				document.getElementById("counter_product").innerHTML = "EXPIRED";
				document.getElementById("add-to-Cart").style.display = 'none';
			  }
			}, 1000);
		@endif
	@endif
});
</script>
