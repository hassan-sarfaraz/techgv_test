<div class="exponea-banner exponea-leaderboard" href="javascript:void(0);" style="cursor: default;">
  <style>
    @keyframes pushDown {
      from {
        padding-top: 0;
      }

      to {
        padding-top: 140px;
      }
    }

    @keyframes exmoneaMoveDown {
      from {
        top: -300px;
      }

      to {
        top: 0;
      }
    }

    body {
      /*padding-top:50px;*/
      /* animation-name: pushDown;
	animation-fill-mode: forwards;
	animation-duration: 1s; */
    }

    .exponea-leaderboard * {
      box-sizing: border-box;
    }

    .exponea-leaderboard {
      display: block;
      position: relative;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 8;
      background: #47c257;
      font-size: 26px;
      line-height: normal;
      text-decoration: inherit;
      text-align: center;
      cursor: pointer;
      animation-name: exmoneaMoveDown;
      animation-fill-mode: forwards;
      animation-duration: 1s;
      vertical-align: middle;
      height: 140px;
    }

    .exponea-leaderboard .exponea-text b {
      display: block;

    }

    .exponea-leaderboard .exponea-text.exponea-text-kwt b,
    .exponea-leaderboard .exponea-text.exponea-text b {
      display: inline;
    }

    .exponea-leaderboard .exponea-text.exponea-text {
      margin-top: 0px;
    }

    .exponea-leaderboard:hover,
    .exponea-leaderboard:active {
      text-decoration: inherit;
    }

    .exponea-leaderboard span {
      display: block;
    }

    .exponea-leaderboard .exponea-header {
      font-size: 10px;
      font-weight: bold;
      color: #ffffff;
      margin: 0px;
    }

    .exponea-leaderboard .exponea-text {
      color: #ffffff;
      font-size: 20px;
      line-height: 22px;
      letter-spacing: 0.5px;
      padding: 2px 20px 2px 0px;
      display: inline-block;
      vertical-align: middle;
      max-width: 800px;
      text-align: left;
      margin-top: 18px;
      position: relative;
      transform: translateY(-50%);
      top: 50%;
    }

    .webengage-push-down {
      height: 0 !important;
      display: none !important;
    }

    .top_icons_section {
      height: 100%;
      padding: 10px 0;
      box-sizing: border-box;
      display: flex;
    }

    @media (max-width: 1024px) {
      .exponea-leaderboard .exponea-text {
        font-size: 14px;
        line-height: 18px;
      }

      .exponea-scroll .branding__menu {
        top: 140px !important;
      }

      .exponea-leaderboard .exponea-text b {
        display: inline;
      }

      body {
        animation: none;
      }

      .c-content {
        margin-top: 18px;
      }
    }


    @media (max-width: 767px) {
      .exponea-leaderboard .exponea-text {
        font-size: 14px;
        line-height: 16px;
        padding: 5px;
        padding: 0 15px 0 15px;
        transform: translateY(-50%);
        top: 50%;
        margin-top: 0;
        position: absolute;
        left: 0;
        right: 0;

      }

      .exponea-leaderboard .exponea-text.exponea-text {
        margin-top: 0;
      }

      .exponea-leaderboard .exponea-text:before {
        right: 14px;
        top: 15px;
        width: 20px;
        height: 20px;
        display: none
      }

      .exponea-scroll .branding__menu {
        top: 140px !important;
      }

      body.exponea-scroll {
        margin-top: 147px !important;
      }

      body.exponea-scroll.path--cart {
        margin-top: 170px !important;
      }

      .exponea-scroll .c-content {
        margin-top: 150px;
      }

      .plp-page-only .c-breadcrumb,
      .path--taxonomy .c-breadcrumb {
        margin-top: 170px;
      }

      body.path--store-finder {
        margin-top: 180px !important;
      }

      .branding__menu {
        transition: top ease-out 0.25s;
      }

      .c-content__slider {
        position: relative;
        z-index: 9;
      }


    }

    @media (max-width: 400px) {

      .exponea-leaderboard .exponea-text {
        padding: 5px;
        font-size: 10px;
        letter-spacing: 0;

      }

      .exponea-scroll .branding__menu {
        top: 140px !important;

      }
    }


    .exponea-hidden {
      display: none !important;
    }

    .exponea-branding,
    .exponea-branding:hover {
      font-size: 11px;
      color: #ffffff;
      opacity: 0.6;
      padding: 5px 10px 10px 10px;
      display: block;
      text-align: right;
      text-decoration: none;
    }

    .exponea-branding:hover {
      opacity: 0.9;
      text-decoration: none;
    }

    .exponea-banner__store-icons {
      /*width:25%;*/
      /*display: flex;*/
      /*   flex-wrap: wrap;*/
      /*   align-content: space-around;*/


      margin: 0px 20px;
    }

    .exponea-banner__store-icons div {
      width: 110px;
      display: block;
      text-align: center;

      margin: 15px 0px;
    }

    .exponea-banner__store-icons a {
      display: block;
      line-height: 0;
    }

    .exponea-banner__store-icons img {
      width: 100%;
    }

    @media (max-width: 450px) {
      .top_icons_section {
        height: 70%;
        padding: 5px;
        box-sizing: border-box;
        display: flex;
        margin-top: 47px;
      }

      .exponea-banner__store-icons img {
        width: 75%;
      }
    }

    /*.exponea-banner__qr-code{*/
    /*width:50%*/

    /*}*/

    .exponea-leaderboard .exponea-text {
      line-height: 30px;
    }

    @media (min-width:1008px) and (max-width: 1510px) {

      .exponea-leaderboard .exponea-text {
        font-size: 16px
      }

      /*.exponea-banner__store-icons{border: 1px solid red}	*/
    }


    /**{border : 1px solid red}*/
  </style>

  <div class="clearfix" style="margin: 0 auto;height: 100%;display: flex;align-items: center; justify-content: right; max-width: 900px;">
    <div style="height: 100%;">
      <span class="exponea-text exponea-text-uae">Download our new app now &amp; get an extra 15% off everything! <br>Use code: <b>PD15</b>

      </span>






    </div>

    <div class="top_icons_section">
      <div class="exponea-banner__store-icons ">
        <div>
          <a href="https://apps.apple.com/ae/app/ae-aerie-middle-east/id1547602361" target="_blank">
            <img src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/ae52a6e2-ca6a-4d8e-8944-532f952aae85/04f5bc50-4bf8-11ea-a90b-72540bde7b03/App-Store.png" style="max-height: 100%; max-width: 100%">
          </a>
        </div>
        <div>
          <a href="https://play.google.com/store/apps/details?id=com.aeo.mena" target="_blank">
            <img src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/ae52a6e2-ca6a-4d8e-8944-532f952aae85/04f5bc50-4bf8-11ea-a90b-72540bde7b03/playstore.png" style="max-height: 100%; max-width: 100%">
          </a>
        </div>
      </div>
      <div class="exponea-banner__qr-code">
        <img src="{{ url('/images/Protein District.png') }}" style="max-height: 100%; max-width: 100%">
      </div>
    </div>
  </div>

  <span class="exponea-close"><span class="exponea-close-cross"></span></span>
