<section class="shop-content shop-two">

   <div class="container">
       <div class="breadcum-area">
           <h2>Shop</h2>
           <ol class="breadcrumb">
             @if(!empty($result['category_name']) and !empty($result['sub_category_name']))
             <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
             <li  class="breadcrumb-item"><a href="{{ URL::to('/shop')}}">@lang('website.Shop')</a></li>
             <li  class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['category_slug'])}}">{{$result['category_name']}}</a></li>
             <li  class="breadcrumb-item active">{{$result['sub_category_name']}}</li>
             @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
             <li class="breadcrumb-item active">{{$result['category_name']}}</li>
             @else
             <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
             <li class="breadcrumb-item active">@lang('website.Shop')</li>
             @endif
           </ol>
       </div>
       <div class="row">
         <div class="col-12 col-lg-9">
           <div class="products-area">
               <div class="top-bar">
                   <div class="row">
                     <div class="col-12 col-lg-12">
                       <div class="row">
                       <div class="col-12 col-xl-6">
                         <div class="block">
                           <label>@lang('website.Display')</label>
                           <label>
                               <a href="javascript:void(0);" id="grid"><i class="fas fa-th-large"></i></a>
                               <a href="javascript:void(0);" id="list"><i class="fas fa-list"></i></a>
                           </label>
                         </div>
                       </div>
                       <div class="col-12 col-xl-6">
                         <form class="form-inline" method="get"enctype="multipart/form-data" id="load_products_form">
                           @if(!empty(app('request')->input('search')))
                            <input type="hidden"  name="search" value="{{ app('request')->input('search') }}">
                           @endif
                           @if(!empty(app('request')->input('category')))
                            <input type="hidden"  name="category" value="@if(app('request')->input('category')!='all'){{ app('request')->input('category') }} @endif">
                           @endif
                            <input type="hidden"  name="load_products" value="1">

                           <label>@lang('website.Sort')</label>
                           <div class="form-group">
                               <select name="type" id="sortbytype" class="form-control">
                                 <option value="desc" @if(app('request')->input('type')=='desc') selected @endif>@lang('website.Newest')</option>
                                 <option value="atoz" @if(app('request')->input('type')=='atoz') selected @endif>@lang('website.A - Z')</option>
                                 <option value="ztoa" @if(app('request')->input('type')=='ztoa') selected @endif>@lang('website.Z - A')</option>
                                 <option value="hightolow" @if(app('request')->input('type')=='hightolow') selected @endif>@lang('website.Price: High To Low')</option>
                                 <option value="lowtohigh" @if(app('request')->input('type')=='lowtohigh') selected @endif>@lang('website.Price: Low To High')</option>
                                 <option value="topseller" @if(app('request')->input('type')=='topseller') selected @endif>@lang('website.Top Seller')</option>
                                 <option value="special" @if(app('request')->input('type')=='special') selected @endif>@lang('website.Special Products')</option>
                                 <option value="mostliked" @if(app('request')->input('type')=='mostliked') selected @endif>@lang('website.Most Liked')</option>
                                 </select>
                           </div>

                           <label>@lang('website.Limit')</label>
                           <div class="form-group">
                               <select class="form-control"name="limit"id="sortbylimit">
                                 <option value="15" @if(app('request')->input('limit')=='15') selected @endif>15</option>
                                 <option value="30" @if(app('request')->input('limit')=='30') selected @endif>30</option>
                                 <option value="60" @if(app('request')->input('limit')=='60') selected @endif>60</option>
                                 </select>
                           </div>
                           <label>@lang('website.per page')</label>

                         @include('web.common.scripts.shop_page_load_products')
                      </div>
                     </div>
                     </div>
                   </div>
                 </div>
                 @if(!empty(app('request')->input('search')))
                     <div class="search-result">
                         <h4>@lang('website.Search result for') '{{app('request')->input('search')}}' @if($result['products']['total_record']>0) {{$result['products']['total_record']}} @else 0 @endif @lang('website.item found') <h4>
                     </div>
                 @endif
                 <div class="row">

                   <div id="swap" class="col-12 col-sm-12">
                     <div class="row">
                       @if($result['products']['success']==1)
                       @foreach($result['products']['product_data'] as $key=>$products)

                       <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                           <!-- Product -->
                           <div class="product">
                             <article>
                               <div class="thumb">
                                   <div class="icons mobile-icons d-lg-none d-xl-none">
                                     <div class="icon-liked">
                                       <a class="icon active is_liked" products_id="<?=$products->products_id?>">
                                         <i class="fas fa-heart"></i>
                                         <span  class="badge badge-secondary counter"  >{{$products->products_liked}}</span>
                                       </a>
                                     </div>
                                     <div class="icon modal_show" data-toggle="modal" data-target="#myModal" products_id="{{$products->products_id}}"><i class="fas fa-eye"></i></div>
                                       <a onclick="myFunction3({{$products->products_id}})"class="icon"><i class="fas fa-align-right" data-fa-transform="rotate-90"></i></a>
                                     </div>
                                 <img class="img-fluid" src="{{asset('').$products->image_path}}" alt="{{$products->products_name}}">
                               </div>
                               @include('web.common.scripts.addToCompare')
                               <?php
                                  $default_currency = DB::table('currencies')->where('is_default',1)->first();
                                  if($default_currency->id == Session::get('currency_id')){
                                    if(!empty($products->discount_price)){
                                    $discount_price = $products->discount_price;
                                    }
                                    $orignal_price = $products->products_price;
                                    $tax = ($orignal_price* Config::get('global.p_constant'));
                                    $orignal_price = $orignal_price + $tax;
                                    //$orignal_price = round($orignal_price, 2);
                                  }else{
                                    $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();
                                    if(!empty($products->discount_price)){
                                      $discount_price = $products->discount_price * $session_currency->value;
                                    }
                                    $orignal_price = $products->products_price * $session_currency->value;
                                    $tax = ($orignal_price* Config::get('global.p_constant'));
                                    $orignal_price = $orignal_price + $tax;
                                    //$orignal_price = round($orignal_price, 2);
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
                               <span class="tag">
                                 @foreach($products->categories as $key=>$category)
                                     {{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif
                                 @endforeach
                               </span>
                               <h2 class="title text-center"><a href="{{ URL::to('/product-detail/'.$products->products_slug)}}">{{$products->products_name}}</a></h2>
                               <p class="discription">{{$products->products_description}}</p>

                               <div class="price">
                                 @if(!empty($products->discount_price))
                                 {{Session::get('symbol_left')}}{{$discount_price+0}}{{Session::get('symbol_right')}}
                                 <span> {{Session::get('symbol_left')}}{{$orignal_price+0}}{{Session::get('symbol_right')}}</span>
                                 @else
                                 {{Session::get('symbol_left')}}{{$orignal_price+0}}{{Session::get('symbol_right')}}
                                 @endif
                                 <div class="buttons listview-btn">
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
                       @endif
                       @include('web.common.scripts.addToCompare')
                     </div>
                   </div>
                 </div>
           </div>
           <div class="toolbar mt-3">
                             <div class="form-inline">
                                 <div class="form-group  justify-content-start col-6">
                                   <input id="record_limit" type="hidden" value="{{$result['limit']}}">
                                   <input id="total_record" type="hidden" value="{{$result['products']['total_record']}}">
                                     <label for="staticEmail" class="col-form-label"> @lang('website.Showing')&nbsp;<span class="showing_record">{{$result['limit']}} </span> &nbsp; @lang('website.of')  &nbsp;<span class="showing_total_record">{{$result['products']['total_record']}}</span> &nbsp;@lang('website.results')</label>
                                 </div>
                                 <div class="form-group justify-content-end col-6">
                                     <input type="hidden" value="1" name="page_number" id="page_number">
                                <?php
                                         if(!empty(app('request')->input('limit'))){
                                             $record = app('request')->input('limit');
                                         }else{
                                             $record = '15';
                                         }
                                     ?>
                                     <button class="btn btn-dark" type="button" id="load_products"
                                     @if(count($result['products']['product_data']) < $record )
                                         style="display:none"
                                     @endif
                                     >@lang('website.Load More')</button>
                                 </div>
                             </div>
           </div>
         </form>
         </div>
         <div class="col-12 col-lg-3  d-lg-block d-xl-block right-menu">
           <div class="right-menu-categories">
            @if(!empty($result['categories']))
             @foreach($result['categories'] as $category)
             <a class=" main-manu"  @if(array_key_exists("childs",$category)) href="#{{$category->slug}}" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="men-cloth" @else href="{{url('shop?category=').$category->slug}}" @endif>
               <img class="img-fuild" src="{{asset($category->image_path)}}">
                  {{$category->categories_name}} <span><i class="fas fa-minus"></i></span>

             </a>
             <div class="sub-manu collapse multi-collapse" id="{{$category->slug}}">
               <ul class="unorder-list">
                 @if(array_key_exists("childs",$category))
                 @foreach($category->childs as $cat)
                   <li class="list-item">
                     <a class="list-link"  href="{{url('shop?category=').$cat->slug}}" >
                         <i class="fas fa-angle-right"></i>{{$cat->categories_name}}
                     </a>
                   </li>
                   @endforeach
                   @endif
               </ul>
             </div>
             @endforeach
            @endif

           </div>
           @if(!empty($result['categories']))
           <form enctype="multipart/form-data" name="filters" id="test" method="get">
             <input type="hidden" name="min_price" id="min_price" value="0">
             <input type="hidden" name="max_price" id="max_price" value="{{$result['filters']['maxPrice']}}">
             @if(app('request')->input('category'))
              <input type="hidden" name="category" value="{{app('request')->input('category')}}">
             @endif
             @if(app('request')->input('filters_applied')==1)
             <input type="hidden" name="filters_applied" id="filters_applied" value="1">
             <input type="hidden" name="options" id="options" value="<?php echo implode($result['filter_attribute']['options'],',')?>">
             <input type="hidden" name="options_value" id="options_value" value="<?php echo implode($result['filter_attribute']['option_values'], ',')?>">
             @else
             <input type="hidden" name="filters_applied" id="filters_applied" value="0">
             @endif

             @if(!empty($result['products']['success']) and $result['products']['success'] == 0)
             <div class="range-slider-main">
               <h2>Price Range</h2>
               <div class="wrapper">
                   <div class="range-slider">
                       <input onChange="getComboA(this)" name="price" type="text" class="js-range-slider" value="" />
                   </div>
                   <div class="extra-controls form-inline">
                     <div class="form-group">
                         <span>
                           @if(session('symbol_left') != null)
                           <font>{{session('symbol_left')}}</font>
                           @else
                           <font>{{session('symbol_right')}}</font>
                           @endif
                               <input type="text"  class="js-input-from form-control" value="0" />
                         </span>
                             <font>-</font>
                             <span>
                               @if(session('symbol_left') != null)
                               <font>{{session('symbol_left')}}</font>
                               @else
                               <font>{{session('symbol_right')}}</font>
                               @endif
                                 <input  type="text" class="js-input-to form-control" value="0" />
                                 <input  type="hidden" class="maximum_price" value="{{$result['filters']['maxPrice']}}">
                                 </span>
                   </div>
                     </div>
               </div>
             </div>
             @endif
             @include('web.common.scripts.slider')
                   @if(count($result['filters']['attr_data'])>0)
                   @foreach($result['filters']['attr_data'] as $key=>$attr_data)
                   <div class="color-range-main">
                     <h1 @if(count($result['filters']['attr_data'])==$key+1) last @endif>{{$attr_data['option']['name']}}</h1>
                       <div class="block">
                              <div class="card-body">
                               <ul class="list" style="list-style:none; padding: 0px;">
                                   @foreach($attr_data['values'] as $key=>$values)
                                   <li >
                                       <div class="form-check">
                                         <label class="form-check-label">
                                           <input class="form-check-input filters_box" name="{{$attr_data['option']['name']}}[]" type="checkbox" value="{{$values['value']}}" 								 									<?php
                 if(!empty($result['filter_attribute']['option_values']) and in_array($values['value_id'],$result['filter_attribute']['option_values'])) print 'checked';
                                           ?>>
                                           {{$values['value']}}
                                         </label>
                                       </div>
                                   </li>
                                   @endforeach
                               </ul>
                           </div>
                       </div>

                     </div>
                   @endforeach
                   @endif
                   <div class="color-range-main">

                   <div class="alret alert-danger" id="filter_required">
                   </div>

                   <div class="button">
                   <?php
               $url = '';
                     if(isset($_REQUEST['category'])){
                 $url = "?category=".$_REQUEST['category'];
                 $sign = '&';
               }else{
                 $sign = '?';
               }
               if(isset($_REQUEST['search'])){
                 $url.= $sign."search=".$_REQUEST['search'];
               }
             ?>
                 <a href="{{ URL::to('/shop')}}" class="btn btn-dark" id="apply_options"> @lang('website.Reset') </a>
                    @if(app('request')->input('filters_applied')==1)
                 <button type="button" class="btn btn-secondary" id="apply_options_btn"> @lang('website.Apply')</button>
                   @else
                 <!--<button type="button" class="btn btn-secondary" id="apply_options_btn" disabled> @lang('website.Apply')</button>-->
                   <button type="button" class="btn btn-secondary" id="apply_options_btn" > @lang('website.Apply')</button>
                   @endif
               </div>
             </div>
                   @if(count($result['commonContent']['homeBanners'])>0)
                    @foreach(($result['commonContent']['homeBanners']) as $homeBanners)
                       @if($homeBanners->type==7)
                       <div class="img-main">
                           <a href="{{ $homeBanners->banners_url}}" ><img class="img-fluid" src="{{asset('').$homeBanners->path}}"></a>
                       </div>
                     @endif
                    @endforeach
                   @endif
             </form>
             @endif
         </div>
        </div>
    </div>

   </div>
 </section>














     <footer id="footerMobile" class="footer-area footer-mobile d-lg-none d-xl-none">
       <div class="container-fluid p-0">
         <div class="container">
           <div class="row">
             <div class="col-12 col-md-8">
                 <div class="single-footer display-mobile">
                     <h5>Newsletter</h5>
                     <div class="row">
                       <div class="col-7 col-md-8">
                         <hr>
                       </div>
                     </div>
                   </div>
                     <div class="newsletter">
                         <div class="block">
                             <form class="form-inline">
                                 <div class="search">
                                   <input  type="search" placeholder="Confirm your email..">
                                   <button class="btn btn-secondary subscribe" type="submit">
                                     SUBSCRIBE
                                     </button>
                                     <button class="btn-secondary fas fa-location-arrow" type="submit">
                                     </button>
                                 </div>
                             </form>
                         </div>
                     </div>

             </div>
             <div class="col-12 col-md-4">
               <div class="single-footer display-mobile">
                   <h5>follow us</h5>
                   <div class="row">
                     <div class="col-7 col-md-8">
                       <hr>
                     </div>
                   </div>
                 </div>
                   <div class="socials">
                       <ul class="list">
                           <li><a href="#" class="fab fa-facebook-f"></a></li>
                           <li><a href="#" class="fab fa-twitter"></a></li>
                           <li><a href="#" class="fab fa-skype"></a></li>
                           <li><a href="#" class="fab fa-linkedin-in"></a></li>
                           <li><a href="#" class="fab fa-instagram"></a></li>
                       </ul>
                   </div>
             </div>
           </div>
         </div>
       </div>
       <div class="container-fluid px-0  footer-inner">
         <div class="container">
           <div class="row">
             <div class="col-12 col-md-4">
               <div class="single-footer">
                 <h5>About Store</h5>
                 <div class="row">
                   <div class="col-7 col-md-8">
                     <hr>
                   </div>
                 </div>
                 <ul class="contact-list  pl-0 mb-0">
                   <li> <i class="fas fa-map-marker"></i><span>Address City State, Zip Country</span> </li>
                   <li> <i class="fas fa-phone"></i><span>(888 - 963 - 600)</span> </li>
                   <li> <i class="fas fa-envelope"></i><span> <a href="#">info@ekommerce.com</a> </span> </li>

                 </ul>
               </div>
             </div>
             <div class="col-12 col-md-4">
               <div class="footer-block">
                 <div class="single-footer single-footer-left">
                   <h5>Our Services</h5>
                   <div class="row">
                       <div class="col-7 col-md-8">
                         <hr>
                       </div>
                   </div>
                   <ul class="links-list pl-0 mb-0">
                     <li> <a href="index.html"><i class="fa fa-angle-right"></i>Home</a> </li>
                     <li> <a href="#"><i class="fa fa-angle-right"></i>Shop</a> </li>
                     <li> <a href="orders.html"><i class="fa fa-angle-right"></i>Orders</a> </li>
                     <li> <a href="cart-page1.html"><i class="fa fa-angle-right"></i>Shopping Cart</a> </li>
                       <li> <a href="wishlist.html"><i class="fa fa-angle-right"></i>Wishlist</a> </li>
                   </ul>
                 </div>
               </div>
             </div>
             <div class="col-12 col-md-4 ">
               <div class="single-footer single-footer-right">
                 <h5>Information</h5>
                 <div class="row">
                   <div class="col-7 col-md-8">
                     <hr>
                   </div>
                 </div>
                 <ul class="links-list pl-0 mb-0">
                   <li> <a href="about-page1.html"><i class="fa fa-angle-right" ></i>About Us</a> </li>
                   <li> <a href="privacy.html"><i class="fa fa-angle-right" ></i>Privacy Policy</a> </li>
                   <li> <a href="refund.html"><i class="fa fa-angle-right"></i>Refund Policy</a> </li>
                   <li> <a href="term.html"><i class="fa fa-angle-right"></i>Term &amp; Services</a> </li>
                   <li> <a href="contact-page1.html"><i class="fa fa-angle-right"></i>Contact Us</a> </li>
                 </ul>
               </div>
             </div>
           </div>
         </div>
       </div>
       <div class="container-fluid p-0">
         <div class="copyright-content">
             <div class="container">
               <div class="row align-items-center">

                   <div class="col-12 col-md-6">
                     <div class="footer-info">
                       &copy;&nbsp;2019 Company, Inc. <a href="privacy.html">Privacy</a>&nbsp;&bull;&nbsp;<a href="term.html">Terms</a>
                     </div>

                   </div>
                   <div class="col-12 col-md-6">
                       <div class="footer-image">
                           <img class="img-fluid" src="images/miscellaneous/payments.png">
                       </div>

                   </div>
               </div>
             </div>
         </div>
       </div>

     </footer>

     <!-- //footer style One -->
     <!-- <footer id="footerOne" class="footer-area footer-one footer-desktop d-none d-lg-block d-xl-block">
         <div class="container">
           <div class="row">
             <div class="col-12 col-lg-3">
               <div class="single-footer">
                 <h5>About Store</h5>
                 <div class="row">
                   <div class="col-12 col-lg-8">
                     <hr>
                   </div>
                 </div>
                 <ul class="contact-list  pl-0 mb-0">
                   <li> <i class="fas fa-map-marker"></i><span>Address City State, Zip Country</span> </li>
                   <li> <i class="fas fa-phone"></i><span>(888 - 963 - 600)</span> </li>
                   <li> <i class="fas fa-envelope"></i><span> <a href="#">info@ekommerce.com</a> </span> </li>

                 </ul>
               </div>
             </div>
             <div class="col-12 col-md-6 col-lg-3">
               <div class="footer-block">
                   <div class="single-footer single-footer-left">
                     <h5>Our Services</h5>
                     <div class="row">
                         <div class="col-12 col-lg-8">
                           <hr>
                         </div>
                       </div>
                     <ul class="links-list pl-0 mb-0">
                       <li> <a href="index.html"><i class="fa fa-angle-right"></i>Home</a> </li>
                     <li> <a href="#"><i class="fa fa-angle-right"></i>Shop</a> </li>
                     <li> <a href="orders.html"><i class="fa fa-angle-right"></i>Orders</a> </li>
                     <li> <a href="cart-page1.html"><i class="fa fa-angle-right"></i>Shopping Cart</a> </li>
                       <li> <a href="wishlist.html"><i class="fa fa-angle-right"></i>Wishlist</a> </li>
                     </ul>
                   </div>

               </div>
             </div>
             <div class="col-12 col-md-6 col-lg-3">
               <div class="single-footer single-footer-right">
                 <h5>Information</h5>
                 <div class="row">
                     <div class="col-12 col-lg-8">
                       <hr>
                     </div>
                   </div>
                 <ul class="links-list pl-0 mb-0">
                   <li> <a href="about-page1.html"><i class="fa fa-angle-right" ></i>About Us</a> </li>
                       <li> <a href="privacy.html"><i class="fa fa-angle-right" ></i>Privacy Policy</a> </li>
                       <li> <a href="refund.html"><i class="fa fa-angle-right"></i>Refund Policy</a> </li>
                       <li> <a href="term.html"><i class="fa fa-angle-right"></i>Term &amp; Services</a> </li>
                       <li> <a href="contact-page1.html"><i class="fa fa-angle-right"></i>Contact Us</a> </li>
                 </ul>
               </div>
             </div>

             <div class="col-12 col-lg-3">
               <div class="single-footer">
                   <div class="newsletter">
                       <h5>Newsletter</h5>
                       <div class="row">
                           <div class="col-12 col-lg-8">
                             <hr>
                           </div>
                         </div>
                       <div class="block">
                           <form class="form-inline">
                               <div class="search">
                                 <input  type="search" placeholder="Confirm your email..">
                                 <button class="btn btn-secondary subscribe" type="submit">
                                   SUBSCRIBE
                                   </button>
                                   <button class="btn-secondary fas fa-location-arrow" type="submit">
                                   </button>
                               </div>
                             </form>
                       </div>
                   </div>

                   <div class="socials">
                       <h5>Follow Us</h5>
                       <div class="row">
                           <div class="col-12 col-lg-8">
                             <hr>
                           </div>
                         </div>
                       <ul class="list">
                           <li><a href="#" class="fab fa-facebook-f"></a></li>
                           <li><a href="#" class="fab fa-twitter"></a></li>
                           <li><a href="#" class="fab fa-skype"></a></li>
                           <li><a href="#" class="fab fa-linkedin-in"></a></li>
                           <li><a href="#" class="fab fa-instagram"></a></li>
                       </ul>
                   </div>

               </div>
             </div>
           </div>
         </div>
         <div class="container-fluid p-0">
             <div class="copyright-content">
                 <div class="container">
                   <div class="row align-items-center">
                       <div class="col-12 col-md-6">
                         <div class="footer-image">
                             <img class="img-fluid" src="images/miscellaneous/payments.png">
                         </div>

                       </div>
                       <div class="col-12 col-md-6">
                         <div class="footer-info">
                             &copy;&nbsp;2019 Company, Inc. <a href="privacy.html">Privacy</a>&nbsp;&bull;&nbsp;<a href="term.html">Terms</a>
                         </div>

                       </div>
                   </div>
                 </div>
               </div>
         </div>
     </footer> -->

     <!-- //footer style Two -->
     <!-- <footer id="footerTwo" class="footer-area footer-two footer-desktop d-none d-lg-block d-xl-block">
         <div class="container">
           <div class="row">
             <div class="col-12 col-lg-3">
               <div class="single-footer">
                 <h5>About Store</h5>
                 <div class="row">
                   <div class="col-12 col-lg-8">
                     <hr>
                   </div>
                 </div>
                 <ul class="contact-list  pl-0 mb-0">
                   <li> <i class="fas fa-map-marker"></i><span>Address City State, Zip Country</span> </li>
                   <li> <i class="fas fa-phone"></i><span>(888 - 963 - 600)</span> </li>
                   <li> <i class="fas fa-envelope"></i><span> <a href="#">info@ekommerce.com</a> </span> </li>

                 </ul>
               </div>
             </div>
             <div class="col-12 col-md-6 col-lg-3">
               <div class="footer-block">
                   <div class="single-footer single-footer-left">
                     <h5>Our Services</h5>
                     <div class="row">
                         <div class="col-12 col-lg-8">
                           <hr>
                         </div>
                       </div>
                     <ul class="links-list pl-0 mb-0">
                       <li> <a href="index.html"><i class="fa fa-angle-right"></i>Home</a> </li>
                     <li> <a href="#"><i class="fa fa-angle-right"></i>Shop</a> </li>
                     <li> <a href="orders.html"><i class="fa fa-angle-right"></i>Orders</a> </li>
                     <li> <a href="cart-page1.html"><i class="fa fa-angle-right"></i>Shopping Cart</a> </li>
                       <li> <a href="wishlist.html"><i class="fa fa-angle-right"></i>Wishlist</a> </li>
                     </ul>
                   </div>

               </div>
             </div>
             <div class="col-12 col-md-6 col-lg-3">
               <div class="single-footer single-footer-right">
                 <h5>Information</h5>
                 <div class="row">
                     <div class="col-12 col-lg-8">
                       <hr>
                     </div>
                   </div>
                 <ul class="links-list pl-0 mb-0">
                   <li> <a href="about-page1.html"><i class="fa fa-angle-right" ></i>About Us</a> </li>
                   <li> <a href="privacy.html"><i class="fa fa-angle-right" ></i>Privacy Policy</a> </li>
                   <li> <a href="refund.html"><i class="fa fa-angle-right"></i>Refund Policy</a> </li>
                   <li> <a href="term.html"><i class="fa fa-angle-right"></i>Term &amp; Services</a> </li>
                   <li> <a href="contact-page1.html"><i class="fa fa-angle-right"></i>Contact Us</a> </li>
                 </ul>
               </div>
             </div>

             <div class="col-12 col-lg-3">
               <div class="single-footer">
                   <div class="newsletter">
                       <h5>Newsletter</h5>
                       <div class="row">
                           <div class="col-12 col-lg-8">
                             <hr>
                           </div>
                         </div>
                       <div class="block">
                           <form class="form-inline">
                               <div class="search">
                                 <input  type="search" placeholder="Confirm your email..">
                                 <button class="btn btn-secondary subscribe" type="submit">
                                   SUBSCRIBE
                                   </button>
                                   <button class="btn-secondary fas fa-location-arrow" type="submit">
                                   </button>
                               </div>
                             </form>
                       </div>
                   </div>

                   <div class="socials">
                       <h5>Follow Us</h5>
                       <div class="row">
                           <div class="col-12 col-lg-8">
                             <hr>
                           </div>
                         </div>
                       <ul class="list">
                           <li><a href="#" class="fab fa-facebook-f"></a></li>
                           <li><a href="#" class="fab fa-twitter"></a></li>
                           <li><a href="#" class="fab fa-skype"></a></li>
                           <li><a href="#" class="fab fa-linkedin-in"></a></li>
                           <li><a href="#" class="fab fa-instagram"></a></li>
                       </ul>
                   </div>

               </div>
             </div>
           </div>
         </div>
         <div class="container-fluid p-0">
             <div class="copyright-content">
                 <div class="container">
                   <div class="row align-items-center">
                       <div class="col-12 col-md-6">
                         <div class="footer-image">
                             <img class="img-fluid" src="images/miscellaneous/payments.png">
                         </div>

                       </div>
                       <div class="col-12 col-md-6">
                         <div class="footer-info">
                           &copy;&nbsp;2019 Company, Inc. <a href="privacy.html">Privacy</a>&nbsp;&bull;&nbsp;<a href="term.html">Terms</a>
                         </div>

                       </div>
                   </div>
                 </div>
               </div>
         </div>
     </footer> -->

     <!-- //footer style Three -->
     <!-- <footer id="footerThree"  class="footer-area footer-three footer-desktop d-none d-lg-block d-xl-block">
         <div class="container">
           <div class="row">
             <div class="col-12 col-md-6 col-lg-3">
                 <a class="logo" href="index.html">
                 <strong>E</strong>KOMMERCE
               </a>
                 <p>
                     This is Photoshop's version of Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                     sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.

                 </p>
             </div>
             <div class="col-12 col-md-6 col-lg-3">
                   <div class="single-footer single-footer-left">
                     <h5>Our Services</h5>
                     <div class="row">
                         <div class="col-12 col-lg-8">
                           <hr>
                         </div>
                       </div>
                     <ul class="links-list pl-0 mb-0">
                       <li> <a href="index.html"><i class="fa fa-angle-right"></i>Home</a> </li>
                     <li> <a href="#"><i class="fa fa-angle-right"></i>Shop</a> </li>
                     <li> <a href="orders.html"><i class="fa fa-angle-right"></i>Orders</a> </li>
                     <li> <a href="cart-page1.html"><i class="fa fa-angle-right"></i>Shopping Cart</a> </li>
                       <li> <a href="wishlist.html"><i class="fa fa-angle-right"></i>Wishlist</a> </li>
                     </ul>
                   </div>
             </div>
             <div class="col-12 col-md-6 col-lg-3">
               <h5>Information</h5>
               <div class="row">
                 <div class="col-12 col-lg-8">
                   <hr>
                 </div>
               </div>
               <ul class="links-list pl-0 mb-0">
                 <li> <a href="about-page1.html"><i class="fa fa-angle-right" ></i>About Us</a> </li>
                       <li> <a href="privacy.html"><i class="fa fa-angle-right" ></i>Privacy Policy</a> </li>
                       <li> <a href="refund.html"><i class="fa fa-angle-right"></i>Refund Policy</a> </li>
                       <li> <a href="term.html"><i class="fa fa-angle-right"></i>Term &amp; Services</a> </li>
                       <li> <a href="contact-page1.html"><i class="fa fa-angle-right"></i>Contact Us</a> </li>
               </ul>
             </div>
             <div class="col-12 col-lg-3">
                 <h5>About Store</h5>
                 <div class="row">
                   <div class="col-12 col-lg-8">
                     <hr>
                   </div>
                 </div>
                 <ul class="contact-list  pl-0 mb-0">
                   <li> <i class="fas fa-map-marker"></i><span>Address City State, Zip Country</span> </li>
                   <li> <i class="fas fa-phone"></i><span>(888 - 963 - 600)</span> </li>
                   <li> <i class="fas fa-envelope"></i><span> <a href="#">info@ekommerce.com</a> </span> </li>
                 </ul>

             </div>
           </div>
         </div>
         <div class="container-fluid p-0">
             <div class="social-content">
                 <div class="container">
                   <div class="row align-items-center">
                       <div class="col-12 col-md-6">
                           <div class="socials">
                               <ul class="list">
                                   <li><a href="#" class="fab fa-facebook-f"></a></li>
                                   <li><a href="#" class="fab fa-twitter"></a></li>
                                   <li><a href="#" class="fab fa-skype"></a></li>
                                   <li><a href="#" class="fab fa-linkedin-in"></a></li>
                                   <li><a href="#" class="fab fa-instagram"></a></li>
                               </ul>
                           </div>
                       </div>
                       <div class="col-12 col-md-6">
                           <div class="newsletter">

                               <div class="block">
                                   <h5>Newsletter</h5>
                                   <form class="form-inline">
                                       <div class="search">
                                         <input  type="search" placeholder="Confirm your email..">
                                         <button class="btn btn-secondary" type="submit">
                                         SUBSCRIBE
                                         </button>
                                       </div>
                                     </form>
                               </div>
                       </div>
                   </div>
                   </div>
                 </div>

             </div>
         </div>
         <div class="container-fluid p-0">
             <div class="copyright-content">
                 <div class="container">
                   <div class="row align-items-center">
                       <div class="col-5">
                         <div class="footer-image">
                             <img class="img-fluid" src="images/miscellaneous/payments.png">
                         </div>
                       </div>
                       <div class="col-7">
                         <div class="footer-info">
                             <p>&copy;&nbsp;2019 Company, Inc. <a href="privacy.html">Privacy</a>&nbsp;&bull;&nbsp;<a href="term.html">Terms</a></p>
                             <p>this is a demo store.Any orders placed through this store will not be honored or fulfilied</p>
                         </div>

                       </div>
                   </div>
                 </div>
             </div>

         </div>
     </footer> -->

     <!-- //footer style Four -->
     <!-- <footer id="footerFour"  class="footer-area footer-four footer-desktop d-none d-lg-block d-xl-block">
         <div class="container">
           <div class="row justify-content-between">
             <div class="col-12 col-lg-3">
                 <a class="logo" href="index.html">
                 <strong>E</strong>KOMMERCE
               </a>
                 <p>
                     This is Photoshop's version of Lorem<br> ipsum dolor sit amet, consectetur<br> adipisicing elit,
                       sed do eiusmod tempor<br> incididunt.
                 </p>
                 <ul class="contact-list  pl-0 mb-0">
                   <li> <i class="fas fa-map-marker"></i><span>Address City State, Zip Country</span> </li>
                   <li> <i class="fas fa-phone"></i><span>(888 - 963 - 600)</span> </li>
                   <li> <i class="fas fa-envelope"></i><span> <a href="#">info@ekommerce.com</a> </span> </li>
                 </ul>

             </div>
             <div class="col-12 col-lg-4">
               <h5>
                 QUICK CONTACT
               </h5>
               <div class="form">
                   <input type="text" placeholder="Your Name">
                   <input type="email" placeholder="Confirm your email...">
                   <textarea name="message"  placeholder="Describe your comments here..." rows="5" cols="56"></textarea>
                   <button class="btn btn-secondary" type="submit">
                       SUBMIT
                   </button>
               </div>
             </div>
             <div class="col-12 col-lg-3">
               <div class="newsletter">
                   <h5>Newsletter</h5>
                   <div class="block">
                       <form class="form-inline">
                           <div class="search">
                             <input  type="search" placeholder="Confirm your email..">
                             <button class="btn btn-secondary" type="submit">
                             SUBSCRIBE
                             </button>
                           </div>
                         </form>
                   </div>
               </div>
               <div class="socials">
                   <h5>FOLLOW US</h5>
                   <ul class="list">
                       <li><a href="#" class="fab fa-facebook-f"></a></li>
                       <li><a href="#" class="fab fa-twitter"></a></li>
                       <li><a href="#" class="fab fa-skype"></a></li>
                       <li><a href="#" class="fab fa-linkedin-in"></a></li>
                       <li><a href="#" class="fab fa-instagram"></a></li>
                   </ul>
               </div>
             </div>
           </div>
         </div>
         <div class="container-fluid p-0">
             <div class="copyright-content">
                 <div class="container">
                   <div class="row align-items-center">
                       <div class="col-5">
                         <div class="footer-image">
                             <img class="img-fluid" src="images/miscellaneous/payments.png">
                         </div>
                       </div>
                       <div class="col-7">
                         <div class="footer-info">
                             <p>&copy;&nbsp;2019 Company, Inc. <a href="privacy.html">Privacy</a>&nbsp;&bull;&nbsp;<a href="term.html">Terms</a></p>
                             <p>this is a demo store.Any orders placed through this store will not be honored or fulfilied</p>
                         </div>

                       </div>
                   </div>
                 </div>
               </div>
         </div>
     </footer> -->

     <!-- //footer style Five  -->
     <!-- <footer id="footerFive"  class="footer-area footer-five footer-desktop d-none d-lg-block d-xl-block">
             <div class="container">
               <div class="row">
                 <div class="col-12 col-lg-5">
                     <h5>RECENT POST</h5>
                     <div class="row">
                       <div class="col-12 col-lg-6">
                         <hr>
                       </div>
                     </div>
                     <div class="row">
                       <div class="col-6 pr-0">
                         <div class="media ">
                           <img src="images/product_images/woman_brown_bag.jpg" alt="Woman holding brown and pink floral leather bag" class="margin-d2" style="width:60px;">
                           <div class="media-body">

                             <h2>Woman holding brown and pink floral leather bag</h2>
                             <small>MARCH 6, 2019</small>
                           </div>
                         </div>
                       </div>
                       <div class="col-6  pr-0">
                         <div class="media">
                           <img src="images/product_images/brown_wooden.jpg" alt="Brown wooden dresser with mirror" class="margin-d2" style="width:60px;">
                           <div class="media-body">

                             <h2>Brown wooden dresser with mirror</h2>
                             <small>MARCH 6, 2019</small>
                           </div>
                         </div>
                       </div>
                     </div>
                     <div class="row">
                       <div class="col-6 pr-0">
                         <div class="media ">
                           <img src="images/product_images/ariel_view.jpg" alt="Aerial view of man's feet and city buildings" class="margin-d2" style="width:60px;">
                           <div class="media-body">

                             <h2>Aerial view of man's feet and city buildings</h2>
                             <small>MARCH 6, 2019</small>
                           </div>
                         </div>
                       </div>
                       <div class="col-6 pr-0">
                         <div class="media">
                           <img src="images/product_images/pair_red_shoes.jpg" alt="Pair of red patent leather heels on wooden surface" class="margin-d2" style="width:60px;">
                           <div class="media-body">

                             <h2>Pair of red patent leather heels on wooden surface</h2>
                             <small>MARCH 6, 2019</small>
                           </div>
                         </div>
                       </div>
                     </div>
                 </div>
                 <div class="col-12  col-lg-7 pl-5">
                     <div class="row">
                         <div class="col-12 col-lg-4">
                             <div class="single-footer single-footer-left">
                               <h5>Our Services</h5>
                               <div class="row">
                                   <div class="col-12 col-lg-11">
                                     <hr>
                                   </div>
                                 </div>
                               <ul class="links-list pl-0 mb-0">
                                 <li> <a href="index.html"><i class="fa fa-angle-right"></i>Home</a> </li>
                     <li> <a href="#"><i class="fa fa-angle-right"></i>Shop</a> </li>
                     <li> <a href="orders.html"><i class="fa fa-angle-right"></i>Orders</a> </li>
                     <li> <a href="cart-page1.html"><i class="fa fa-angle-right"></i>Shopping Cart</a> </li>
                       <li> <a href="wishlist.html"><i class="fa fa-angle-right"></i>Wishlist</a> </li>

                               </ul>
                             </div>
                       </div>
                       <div class="col-12 col-lg-4">
                         <h5>Information</h5>
                         <div class="row">
                           <div class="col-12 col-lg-11">
                             <hr>
                           </div>
                         </div>
                         <ul class="links-list pl-0 mb-0">
                           <li> <a href="about-page1.html"><i class="fa fa-angle-right" ></i>About Us</a> </li>
                       <li> <a href="privacy.html"><i class="fa fa-angle-right" ></i>Privacy Policy</a> </li>
                       <li> <a href="refund.html"><i class="fa fa-angle-right"></i>Refund Policy</a> </li>
                       <li> <a href="term.html"><i class="fa fa-angle-right"></i>Term &amp; Services</a> </li>
                       <li> <a href="contact-page1.html"><i class="fa fa-angle-right"></i>Contact Us</a> </li>
                         </ul>
                       </div>
                       <div class="col-12 col-lg-4">
                           <h5>About Store</h5>
                           <div class="row">
                             <div class="col-12 col-lg-11">
                               <hr>
                             </div>
                           </div>
                           <ul class="contact-list  pl-0 mb-0">
                             <li> <i class="fas fa-map-marker"></i><span>Address City State, Zip Country</span> </li>
                             <li> <i class="fas fa-phone"></i><span>(888 - 963 - 600)</span> </li>
                             <li> <i class="fas fa-envelope"></i><span> <a href="#">info@ekommerce.com</a> </span> </li>
                           </ul>

                       </div>
                       <div class="col-12">
                           <div class="footer-image">
                             <h5>we Using safe payments:
                               <img class="img-fluid" src="images/miscellaneous/payments.png" style="margin-left:30px;">
                             </h5>
                           </div>
                         </div>
                     </div>
                 </div>

               </div>

             </div>
             <div class="container-fluid p-0">
                 <div class="social-content">
                     <div class="container">
                       <div class="row align-items-center">

                         <div class="col-12 col-md-8">
                           <div class="newsletter">

                               <div class="footer-info">
                                   <p>&copy;&nbsp;2019 Company, Inc. <a href="privacy.html">Privacy</a>&nbsp;&bull;&nbsp;<a href="term.html">Terms</a></p>
                               </div>
                           </div>
                         </div>
                         <div class="col-12 col-md-4">
                             <div class="socials">
                                 <ul class="list">
                                     <li><a href="#" class="fab fa-facebook-f"></a></li>
                                     <li><a href="#" class="fab fa-twitter"></a></li>
                                     <li><a href="#" class="fab fa-skype"></a></li>
                                     <li><a href="#" class="fab fa-linkedin-in"></a></li>
                                     <li><a href="#" class="fab fa-instagram"></a></li>
                                 </ul>
                             </div>
                         </div>
                       </div>
                     </div>
                 </div>
             </div>
     </footer> -->

     <!-- //footer style Six -->
     <!-- <footer id="footerSix"  class="footer-area footer-six footer-desktop d-none d-lg-block d-xl-block">
         <div class="container-fluid p-0">
             <div class="search-content">
                 <div class="container">
                   <div class="row justify-content-center">
                       <div class="col-12 align-self-center">
                           <div class="newsletter">
                               <h5>SUBSCRIBE FOR LATEST UPDATES</h5>
                               <div class="block">
                                 <form class="form-inline">
                                   <div class="search">
                                     <input  type="search" placeholder="Confirm your email.." aria-label="Search">
                                     <button class="btn btn-secondary" type="submit">
                                     SUBSCRIBE</button>
                                   </div>
                                 </form>
                               </div>
                           </div>
                       </div>
                   </div>
                 </div>
               </div>
         </div>
         <div class="container">
           <div class="row">
             <div class="col-12 col-lg-3">
                 <h5>About Store</h5>
                 <div class="row">
                   <div class="col-12 col-lg-8">
                     <hr>
                   </div>
                 </div>
                 <ul class="contact-list  pl-0 mb-0">
                   <li> <i class="fas fa-map-marker"></i><span>Address City State, Zip Country</span> </li>
                   <li> <i class="fas fa-phone"></i><span>(888 - 963 - 600)</span> </li>
                   <li> <i class="fas fa-envelope"></i><span> <a href="#">info@ekommerce.com</a> </span> </li>

                 </ul>
             </div>
             <div class="col-12 col-md-6 col-lg-3">
               <div class="footer-block">
                     <h5>Our Services</h5>
                     <div class="row">
                         <div class="col-12 col-lg-8">
                           <hr>
                         </div>
                       </div>
                     <ul class="links-list pl-0 mb-0">
                       <li> <a href="index.html"><i class="fa fa-angle-right"></i>Home</a> </li>
                     <li> <a href="#"><i class="fa fa-angle-right"></i>Shop</a> </li>
                     <li> <a href="orders.html"><i class="fa fa-angle-right"></i>Orders</a> </li>
                     <li> <a href="cart-page1.html"><i class="fa fa-angle-right"></i>Shopping Cart</a> </li>
                       <li> <a href="wishlist.html"><i class="fa fa-angle-right"></i>Wishlist</a> </li>
                     </ul>

               </div>
             </div>
             <div class="col-12 col-md-6 col-lg-3">
                 <h5>Information</h5>
                 <div class="row">
                     <div class="col-12 col-lg-8">
                       <hr>
                     </div>
                   </div>
                 <ul class="links-list pl-0 mb-0">
                   <li> <a href="about-page1.html"><i class="fa fa-angle-right" ></i>About Us</a> </li>
                       <li> <a href="privacy.html"><i class="fa fa-angle-right" ></i>Privacy Policy</a> </li>
                       <li> <a href="refund.html"><i class="fa fa-angle-right"></i>Refund Policy</a> </li>
                       <li> <a href="term.html"><i class="fa fa-angle-right"></i>Term &amp; Services</a> </li>
                       <li> <a href="contact-page1.html"><i class="fa fa-angle-right"></i>Contact Us</a> </li>
                 </ul>
             </div>

             <div class="col-12 col-lg-3">

               <div class="socials">
                   <h5>Follow Us</h5>
                   <div class="row">
                       <div class="col-12 col-lg-8">
                         <hr>
                       </div>
                     </div>
                   <ul class="list">
                       <li><a href="#" class="fab fa-facebook-f"></a></li>
                       <li><a href="#" class="fab fa-twitter"></a></li>
                       <li><a href="#" class="fab fa-skype"></a></li>
                       <li><a href="#" class="fab fa-linkedin-in"></a></li>
                       <li><a href="#" class="fab fa-instagram"></a></li>
                   </ul>
                   <div class="footer-image">
                       <img class="img-fluid" src="images/miscellaneous/payments.png">
                   </div>
               </div>
             </div>
           </div>
         </div>
         <div class="container-fluid p-0">
             <div class="copyright-content">
                 <div class="container">
                   <div class="row align-items-center">
                       <div class="col-12">
                         <div class="footer-info">
                             &copy;&nbsp;2019 Company, Inc. <a href="privacy.html">Privacy</a>&nbsp;&bull;&nbsp;<a href="term.html">Terms</a>
                         </div>

                       </div>
                   </div>
                 </div>
               </div>
         </div>
     </footer>     -->

     <!-- //footer style Seven -->
     <!-- <footer id="footerSeven"  class="footer-area footer-seven footer-desktop d-none d-lg-block d-xl-block">
           <div class="container">
             <div class="row">
                 <div class="col-12 col-lg-4">
                     <h5>About Store</h5>
                     <p style="margin-bottom:0">
                         Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                         sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                         Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                         nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                         reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.

                     </p>
                 </div>
                 <div class="col-12 col-md-6 col-lg-2">
                     <h5>Information</h5>
                     <ul class="links-list pl-0 mb-0">
                       <li> <a href="about-page1.html"><i class="fa fa-angle-right" ></i>About Us</a> </li>
                       <li> <a href="privacy.html"><i class="fa fa-angle-right" ></i>Privacy Policy</a> </li>
                       <li> <a href="refund.html"><i class="fa fa-angle-right"></i>Refund Policy</a> </li>
                       <li> <a href="term.html"><i class="fa fa-angle-right"></i>Term &amp; Services</a> </li>
                       <li> <a href="contact-page1.html"><i class="fa fa-angle-right"></i>Contact Us</a> </li>
                     </ul>
                 </div>

                 <div class="col-12 col-lg-3">
                     <h5>CONTACT US</h5>

                     <ul class="contact-list  pl-0 mb-0">
                       <li> <i class="fas fa-map-marker"></i><span>Address City State, Zip Country</span> </li>
                       <li> <i class="fas fa-phone"></i><span>(888 - 963 - 600)</span> </li>
                       <li> <i class="fas fa-envelope"></i><span> <a href="#">info@ekommerce.com</a> </span> </li>

                     </ul>
                 </div>
                 <div class="col-12 col-lg-3">
                     <div class="newsletter">
                         <h5>SUBSCRIBE</h5>
                         <div class="block">
                             <form class="form-inline">
                                 <div class="search">
                                   <input  type="search" placeholder="Confirm your email..">
                                   <button class="btn-secondary fas fa-location-arrow" type="submit">
                                   </button>
                                 </div>
                               </form>
                        </div>
                     </div>
                     <div class="socials">
                         <h5>payment method</h5>
                         <img class="img-fluid" src="images/miscellaneous/payments.png">
                     </div>
                   </div>
             </div>
           </div>
           <div class="container-fluid p-0">
               <div class="copyright-content">
                   <div class="container">
                     <div class="row align-items-center">
                         <div class="col-12 col-lg-4">
                             <h5>DOWNLOAD OUR APPS</h5>
                             <div class="apps-download">
                                 <a href="#"><img class="img-fluid" src="images/miscellaneous/google-play-btn.png"></a>
                                 <a href="#"><img class="img-fluid" src="images/miscellaneous/app-store-btn.png"></a>
                             </div>

                           </div>
                           <div class="col-12 col-lg-4 socials">
                               <h5>Follow us on</h5>
                               <div class="social">
                                   <ul class="list">
                                       <li><a href="#" class="fab fa-facebook-f"></a></li>
                                       <li><a href="#" class="fab fa-twitter"></a></li>
                                       <li><a href="#" class="fab fa-skype"></a></li>
                                       <li><a href="#" class="fab fa-linkedin-in"></a></li>
                                       <li><a href="#" class="fab fa-instagram"></a></li>
                                   </ul>
                               </div>

                             </div>
                         <div class="col-12 col-lg-4">
                           <div class="footer-info">
                               <strong>&copy;&nbsp;2019 Company, Inc. <a href="privacy.html">Privacy</a>&nbsp;&bull;&nbsp;<a href="term.html">Terms</a></strong>
                               <p>This is a demo store.Any orders placed through
                                 this store will not be honored or fulfilled
                               </p>
                           </div>

                         </div>
                     </div>
                   </div>
                 </div>
           </div>
     </footer> -->

     <!-- //footer style Eight -->
     <!-- <footer id="footerEight"  class="footer-area footer-eight footer-desktop d-none d-lg-block d-xl-block">
         <div class="container-fluid p-0">
             <div class="search-content">
                 <div class="container">
                   <div class="row justify-content-center">
                       <div class="col-12 align-self-center">
                           <div class="newsletter">
                               <h5>SUBSCRIBE NOW FOR THE LATEST NEWS AND GET 10% DISCOUNT ON SPECIAL ITEMS</h5>
                               <div class="block">
                                 <form class="form-inline">
                                   <div class="search">
                                     <input  type="search" placeholder="Confirm your email.." aria-label="Search">
                                     <button class="btn-secondary fas fa-location-arrow" type="submit">
                                     </button>
                                   </div>
                                 </form>
                               </div>
                           </div>
                       </div>
                   </div>
                 </div>
             </div>
           </div>
         <div class="container">
           <div class="row">
             <div class="col-12 col-lg-3 footer-padding">
               <div class="socials">
                   <h5>Follow Us</h5>
                   <div class="row">
                       <div class="col-12 col-lg-8">
                         <hr>
                       </div>
                     </div>
                   <ul class="list">
                       <li><a href="#" class="fab fa-facebook-f"></a></li>
                       <li><a href="#" class="fab fa-twitter"></a></li>
                       <li><a href="#" class="fab fa-skype"></a></li>
                       <li><a href="#" class="fab fa-linkedin-in"></a></li>
                       <li><a href="#" class="fab fa-instagram"></a></li>
                   </ul>

                   <div class="footer-image">
                       <h5>PAYMENTS METHOD</h5>
                       <div class="row">
                           <div class="col-12 col-lg-8">
                             <hr>
                           </div>
                         </div>
                       <img class="img-fluid" src="images/miscellaneous/payments.png">
                   </div>
               </div>
             </div>
             <div class="col-12 col-md-6 col-lg-3 footer-padding">
               <div class="footer-block">
                     <h5>Our Services</h5>
                     <div class="row">
                         <div class="col-12 col-lg-8">
                           <hr>
                         </div>
                       </div>
                     <ul class="links-list pl-0 mb-0">
                       <li> <a href="index.html"><i class="fa fa-angle-right"></i>Home</a> </li>
                     <li> <a href="#"><i class="fa fa-angle-right"></i>Shop</a> </li>
                     <li> <a href="orders.html"><i class="fa fa-angle-right"></i>Orders</a> </li>
                     <li> <a href="cart-page1.html"><i class="fa fa-angle-right"></i>Shopping Cart</a> </li>
                       <li> <a href="wishlist.html"><i class="fa fa-angle-right"></i>Wishlist</a> </li>
                     </ul>

               </div>
             </div>
             <div class="col-12 col-md-6 col-lg-3 footer-padding">
                 <h5>Information</h5>
                 <div class="row">
                     <div class="col-12 col-lg-8">
                       <hr>
                     </div>
                   </div>
                 <ul class="links-list pl-0 mb-0">
                   <li> <a href="about-page1.html"><i class="fa fa-angle-right" ></i>About Us</a> </li>
                       <li> <a href="privacy.html"><i class="fa fa-angle-right" ></i>Privacy Policy</a> </li>
                       <li> <a href="refund.html"><i class="fa fa-angle-right"></i>Refund Policy</a> </li>
                       <li> <a href="term.html"><i class="fa fa-angle-right"></i>Term &amp; Services</a> </li>
                       <li> <a href="contact-page1.html"><i class="fa fa-angle-right"></i>Contact Us</a> </li>
                 </ul>
             </div>
             <div class="col-12 col-lg-3">
               <div class="contacts">
                 <h5>CONTACT US</h5>
                 <div class="row">
                   <div class="col-12 col-lg-8">
                     <hr>
                   </div>
                 </div>
                 <ul class="contact-list  pl-0 mb-0">
                   <li> <i class="fas fa-location-arrow"></i><span>Address City State, Zip Country</span> </li>
                   <li> <i class="fas fa-phone"></i><span>(888 - 963 - 600)</span> </li>
                   <li> <i class="fas fa-envelope"></i><span> <a href="#">info@ekommerce.com</a> </span> </li>

                 </ul>
                 </div>
             </div>

           </div>
         </div>
         <div class="container-fluid p-0">
             <div class="copyright-content">
                 <div class="container">
                   <div class="row align-items-center">
                       <div class="col-12">
                         <div class="footer-info">
                             &copy;&nbsp;2019 Company, Inc. <a href="privacy.html">Privacy</a>&nbsp;&bull;&nbsp;<a href="term.html">Terms</a>
                         </div>

                       </div>
                   </div>
                 </div>
               </div>
         </div>
     </footer>   -->

     <!-- //footer style Nine   -->
     <footer id="footerNine"  class="footer-area footer-nine footer-desktop d-none d-lg-block d-xl-block">
           <div class="container">
             <div class="row">
               <div class="col-12 col-md-6 col-lg-6">
                   <div class="row">
                     <div class="col-6">
                         <div class="footer-image mb-4">
                           <h5>DOWNLOAD OUR APP</h5>
                           <a href="#"><img class="img-fluid" src="images/miscellaneous/google-play-btn.png"></a>
                           <a href="#"><img class="img-fluid" src="images/miscellaneous/app-store-btn.png"></a>
                         </div>
                         <div class="footer-image mb-3">
                             <h5>We Using safe payments</h5>
                               <img class="img-fluid" src="images/miscellaneous/payments.png">
                           </div>
                     </div>
                     <div class="col-6">
                         <h5>TAGS</h5>
                         <ul class="links-tag pl-0 mb-0">
                             <li> <a href="#">Fashion</a> </li>
                             <li> <a href="#">Households</a> </li>
                             <li> <a href="#">Jewelry & Watches</a> </li>
                             <li> <a href="#">Computers</a> </li>
                             <li> <a href="#">Boy's Clothing</a> </li>
                             <li> <a href="#">Mobiles</a> </li>
                             <li> <a href="#">Women's Clothing</a> </li>
                             <li> <a href="#">Cellphone & Accessories</a> </li>
                             <li> <a href="#">Specials</a> </li>
                             <li> <a href="#">Automobiles & Motorcycles</a> </li>
                             <li> <a href="#">Men's shoes</a> </li>
                             <li> <a href="#">Home Accessories</a> </li>
                             <li> <a href="#">Gifts</a> </li>
                             <li> <a href="#">Girl's Clothing</a> </li>
                             <li> <a href="#">Health & Beauty,Hair</a> </li>
                           </ul>
                     </div>
                   </div>
               </div>
               <div class="col-12 col-md-6 col-lg-6">
                 <div class="row">
                   <div class="col-12 col-md-6 col-lg-3">
                         <div class="single-footer single-footer-left">
                           <h5>Our Services</h5>
                           <ul class="links-list pl-0 mb-0">
                             <li> <a href="index.html"><i class="fa fa-angle-right"></i>Home</a> </li>
                             <li> <a href="#"><i class="fa fa-angle-right"></i>Shop</a> </li>
                             <li> <a href="orders.html"><i class="fa fa-angle-right"></i>Orders</a> </li>
                             <li> <a href="cart-page1.html"><i class="fa fa-angle-right"></i>Shopping Cart</a> </li>
                               <li> <a href="wishlist.html"><i class="fa fa-angle-right"></i>Wishlist</a> </li>
                           </ul>
                         </div>
                   </div>
                   <div class="col-12 col-md-6 col-lg-4">
                     <h5>INFORMATION</h5>
                     <ul class="links-list pl-0 mb-0">
                       <li> <a href="about-page1.html"><i class="fa fa-angle-right" ></i>About Us</a> </li>
                       <li> <a href="privacy.html"><i class="fa fa-angle-right" ></i>Privacy Policy</a> </li>
                       <li> <a href="refund.html"><i class="fa fa-angle-right"></i>Refund Policy</a> </li>
                       <li> <a href="term.html"><i class="fa fa-angle-right"></i>Term &amp; Services</a> </li>
                       <li> <a href="contact-page1.html"><i class="fa fa-angle-right"></i>Contact Us</a> </li>
                     </ul>
                   </div>
                   <div class="col-12 col-lg-5">
                       <h5>CONTACT US</h5>
                       <ul class="contact-list  pl-0 mb-0">
                         <li> <i class="fas fa-map-marker"></i><span>Address City State, Zip Country</span> </li>
                         <li> <i class="fas fa-phone"></i><span>(888 - 963 - 600)</span> </li>
                         <li> <i class="fas fa-envelope"></i><span> <a href="#">info@ekommerce.com</a> </span> </li>
                       </ul>

                   </div>
                 </div>

               </div>

             </div>

           </div>
           <div class="container-fluid p-0">
               <div class="social-content">
                   <div class="container">
                     <div class="social-div">
                       <div class="row align-items-center">

                         <div class="col-12 col-md-8">
                           <div class="newsletter">

                               <div class="footer-info">
                                 &copy;&nbsp;2019 Company, Inc. <a href="privacy.html">Privacy</a>&nbsp;&bull;&nbsp;<a href="term.html">Terms</a>
                               </div>
                           </div>
                         </div>
                         <div class="col-12 col-md-4">
                             <div class="socials">
                                 <ul class="list">
                                     <li><a href="#" class="fab fa-facebook-f"></a></li>
                                     <li><a href="#" class="fab fa-twitter"></a></li>
                                     <li><a href="#" class="fab fa-skype"></a></li>
                                     <li><a href="#" class="fab fa-linkedin-in"></a></li>
                                     <li><a href="#" class="fab fa-instagram"></a></li>
                                 </ul>
                             </div>
                         </div>
                       </div>
                   </div>
                   </div>
               </div>
           </div>
     </footer>

     <!-- //footer style Ten -->
     <!-- <footer id="footerTen"  class="footer-area footer-ten footer-desktop d-none d-lg-block d-xl-block">
         <div class="container-fluid p-0">
             <div class="brands-content ">
                 <div class="container">
                   <div class="row">
                     <div class="col-12">
                       <img class="img-fluid" src="images/brands/brands-content.jpg">
                       </div>
                   </div>
                 </div>
               </div>
         </div>
           <div class="container">
             <div class="row">
               <div class="col-12 col-md-6 col-lg-5">
                   <h5>ABOUT STORE</h5>
                 <div class="row">
                   <div class="col-12 col-lg-8">
                     <hr>
                   </div>
                 </div>
                   <p>
                       Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                       incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                         nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                       Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
                       eu fugiat nulla pariatur.
                   </p>
               </div>
               <div class="col-12 col-lg-7">
                 <div class="row">
                   <div class="col-12 col-md-6 col-lg-3">
                     <h5>Information</h5>
                     <div class="row">
                       <div class="col-12 col-lg-11">
                         <hr>
                       </div>
                     </div>
                     <ul class="links-list pl-0 mb-0">
                       <li> <a href="about-page1.html"><i class="fa fa-angle-right" ></i>About Us</a> </li>
                       <li> <a href="privacy.html"><i class="fa fa-angle-right" ></i>Privacy Policy</a> </li>
                       <li> <a href="refund.html"><i class="fa fa-angle-right"></i>Refund Policy</a> </li>
                       <li> <a href="term.html"><i class="fa fa-angle-right"></i>Term &amp; Services</a> </li>
                       <li> <a href="contact-page1.html"><i class="fa fa-angle-right"></i>Contact Us</a> </li>
                     </ul>
                   </div>
                   <div class="col-12 col-lg-5">
                       <h5>Contact Us</h5>
                       <div class="row">
                         <div class="col-12 col-lg-8">
                           <hr>
                         </div>
                       </div>
                       <ul class="contact-list  pl-0 mb-0">
                         <li> <i class="fas fa-map-marker"></i><span>Address City State, Zip Country</span> </li>
                         <li> <i class="fas fa-phone"></i><span>(888 - 963 - 600)</span> </li>
                         <li> <i class="fas fa-envelope"></i><span> <a href="#">info@ekommerce.com</a> </span> </li>
                       </ul>

                   </div>
                   <div class="col-12 col-md-6 col-lg-4">
                     <div class="single-footer single-footer-left">
                       <h5>FOLLOW US</h5>
                       <div class="row">
                         <div class="col-12 col-lg-11">
                           <hr>
                         </div>
                       </div>
                       <div class="socials">
                         <ul class="list">
                           <li><a href="#" class="fab fa-facebook-f"></a></li>
                           <li><a href="#" class="fab fa-twitter"></a></li>
                           <li><a href="#" class="fab fa-skype"></a></li>
                           <li><a href="#" class="fab fa-linkedin-in"></a></li>
                           <li><a href="#" class="fab fa-instagram"></a></li>
                         </ul>
                       </div>
                       <div class="footer-image mt-4">
                           <a href="#"><img class="img-fluid" src="images/miscellaneous/payments.png"></a>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
           </div>
           <div class="container-fluid p-0">
             <div class="copyright-content">
                 <div class="container">
                   <div class="row align-items-center">
                       <div class="col-12 col-md-8">
                         <div class="newsletter">
                           <div class="block">
                             <h5>SUBSCRIBE FOR LATEST UPDATES</h5>
                             <form class="form-inline">
                               <div class="search">
                                 <input  type="search" placeholder="Confirm your email.." aria-label="Search">
                                 <button class="btn-secondary fas fa-location-arrow" type="submit">
                                 </button>
                               </div>
                             </form>
                           </div>
                         </div>
                       </div>
                       <div class="col-12 col-md-4">
                         <div class="footer-info">
                             <p>&copy;&nbsp;2019 Company, Inc. <a href="privacy.html">Privacy</a>&nbsp;&bull;&nbsp;<a href="term.html">Terms</a></p>
                             <p>this is a demo store.Any orders placed through this store will not be honored or fulfilied</p>
                         </div>
                       </div>
                 </div>
                 </div>
             </div>
         </div>
     </footer> -->

     <div class="mobile-overlay"></div>
     <!-- Product Modal -->


     <a href="#" id="back-to-top" title="Back to top">&uarr;</a>

     <div class="modal" tabindex="-1" id="myModal" role="dialog">
         <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                   </button>
                 </div>
               <div class="modal-body">
                   <div class="container">
                     <div class="row">
                       <div class="col-lg-5">
                           <div id="quickView" class="carousel slide" data-ride="carousel">
                               <!-- The slideshow -->
                               <div class="carousel-inner">
                                 <div class="carousel-item active">

                                   <img class="img-fluid" src="images/gallery/preview/banner_1.png" alt="image">
                                 </div>
                                 <div class="carousel-item">

                                   <img class="img-fluid" src="images/gallery/preview/banner_2.png" alt="image">
                                 </div>
                                 <div class="carousel-item">

                                   <img class="img-fluid" src="images/gallery/preview/banner_3.png" alt="image">
                                 </div>
                                 <div class="carousel-item">

                                   <img class="img-fluid" src="images/gallery/preview/banner_4.png" alt="image">
                                 </div>
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
                       <div class="col-lg-7">
                           <h2>VAUGHN SUEDE SLIP-ON SNEAKER</h2>
                           <ul class="list">
                               <li>Men Shoes
                               </li>
                               <li> 0 Order(s)</li>
                               <li class="instock"><i class="fas fa-check"></i>In stock
                               </li>
                               <li> (Min Order Limit: 1)
                               </li>
                           </ul>
                           <h2>Total Price: <span>$360</span></h2>
                           <p>
                               Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                               sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                               Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                               nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                               reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                               Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                           </p>
                           <div class="product-buttons">
                               <div class="input-group item-quantity">

                                   <input type="text" id="quantity" name="quantity" class="form-control quantity input-number" value="10" min="1" max="100">

                                       <span class="input-group-btn">
                                           <button type="button" class="quantity-left-minus btn"  data-type="minus" data-field="">
                                             <span class="fas fa-angle-up"></span>
                                           </button>

                                           <button type="button" class="quantity-right-plus btn" data-type="plus" data-field="">
                                               <span class="fas fa-angle-down"></span>
                                           </button>
                                       </span>


                               </div>
                           <div class="buttons">
                             <a href="cart-page1.html" class="btn btn-block btn-secondary">Add to Cart</a>
                           </div>
                           <div class="icon-liked">
                             <a href="wishlist.html" class="icon active">
                               <i class="fas fa-heart"></i>
                               <span class="badge badge-secondary">4</span>
                             </a>
                           </div>
                           </div>
                           <a href="#">Men&rsquo;s Clothing</a>,&nbsp;<a href="#">Men Shoes</a>
                       </div>
                     </div>
                   </div>
                 </div>
           </div>
         </div>
     </div>

     <!-- Include js plugin -->
     <script src="js/jquery.min.js"></script>

     <script src="js/bootstrap.bundle.min.js"></script>
     <script src="js/owl.carousel.min.js"></script>

     <!-- Slider Revolution core JavaScript files -->
     <script type="text/javascript" src="revolution/js/jquery.themepunch.tools.min.js"></script>
     <script type="text/javascript" src="revolution/js/jquery.themepunch.revolution.min.js"></script>

     <!-- Slider Revolution extension scripts. ONLY NEEDED FOR LOCAL TESTING -->
     <script type="text/javascript" src="revolution/js/extensions/revolution.extension.actions.min.js"></script>
     <script type="text/javascript" src="revolution/js/extensions/revolution.extension.carousel.min.js"></script>
     <script type="text/javascript" src="revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
     <script type="text/javascript" src="revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
     <script type="text/javascript" src="revolution/js/extensions/revolution.extension.migration.min.js"></script>
     <script type="text/javascript" src="revolution/js/extensions/revolution.extension.navigation.min.js"></script>
     <script type="text/javascript" src="revolution/js/extensions/revolution.extension.parallax.min.js"></script>
     <script type="text/javascript" src="revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
     <script type="text/javascript" src="revolution/js/extensions/revolution.extension.video.min.js"></script>

     <!-- All custom scripts here -->
     <script src="js/scripts.js"></script>


   </body>
