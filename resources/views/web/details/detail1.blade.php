<section class="product-page product-page-one ">
    <div class="container bg-white">
        <div class="product-main">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="row justify-content-start">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                                @if(!empty($result['category_name']) and !empty($result['sub_category_name']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ URL::to('/shop?category='.$result['category_slug'])}}">{{$result['category_name']}}</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ URL::to('/shop?category='.$result['sub_category_slug'])}}">{{$result['sub_category_name']}}</a>
                                    </li>
                                @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ URL::to('/shop?category='.$result['category_slug'])}}">{{$result['category_name']}}</a>
                                    </li>
                                @endif
                                <li class="breadcrumb-item active">{{$result['detail']['product_data'][0]->products_name}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-12 col-sm-12">
                    <div class="row">
                        <div class="col-12 col-lg-5">
                            <div class="slider-wrapper" style="border-right: 1px solid #f3f3f3;">
                                <div class="slider-for">
                                    <a class="slider-for__item ex1 fancybox-button"
                                       href="{{asset('').$result['detail']['product_data'][0]->image_path }}"
                                       data-fancybox-group="fancybox-button" title="Lorem ipsum dolor sit amet">
                                        <img src="{{asset('').$result['detail']['product_data'][0]->image_path }}" alt="Zoom Image"/>
                                    </a>
                                    @foreach( $result['detail']['product_data'][0]->images as $key=>$images )
                                        @if($images->image_type == 'ACTUAL')
                                            <a class="slider-for__item ex1 fancybox-button"
                                               href="{{asset('').$images->image_path }}"
                                               data-fancybox-group="fancybox-button" title="Lorem ipsum dolor sit amet">
                                                <img src="{{asset('').$images->image_path }}" alt="Zoom Image"/>
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
                        <div class="col-12 col-lg-7">
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
                                    <li> {{$result['detail']['product_data'][0]->products_ordered}}
                                        &nbsp;@lang('website.Order(s)')</li>
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
                                    <?php $field_price = $result['detail']['product_data'][0]->flash_price; ?>
                                @elseif(!empty($result['detail']['product_data'][0]->discount_price))
                                    <?php $field_price = $result['detail']['product_data'][0]->discount_price; ?>
                                @else
                                    <?php $field_price = $result['detail']['product_data'][0]->products_price;?>
                                @endif
                                <?php
                                // echo "========================".$field_price;
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
                                                    <select name="{{ $attributes_data['option']['id'] }}" onChange="getQuantity()" class="currentstock form-control attributeid_<?=$index++?>" attributeid="{{ $attributes_data['option']['id'] }}">
                                                        @if(!empty($result['cart']))
                                                            @php
                                                                $value_ids = array();
                                                                 if(isset($result['cart'][0]->attributes)){
                                                                   foreach($result['cart'][0]->attributes as $values){
                                                                       $value_ids[] = $values->options_values_id;
                                                                   }
                                                                 }
                                                            @endphp
                                                            @foreach($attributes_data['values'] as $values_data)
                                                                @if(!empty($result['cart']))
                                                                    <option
                                                                        @if(in_array($values_data['id'],$value_ids)) selected
                                                                        @endif attributes_value="{{ $values_data['products_attributes_id'] }}"
                                                                        value="{{ $values_data['id'] }}"
                                                                        prefix='{{ $values_data['price_prefix'] ?? '-' }}'
                                                                        value_price="{{ pd_adjust_price_with_tax($values_data['price']+0) }}">{{ $values_data['value'] }}</option>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            @foreach($attributes_data['values'] as $values_data)
                                                                <option
                                                                    attributes_value="{{ $values_data['products_attributes_id'] }}"
                                                                    value="{{ $values_data['id'] }}"
                                                                    prefix='{{ $values_data['price_prefix'] ?? '-' }}'
                                                                    value_price="{{ pd_adjust_price_with_tax($values_data['price']+0) }}">{{ $values_data['value'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="col-12 col-md-4 box Qty"
                                         @if(!empty($result['detail']['product_data'][0]->flash_start_date) and $result['detail']['product_data'][0]->server_time < $result['detail']['product_data'][0]->flash_start_date ) style="display: none" @endif>
                                        <label>Quantity</label>
                                        <div class="Qty">
                                            <div class="input-group">
                                            <span class="input-group-btn first qtyminus">
                                              <button class="btn btn-defualt" type="button">-</button>
                                            </span>
                                                <input style="width:-20px;" type="text" readonly name="quantity"
                                                       value=" @if(!empty($result['cart'])) {{$result['cart'][0]->customers_basket_quantity}} @else @if($result['detail']['product_data'][0]->products_min_order>0 and $result['detail']['product_data'][0]->defaultStock > $result['detail']['product_data'][0]->products_min_order) {{$result['detail']['product_data'][0]->products_min_order}} @else 1 @endif @endif"
                                                       min="@if($result['detail']['product_data'][0]->products_min_order>0 and $result['detail']['product_data'][0]->defaultStock > $result['detail']['product_data'][0]->products_min_order) {{$result['detail']['product_data'][0]->products_min_order}} @else 1 @endif"
                                                       max="@if(!empty($result['detail']['product_data'][0]->products_max_stock) and $result['detail']['product_data'][0]->products_max_stock>0 and $result['detail']['product_data'][0]->defaultStock > $result['detail']['product_data'][0]->products_max_stock){{ $result['detail']['product_data'][0]->products_max_stock}}@else{{ $result['detail']['product_data'][0]->defaultStock}}@endif"
                                                       class="form-control qty">
                                                <span class="input-group-btn last qtyplus">
                                              <button class="btn btn-defualt" type="button">+</button>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($result['promotions']))
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
                                                            if ($attributes_data['option']['name'] == 'Size') { }
                                                            ?>
                                                            <div class="col-12 col-md-4 box">
                                                                <label>{{ $attributes_data['option']['name'] }}</label>
                                                                <div class="select-control ">
                                                                    <select class="currentstock form-control"
                                                                            @if($attributes_data['option']['name'] == 'Size') disabled
                                                                            style="background: grey"
                                                                            @else name="promotion_{{ $attributes_data['option']['id'] }}" @endif
                                                                    >

                                                                        @foreach($attributes_data['values'] as $values_data)
                                                                            <option
                                                                                @if($values_data['products_attributes_id'] == $at_p_to) selected
                                                                                @endif
                                                                                attributes_value="{{ $values_data['products_attributes_id'] }}"
                                                                                value="{{ $values_data['id'] }}"
                                                                                prefix='{{ $values_data['price_prefix'] ?? '-' }}'
                                                                                value_price="0">{{ $values_data['value'] }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    <div class="col-12 col-md-4 box Qty_promo"
                                                         @if(!empty($result['detail']['product_data'][0]->flash_start_date) and $result['detail']['product_data'][0]->server_time < $result['detail']['product_data'][0]->flash_start_date ) style="display: none" @endif>
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
                                @endif
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
                                            /*$orignal_price = $result['detail']['product_data'][0]->products_price;
                                            $tax = ($orignal_price * Config::get('global.p_constant'));
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
                                            /*$tax = ($orignal_price * Config::get('global.p_constant'));
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
                                            {{Session::get('symbol_left')}}{{pd_format_number(pd_adjust_price_with_tax($flash_price+0))}}{{Session::get('symbol_right')}}
                                        @elseif(!empty($result['detail']['product_data'][0]->discount_price))
                                            {{Session::get('symbol_left')}}{{pd_format_number(pd_adjust_price_with_tax($discount_price+0))}}{{Session::get('symbol_right')}}
                                        @else
                                            {{Session::get('symbol_left')}}{{pd_format_number(pd_adjust_price_with_tax($orignal_price+0))}}{{Session::get('symbol_right')}}
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
                                                <button class="btn btn-secondary btn-block  add-to-Cart stock-cart" type="button" products_id="{{$result['detail']['product_data'][0]->products_id}}">@lang('website.Add to Cart')</button>
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
                                        <div role="tabpanel" class="tab-pane fade" id="review"
                                             aria-labelledby="review-tab">
                                            <div class="tabs-pera">
                                                <p>
                                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                    nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                                                    reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                                    pariatur.
                                                    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui
                                                    officia deserunt mollit anim id est laborum. Duis aute irure dolor
                                                    in
                                                    reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                                    pariatur.Duis aute irure dolor in
                                                    reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                                    pariatur.
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
                </div>
            </div>
        </div>
        <hr >
        <div class="products-area mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="heading">
                        <h2 class="font-weight-bolder">
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