</div>

<div id="alertTop" class="alertTop text-center alert-dismissible fade show" role="alertTop">
  <div class="container">
    <div class="pro-description">
      <div class="widget block block-static-block" style="font-size: 14px; padding: 8px">
        <div class="flex-ticker" style="display:none;">
          <div class="flex-ticker__text"></div>
        </div>
      </div>
      {{--
      @if(session()->get('locale') == 'en')
        Get<strong> UPTO 40% OFF </strong>on your 1st order <a href="" class="pro-dropdown-toggle"><strong>SHOP NOW</strong></a>
      @else
          احصل على خصم حتى 40% على طلبك الأول! تسوق الآن
      @endif
      <button type="button" class="close alertTopclose" data-dismiss="alertTop" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    --}}
    </div>
  </div>
</div>

<!-- //header style One -->
<header id="headerOne" class="header-area header-one header-desktop d-none d-lg-block d-xl-block">
  <div class="header-mini bg-top-bar">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-12">
          <nav id="navbar_0_2" class="navbar navbar-expand-md navbar-dark navbar-0">
            <div class="navbar-lang">
              @if(count($languages) > 1)
              <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  <img src="{{asset('').session('language_image')}}" width="17px" />
                  {{session('language_name') == 'Arabic' ? 'عربي': session('language_name')}}
                </button>
                <ul class="dropdown-menu">
                  @foreach($languages as $language)
                  <li @if(session('locale')==$language->code) style="background:lightgrey;" @endif>
                    <button onclick="myFunction1({{$language->languages_id}})" class="btn" style="background:none;" href="#">
                      <img style="margin-left:10px; margin-right:10px;" src="{{asset('').$language->image_path}}" width="17px" />
                      <span>{{$language->name == 'Arabic' ? 'عربي': $language->name}}</span>
                    </button>
                  </li>
                  @endforeach
                </ul>
              </div>
              @include('web.common.scripts.changeLanguage')
              @endif
              @if(count($currencies) > 1)
              <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  @if(session('symbol_left') != null)
                  <!-- <span >{{session('symbol_left')}}</span> -->
                  @else
                  <!-- <span >{{session('symbol_right')}}</span> -->
                  @endif
                  {{session('currency_code')}}
                </button>
                <ul class="dropdown-menu">
                  @foreach($currencies as $currency)
                  <li @if(session('currency_title')==$currency->code) style="background:lightgrey;" @endif>
                    <button onclick="myFunction2({{$currency->id}})" class="btn" style="background:none;" href="#">
                      @if($currency->symbol_left != null)
                      <!-- <span style="margin-left:10px; margin-right:10px;">{{$currency->symbol_left}}</span> -->
                      <span>{{$currency->code}}</span>
                      @else
                      <!-- <span style="margin-left:10px; margin-right:10px;">{{$currency->symbol_right}}</span> -->
                      <span>{{$currency->code}}</span>
                      @endif
                    </button>
                  </li>
                  @endforeach
                </ul>
              </div>
              @include('web.common.scripts.changeCurrency')
              @endif
            </div>
            <div class="navbar-collapse">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <div class="nav-avatar nav-link">
                    <div class="avatar">
                      <?php
                      if (auth()->guard('customer')->check()) {
                        if (auth()->guard('customer')->user()->avatar == null) { ?>
                          <img class="img-fluid" src="{{asset('web/images/miscellaneous/avatar.jpg')}}">
                        <?php } else { ?>
                          <img class="img-fluid" src="{{auth()->guard('customer')->user()->avatar}}">
                      <?php
                        }
                      }
                      ?>
                    </div>
                    <span><?php if (auth()->guard('customer')->check()) { ?>@lang('website.Welcome')&nbsp;! {{auth()->guard('customer')->user()->first_name}} <?php } ?> </span>
                  </div>
                </li>
                <?php if (auth()->guard('customer')->check()) { ?>
                  <li class="nav-item"> <a href="{{url('profile')}}" class="nav-link">@lang('website.Profile')</a> </li>
                  <li class="nav-item"> <a href="{{url('wishlist')}}" class="nav-link">@lang('website.Wishlist')</a> </li>
                  <li class="nav-item"> <a href="{{url('compare')}}" class="nav-link">@lang('website.Compare')&nbsp;(<span id="compare">{{$count}}</span>)</a> </li>
                  <li class="nav-item"> <a href="{{url('orders')}}" class="nav-link">@lang('website.Orders')</a> </li>
                  <li class="nav-item"> <a href="{{url('shipping-address')}}" class="nav-link">@lang('website.Shipping Address')</a> </li>
                  <li class="nav-item"> <a href="{{url('logout')}}" class="nav-link padding-r0">@lang('website.Logout')</a> </li>
                <?php } else { ?>
                  @if(session()->get('locale') == 'en')
                  <li class="nav-item">
                    <div class="nav-link">@lang('website.Welcome')!</div>
                  </li>
                  <li class="nav-item"> <a href="{{ URL::to('/guest_checkout')}}" class="nav-link -before">@lang('website.Guest Checkout')</a> </li>
                  <li class="nav-item"> <a href="{{url('orders')}}" class="nav-link">@lang('website.Orders')</a> </li>
                  <li class="nav-item"> <a href="{{ URL::to('/login')}}" class="nav-link -before"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;@lang('website.Login/Register')</a> </li>
                  @else
                  <li class="nav-item">
                    <div class="nav-link">مرحبا!</div>
                  </li>
                  <li class="nav-item"> <a href="{{ URL::to('/guest_checkout')}}" class="nav-link -before">تابع كزائر</a> </li>
                  <li class="nav-item"> <a href="{{url('orders')}}" class="nav-link">الطلبات</a> </li>
                  <li class="nav-item"> <a href="{{ URL::to('/login')}}" class="nav-link -before"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;تسجيل الدخول / حساب جديد</a> </li>
                  @endif
                <?php } ?>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <div class="header-navbar logo-nav bg-menu-bar">
    <div class="container">
      <nav id="navbar_1_2" class="navbar navbar-expand-lg  bg-nav-bar">
        <a href="{{ URL::to('/')}}" class="logo">
          @if($result['commonContent']['setting'][77]->value=='name')
          <?= stripslashes($result['commonContent']['setting'][78]->value) ?>
          @endif

          @if($result['commonContent']['setting'][77]->value=='logo')
          <img style="width: 250px;" src="{{asset('').$result['commonContent']['setting'][15]->value}}" alt="<?= stripslashes($result['commonContent']['setting'][79]->value) ?>">
          @endif
        </a>
        <div class=" navbar-collapse">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
              <a class="nav-link " href="{{url('/')}}">
                @if(session()->get('locale') == 'en')
                @lang('website.Home')
                @else
                الصفحة الرئيسية
                @endif
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" href="{{url('/shop')}}">
                @if(session()->get('locale') == 'en')
                @lang('website.Shop')
                @else
                تسوق
                @endif
                <span class="badge badge-secondary">Hot</span>
              </a>
            </li>
            <!-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" >
                      @lang('website.News')
                    </a>
                    <div class="dropdown-menu">
                      @foreach($result['commonContent']['newsCategories'] as $categories)
                          <div class="dropdown-submenu">
                            <a class="dropdown-item" href="{{ URL::to('/news?category='.$categories->slug)}}">{{$categories->name}}</a>
                          </div>
                      @endforeach

                  </li> -->
            <!-- <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" >
                    @lang('website.infoPages')
                  </a>
                  <div class="dropdown-menu">
                    @foreach($result['commonContent']['pages'] as $page)
                      <a class="dropdown-item" href="{{ URL::to('/page?name='.$page->slug)}}">
                        {{$page->name}}
                      </a>
                    @endforeach
                  </div>
                </li> -->
            <!-- <li class="nav-item dropdown">
                  <a class="nav-link" href="{{url('contact')}}" >
                    @lang('website.Contact Us')
                  </a>
                </li> -->
            {{--<li class="nav-item ">--}}
            {{--<a href="{{url('shop?type=special')}}"class="btn btn-secondary">@lang('website.SPECIAL DEALS')</a>--}}
            {{--</li>--}}
          </ul>
        </div>

      </nav>
    </div>
  </div>
  <div class="header-maxi bg-header-bar">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-12 col-lg-2 lessPadding">
          <div class=" header-nav mid-header">
            <div class="box-header-nav" style="border-right: 1px solid #efefef;">
              <span data-action="toggle-nav-cat" class="nav-toggle-menu nav-toggle-cat">
                @if(session()->get('locale') == 'en')
                <span><i aria-hidden="true" class="fa fa-bars"></i> Shop By</span>
                @else
                <span><i aria-hidden="true" class="fa fa-bars"></i> تسوق</span>
                @endif
                <div class="block-nav-categori">
                  <div class="block-content">
                    <ul class="ui-categori">
                      <li class="parent">
                        <a href="" style="display: inline;color: black;font-weight: 600;font-size: 14px;line-height: 18px;">Shop By category</a><i class="fas fa-angle-right" style="float: right;margin-top: 5px;font-size: 12px;"></i>
                        <div class="submenu">
                          <ul class="categori-list clearfix">
                            @include('web.common.HeaderCategories')
                            @php productCategoriesMenu(); @endphp
                          </ul>
                        </div>
                      </li>

                      <li class="parent">
                        <a href="" style="display: inline;color: black;font-weight: 600;font-size: 14px;line-height: 18px;">Shop By brands</a><i class="fas fa-angle-right" style="float: right;margin-top: 5px;font-size: 12px;"></i>
                        <div class="submenu">
                          <ul class="categori-list clearfix">
                            <div class="row">
                              @foreach(headerBrands() as $headerBrand)

                              <div class="col-md-3" style="margin-bottom: 20px">
                                <a href="{{ URL::to('/shop?category=')}}">
                                  <li style="text-align: center">
                                    <img class="img-fluid" style="width:75px;height:75px" src="{{asset('').$headerBrand->path}}" alt="{{$headerBrand->name}}">
                                    <span style="display: block">
                                      {{$headerBrand->name}}
                                    </span>
                                  </li>
                                </a>
                              </div>
                              @endforeach

                            </div>
                          </ul>
                        </div>
                      </li>
                    </ul>
                    {{--<ul class="ui-categori">--}}
                    {{--<li class="parent">--}}
                    {{--<a href="" style="display: inline;color: black;font-weight: 600;font-size: 14px;line-height: 18px;">Shop By category</a><i class="fas fa-angle-right" style="float: right;margin-top: 5px;font-size: 12px;"></i>--}}
                    {{--<div class="submenu">--}}
                    {{--<ul class="categori-list clearfix">--}}
                    {{--@php productCategoriesMenu(); @endphp--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--</ul>--}}

                    {{--<ul class="ui-categori">--}}
                    {{--<li class="parent">--}}
                    {{--<a href="" style="display: inline;color: black;font-weight: 600;font-size: 14px;line-height: 18px;">Shop By Health Goal</a><i class="fas fa-angle-right" style="float: right;margin-top: 5px;font-size: 12px;"></i>--}}
                    {{--<div class="submenu">--}}
                    {{--<ul class="categori-list clearfix">--}}
                    {{--@php productCategoriesMenu(); @endphp--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                  </div>
                </div>
              </span>
            </div>
          </div>
        </div>
        {{--<div class="col-12 col-lg-1"></div>--}}
        <div class="col-12 col-lg-6 lessPaddingLeft">
          <form class="form-inline" action="{{ URL::to('/shop')}}" method="get">
            <div class="search">
              <!-- <div class="select-control">
                      <select class="form-control" name="category">
                       @php    productCategories(); @endphp
                      </select>
                  </div> -->
              <input type="search" name="search" placeholder="<?php if ((session()->get('locale') == 'en')) { ?> @lang('website.Search entire store here')... <?php } else { ?> بحث مخزن كامل هنا... <?php } ?>" value="{{ app('request')->input('search') }}" aria-label="Search">
              <button class="btn btn-secondary" type="submit">
                <i class="fa fa-search"></i></button>
            </div>
          </form>
        </div>
        <div class="col-12 col-lg-4">
          <ul class="top-right-list">
            <li class="phone-header">
              <a href="#">
                <i class="fas fa-phone"></i>
                <span class="block">
                  @if(session()->get('locale') == 'en')
                  <span class="title">@lang('website.Call Us Now')</span>
                  @else
                  <span class="title">اتصل بنا الآن</span>
                  @endif
                  <span class="items">{{$result['commonContent']['setting'][11]->value}}</span>
                </span>
              </a>
            </li>
            <li class="cart-header dropdown head-cart-content d-none d-md-block">
              <?php $qunatity = 0; ?>
              @foreach($result['commonContent']['cart'] as $cart_data)
              <?php $qunatity += $cart_data->customers_basket_quantity; ?>
              @endforeach

              <a href="#" id="dropdownMenuButton" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="badge badge-secondary">{{ $qunatity }}</span>
                <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
                <!--<img class="img-fluid" src="{{asset('').'public/images/shopping_cart.png'}}" alt="icon">-->

                <span class="block">
                  @if(session()->get('locale') == 'en')
                  <span class="title">@lang('website.My Cart')</span>
                  @else
                  <span class="title">عربتي</span>
                  @endif
                  @if(count($result['commonContent']['cart'])>0)
                  <span class="items">{{ count($result['commonContent']['cart']) }}&nbsp;@if(session()->get('locale') == 'en')@lang('website.items')@else العناصر@endif</span>
                  @else
                  <span class="items">(0)&nbsp;@lang('website.item')</span>
                  @endif
                </span>
              </a>

              @if(count($result['commonContent']['cart'])>0)
              @php
              $default_currency = DB::table('currencies')->where('is_default',1)->first();
              if($default_currency->id == Session::get('currency_id')){

              $currency_value = 1;
              }else{
              $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();

              $currency_value = $session_currency->value;
              }
              @endphp
              <div class="shopping-cart shopping-cart-empty dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCartButton_9">
                <ul class="shopping-cart-items">
                  <?php
                  $total_amount = 0;
                  $qunatity = 0;
                  ?>
                  @foreach($result['commonContent']['cart'] as $cart_data)

                  <?php
                  $single_product = $cart_data->final_price * $cart_data->customers_basket_quantity;
                  $total_amount += $single_product;

                  $qunatity     += $cart_data->customers_basket_quantity;

                  $tax = ($single_product * Config::get('global.p_constant'));
                  $single_product = $single_product + $tax;
                  $single_product = round($single_product, 2);

                  $tax = ($total_amount * Config::get('global.p_constant'));
                  $total_amount = $total_amount + $tax;
                  $total_amount = round($total_amount, 2);

                  ?>
                  <li>
                    <div class="item-thumb">
                      <a href="{{ URL::to('/deleteCart?id='.$cart_data->customers_basket_id)}}" class="icon"><img class="img-fluid" src="{{asset('').'web/images/close.png'}}" alt="icon"></a>
                      <div class="image">
                        <img class="img-fluid" src="{{asset('').$cart_data->image}}" alt="{{$cart_data->products_name}}" />
                      </div>
                    </div>
                    <div class="item-detail">
                      <h2 class="item-name">{{$cart_data->products_name}}</h2>
                      <span class="item-quantity">
                        @if(session()->get('locale') == 'en')@lang('website.Qty')@elseالكمية@endif&nbsp;:&nbsp;{{$cart_data->customers_basket_quantity}}</span>
                      <span class="item-price">{{Session::get('symbol_left')}} {{$single_product*$currency_value}}{{Session::get('symbol_right')}}</span>
                    </div>
                  </li>
                  @endforeach
                  <li>
                    <div class="tt-summary">
                      <p>
                        @if(session()->get('locale') == 'en')
                        @lang('website.items')
                        @else
                        العناصر
                        @endif

                        <span>{{ $qunatity }}</span>
                      </p>
                      <p>
                        @if(session()->get('locale') == 'en')
                        @lang('website.SubTotal')
                        @else
                        المجموع الفرعي
                        @endif


                        <span>{{Session::get('symbol_left')}} {{ $total_amount*$currency_value }}{{Session::get('symbol_right')}}</span>
                      </p>
                    </div>
                  </li>
                  <li>
                    <div class="buttons">
                      <a class="btn btn-dark" href="{{ URL::to('/viewcart')}}">
                        @if(session()->get('locale') == 'en')
                        @lang('website.View Cart')
                        @else
                        عرض العربة
                        @endif

                      </a>
                      <a class="btn btn-secondary" href="{{ URL::to('/checkout')}}">
                        @if(session()->get('locale') == 'en')
                        @lang('website.Checkout')
                        @else
                        الدفع
                        @endif
                      </a>
                    </div>
                  </li>
                </ul>

              </div>

              @else

              <div class="shopping-cart shopping-cart-empty dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <ul class="shopping-cart-items">
                  <li>
                    @if(session()->get('locale') == 'en')
                    @lang('website.You have no items in your shopping cart')
                    @else
                    ليس لديك أي عناصر في سلة التسوق الخاصة بك.
                    @endif
                  </li>
                </ul>
              </div>
              @endif

            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</header>