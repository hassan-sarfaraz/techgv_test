<section class="product-page product-page-five ">
     <div class="container">
       <div class="product-main">
         <div class="row">
           <div class="col-12 col-sm-12">
               <div class="row justify-content-end">
                   <nav aria-label="breadcrumb">
                     <ol class="breadcrumb">
                       <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                       @if(!empty($result['category_name']) and !empty($result['sub_category_name']))
                         <li class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['category_slug'])}}">{{$result['category_name']}}</a></li>
                         <li class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['sub_category_slug'])}}">{{$result['sub_category_name']}}</a></li>
                       @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
                         <li class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['category_slug'])}}">{{$result['category_name']}}</a></li>
                       @endif
                       <li class="breadcrumb-item active">{{$result['detail']['product_data'][0]->products_name}}</li>
                     </ol>
                     </nav>
               </div>
           </div>
           <div class="col-12 col-sm-12">
             <div class="row">
               <div class="col-12 col-lg-2">
                     <div class="banner-full bg-banner-content">
                       @if(!empty($result['top_seller']))
                       <h2 class="spc-heading">Best Sellers*</h2>
                           <div id="spc1" class="owl-carousel" style="margin-bottom: 10px;">
                               @foreach($result['top_seller']['product_data'] as $key=>$products)
                               @if(++$key<=5)
                               <div class="col-12 p-1">
                               @include('web.common.product')
                               </div>
                               @endif
                               @endforeach
                           </div>
                           @endif
                     </div>
                 </div>
               <div class="col-12 col-lg-5">
                 <div class="slider-wrapper">
                     <div class="slider-for">
                       <a class="slider-for__item ex1 fancybox-button" href="{{asset('').$result['detail']['product_data'][0]->image_path }}" data-fancybox-group="fancybox-button" title="Lorem ipsum dolor sit amet">
                         <img src="{{asset('').$result['detail']['product_data'][0]->image_path }}" alt="Zoom Image" />
                       </a>
                       @foreach( $result['detail']['product_data'][0]->images as $key=>$images )
                         @if($images->image_type == 'ACTUAL')
                         <a class="slider-for__item ex1 fancybox-button" href="{{asset('').$images->image_path }}" data-fancybox-group="fancybox-button" title="Lorem ipsum dolor sit amet">
                           <img src="{{asset('').$images->image_path }}" alt="Zoom Image" />
                         </a>
                         @endif
                       @endforeach

                     </div>

                     <div class="slider-nav">
                       <div class="slider-nav__item">
                         <img src="{{asset('').$result['detail']['product_data'][0]->image_path }}" alt="Zoom Image"/>
                       </div>

                       @foreach( $result['detail']['product_data'][0]->images as $key=>$images )
                       @if($images->image_type == 'THUMBNAIL')
                       <div class="slider-nav__item">
                         <img src="{{asset('').$images->image_path }}" alt="Zoom Image"/>
                       </div>
                       @endif
                       @endforeach

                     </div>
                   </div>
               </div>
               <div class="col-12 col-lg-5 product-page-one">
                 <h1>{{$result['detail']['product_data'][0]->products_name}}</h1>
                 <div class="list-main">
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
                 <form name="attributes" id="add-Product-form" method="post" >
                     <input type="hidden" name="products_id" value="{{$result['detail']['product_data'][0]->products_id}}">

                     <input type="hidden" name="products_price" id="products_price" value="@if(!empty($result['detail']['product_data'][0]->flash_price)) {{$result['detail']['product_data'][0]->flash_price+0}} @elseif(!empty($result['detail']['product_data'][0]->discount_price)){{$result['detail']['product_data'][0]->discount_price+0}}@else{{$result['detail']['product_data'][0]->products_price+0}}@endif">

                     <input type="hidden" name="checkout" id="checkout_url" value="@if(!empty(app('request')->input('checkout'))) {{ app('request')->input('checkout') }} @else false @endif" >

                     <input type="hidden" id="max_order" value="@if(!empty($result['detail']['product_data'][0]->products_max_stock)) {{ $result['detail']['product_data'][0]->products_max_stock }} @else 0 @endif" >
                      @if(!empty($result['cart']))
                       <input type="hidden"  name="customers_basket_id" value="{{$result['cart'][0]->customers_basket_id}}" >
                      @endif
                         <div class="product-controls row">
                           @if(count($result['detail']['product_data'][0]->attributes)>0)
                           <?php
                               $index = 0;
                           ?>
                           @foreach( $result['detail']['product_data'][0]->attributes as $key=>$attributes_data )
                           <?php
                               $functionValue = 'function_'.$key++;
                           ?>
                           <input type="hidden" name="option_name[]" value="{{ $attributes_data['option']['name'] }}" >
                           <input type="hidden" name="option_id[]" value="{{ $attributes_data['option']['id'] }}" >
                           <input type="hidden" name="{{ $functionValue }}" id="{{ $functionValue }}" value="0" >
                           <input id="attributeid_<?=$index?>" type="hidden" value="">
                           <input id="attribute_sign_<?=$index?>" type="hidden" value="">
                           <input id="attributeids_<?=$index?>" type="hidden" name="attributeid[]" value="" >
                           <div class="col-12 col-md-4 box">
                             <label>{{ $attributes_data['option']['name'] }}</label>
                             <div class="select-control ">
                                 <select name="{{ $attributes_data['option']['id'] }}" onChange="getQuantity()" class="currentstock form-control attributeid_<?=$index++?>" attributeid = "{{ $attributes_data['option']['id'] }}">
                                 @if(!empty($result['cart']))
                                   @php
                                    $value_ids = array();
                                     foreach($result['cart'][0]->attributes as $values){
                                         $value_ids[] = $values->options_values_id;
                                     }
                                   @endphp
                                     @foreach($attributes_data['values'] as $values_data)
                                      @if(!empty($result['cart']))
                                      <option @if(in_array($values_data['id'],$value_ids)) selected @endif attributes_value="{{ $values_data['products_attributes_id'] }}" value="{{ $values_data['id'] }}" prefix = '{{ $values_data['price_prefix'] }}'  value_price ="{{ $values_data['price']+0 }}" >{{ $values_data['value'] }}</option>
                                      @endif
                                     @endforeach
                                   @else
                                     @foreach($attributes_data['values'] as $values_data)
                                      <option attributes_value="{{ $values_data['products_attributes_id'] }}" value="{{ $values_data['id'] }}" prefix = '{{ $values_data['price_prefix'] }}'  value_price ="{{ $values_data['price']+0 }}" >{{ $values_data['value'] }}</option>
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
                                       <button class="btn btn-defualt" type="button">-</button>
                                     </span>
                                     <input style="width:-20px;" type="text" readonly name="quantity" value=" @if(!empty($result['cart'])) {{$result['cart'][0]->customers_basket_quantity}} @else @if($result['detail']['product_data'][0]->products_min_order>0 and $result['detail']['product_data'][0]->defaultStock > $result['detail']['product_data'][0]->products_min_order) {{$result['detail']['product_data'][0]->products_min_order}} @else 1 @endif @endif" min="@if($result['detail']['product_data'][0]->products_min_order>0 and $result['detail']['product_data'][0]->defaultStock > $result['detail']['product_data'][0]->products_min_order) {{$result['detail']['product_data'][0]->products_min_order}} @else 1 @endif" max="@if(!empty($result['detail']['product_data'][0]->products_max_stock) and $result['detail']['product_data'][0]->products_max_stock>0 and $result['detail']['product_data'][0]->defaultStock > $result['detail']['product_data'][0]->products_max_stock){{ $result['detail']['product_data'][0]->products_max_stock}}@else{{ $result['detail']['product_data'][0]->defaultStock}}@endif" class="form-control qty">
                                     <span class="input-group-btn last qtyplus">
                                       <button class="btn btn-defualt" type="button">+</button>
                                     </span>
                                 </div>
                               </div>
                             </div>

                         </div>




                 <div class="product-buttons">
                     <h2>Total Price:
                       <span class="total_price">

                         <?php
                         $default_currency = DB::table('currencies')->where('is_default',1)->first();
                         if($default_currency->id == Session::get('currency_id')){
                           if(!empty($result['detail']['product_data'][0]->discount_price)){
                           $discount_price = $result['detail']['product_data'][0]->discount_price;
                           }
                           if(!empty($result['detail']['product_data'][0]->flash_price)){
                             $flash_price = $result['detail']['product_data'][0]->flash_price;
                           }
                           $orignal_price = $result['detail']['product_data'][0]->products_price;
                         }else{
                           $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();
                           if(!empty($result['detail']['product_data'][0]->discount_price)){
                           $discount_price = $result['detail']['product_data'][0]->discount_price * $session_currency->value;
                           }
                           if(!empty($result['detail']['product_data'][0]->flash_price)){
                             $flash_price = $result['detail']['product_data'][0]->flash_price * $session_currency->value;
                           }
                           $orignal_price = $result['detail']['product_data'][0]->products_price * $session_currency->value;
                         }
                          if(!empty($result['detail']['product_data'][0]->discount_price)){

                           if(($orignal_price+0)>0){
                          $discounted_price = $orignal_price-$discount_price;
                          $discount_percentage = $discounted_price/$orignal_price*100;
                          $discounted_price = $result['detail']['product_data'][0]->discount_price;

                          }else{
                            $discount_percentage = 0;
                            $discounted_price = 0;
                          }
                         }
                         else{
                           $discounted_price = $orignal_price;
                         }
                         ?>
                         @if(!empty($result['detail']['product_data'][0]->flash_price))
                         {{Session::get('symbol_left')}}{{$flash_price+0}}{{Session::get('symbol_right')}}
                         @elseif(!empty($result['detail']['product_data'][0]->discount_price))
                         {{Session::get('symbol_left')}}{{$discount_price+0}}{{Session::get('symbol_right')}}
                         @else
                         {{Session::get('symbol_left')}}{{$orignal_price+0}}{{Session::get('symbol_right')}}
                         @endif
                         </h2>
                       @if($result['detail']['product_data'][0]->products_min_order>0)
                         <p>
                         &nbsp; @lang('website.Min Order Limit:') {{ $result['detail']['product_data'][0]->products_min_order }}
                           </p>
                       @endif

                       <div class="buttons">
                        @if(!empty($result['detail']['product_data'][0]->flash_start_date) and $result['detail']['product_data'][0]->server_time < $result['detail']['product_data'][0]->flash_start_date )
                         @else
                          @if($result['detail']['product_data'][0]->products_type == 0)
                               @if($result['detail']['product_data'][0]->defaultStock == 0)
                                 <button class="btn  btn-block  btn-danger " type="button">@lang('website.Out of Stock')</button>
                               @else
                                   <button class="btn btn-block btn-secondary add-to-Cart" type="button" products_id="{{$result['detail']['product_data'][0]->products_id}}">@lang('website.Add to Cart')</button>
                               @endif
                           @else
                                <button class="btn btn-secondary btn-block  add-to-Cart stock-cart"  type="button" products_id="{{$result['detail']['product_data'][0]->products_id}}">@lang('website.Add to Cart')</button>
                                <button class="btn btn-danger btn-block  stock-out-cart" hidden type="button">@lang('website.Out of Stock')</button>
                           @endif
                         @endif
                       </div>

                 </div>
               </form>
                     <div class="row">
                         <div class="col-md-12 tab-list">
                           <div class="nav nav-pills" role="tablist">
                             <a class="nav-link nav-item nav-index active show" href="#description" id="description-tab" data-toggle="pill" role="tab">Description</a>
                           </div>
                           <div class="tab-content">
                             <div role="tabpanel" class="tab-pane fade active show" id="description" aria-labelledby="description-tab">
                               <div class="tabs-pera">
                                   <p>
                                    <?=stripslashes($result['detail']['product_data'][0]->products_description)?>
                                   </p>

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
                     <div class="row">
                         <div class="col-12">
                           <ul class="add-favrt">
                             <li><a href="#" class="icon active is_liked" products_id="<?=$result['detail']['product_data'][0]->products_id?>"><i class="fas fa-heart"></i>Add to Wishlist</a></li>
                             <li><a href="#" onclick="myFunction3({{$result['detail']['product_data'][0]->products_id}})"><i class="fas fa-align-right" style="    transform: rotate(90deg);"></i>Add to Compare</a></li>
                           </ul>
                           </div>
                     </div>

               </div>

             </div>
           </div>
         </div>


       </div>
       <div class="products-area">
         <div class="row">
             <div class="col-12">
               <div class="heading">
                 <h2>
                   @lang('website.Related Products')
                 </h2>
                 <hr style="margin-bottom: 0;">
               </div>
               <div id="p2" class="owl-carousel" style="margin-bottom: 10px;">
                 @foreach($result['simliar_products']['product_data'] as $key=>$products)
                 @if($result['detail']['product_data'][0]->products_id != $products->products_id)
                 @if(++$key<=5)
                 @include('web.common.product')
                 @endif
                 @endif
                 @endforeach
               </div>
             </div>
           </div>
     </div>
     </div>
   </section>
