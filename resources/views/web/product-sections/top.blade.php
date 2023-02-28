@if($result['featured']['success']==1)
    <section class="products-content">
        <div class="container bg-white p-4 mb-4">
            <div class="products-area">
                <!-- heading -->
                <div class="heading">
                    <h2 class="font-weight-bolder">
                        @if(session()->get('locale') == 'en')
                            @lang('website.Top Selling of the Week')
                        @else
                            أعلى بيع لهذا الأسبوع
                        @endif
                        <small class="pull-right">
                            <a class="btn btn-sm btn-success" href="{{url('/shop?type=topseller')}}">@lang('website.View All')</a>
                        </small>
                    </h2>
                    <hr>
                </div>
                <div class="row">
                    {{-- @if($result['featured']['success']==1)
                        @foreach($result['featured']['product_data'] as $key=>$products)
                            @if($key==0)
                                <div class="col-12 col-md-12 col-lg-6">
                                    <div class="product-2x">
                                        <span class="featured-tag"><i class="far fa-flag" aria-hidden="true"></i>&nbsp;Featured</span>
                                        <div class="icon-liked">
                                            <a onclick="myLike({{$products->products_id}})" class="icon active">
                                                <i class="fas fa-heart"></i>
                                                <span class="badge badge-secondary">{{$products->products_liked}}</span>
                                            </a>
                                        </div>
                                        <article>
                                            <div class="thumb">
                                                <img class="img-fluid" src="{{asset('').$products->image_path}}" alt="{{$products->products_name}}">
                                            </div>
                                            <div class="module">
                                            <span class="tag">
                                              @php
                                                  $default_currency = DB::table('currencies')->where('is_default',1)->first();
                                                  if($default_currency->id == Session::get('currency_id')){
                                                      $currency_value = 1;
                                                  }else{
                                                      $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();
                                                      $currency_value = $session_currency->value;
                                                  }
                                              @endphp
                                                @foreach($products->categories as $key=>$category)
                                                    {{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif
                                                @endforeach
                                            </span>
                                                <h2 class="title"><a href="{{ URL::to('/product-detail/'.$products->products_slug)}}">{{$products->products_name}}</a></h2>
                                                <div class="price">
                                                    @if(!empty($products->discount_price))
                                                        {{Session::get('symbol_left')}}
                                                        <span class="price_value">{{pd_format_number(pd_adjust_price_with_tax($products->discount_price+0))}}</span>
                                                        {{Session::get('symbol_right')}}
                                                        <span style="text-decoration: line-through; color:lightgrey;">
                                                            {{Session::get('symbol_left')}}
                                                            {{pd_adjust_price_with_tax($products->discount_price+0)}}
                                                            @php
                                                                $orignal_price = $products->products_price;
                                                            @endphp
                                                              <span class="price_value">{{pd_format_number(pd_adjust_price_with_tax($orignal_price+0*$currency_value))}}</span>
                                                            {{Session::get('symbol_right')}}
                                                        </span>
                                                    @else
                                                        {{Session::get('symbol_left')}}
                                                        @php
                                                            $orignal_price = $products->products_price;
                                                        @endphp
                                                        <span class="price_value">{{pd_format_number(pd_adjust_price_with_tax($orignal_price+0*$currency_value))}}</span>
                                                        {{Session::get('symbol_right')}}
                                                    @endif
                                                </div>
                                                <ul class="list">
                                                    @if(!empty($products->attributes) and count($products->attributes)>0)
                                                        @foreach( $products->attributes as $key=>$attributes_data )
                                                            @if($key==1)

                                                            @endif
                                                            <li>{{ $attributes_data['option']['name'] }}
                                                                <br>
                                                                @foreach( $attributes_data['values'] as $key=>$values_data )
                                                                    <small>{{ $values_data['value'] }}
                                                                        @if($key+1!=count($attributes_data['values']))
                                                                            |
                                                                        @endif
                                                                    </small>
                                                                @endforeach
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
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
                                        </article>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif --}}
                    @if($result['weeklySoldProducts']['success']==1)
                        @foreach($result['weeklySoldProducts']['product_data'] as $key=>$products)
                            @if($key<=5)
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                                    <!-- Product -->
                                    @include('web.common.product')
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
