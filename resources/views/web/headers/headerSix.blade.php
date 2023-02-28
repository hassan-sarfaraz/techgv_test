<!-- //header style Six -->
<header id="headerSix" class="header-area header-six header-desktop d-none d-lg-block d-xl-block">
    <div class="header-mini bg-top-bar">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-12">

            <nav id="navbar_0_6" class="navbar navbar-expand-md navbar-dark navbar-0">
              <div class="navbar-lang">
                <!--  CHANGE LANGUAGE CODE SECTION -->
                @if(count($languages) > 1)
                <div class="dropdown">

                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                     <img src="{{asset('').session('language_image')}}" width="17px" />
                     {{	session('language_name')}}
                    </button>
                    <ul class="dropdown-menu">
                      @foreach($languages as $language)
                      <li  @if(session('locale')==$language->code) style="background:lightgrey;" @endif>
                        <button  onclick="myFunction1({{$language->languages_id}})" class="btn" style="background:none;" href="#">
                          <img style="margin-left:10px; margin-right:10px;"src="{{asset('').$language->image_path}}" width="17px" />
                          <span>{{$language->name}}</span>
                        </button>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                  @include('web.common.scripts.changeLanguage')
                  @endif
                  <!-- END  CHANGE LANGUAGE CODE SECTION -->
                  <!--  CHANGE Currency CODE SECTION -->
                  @if(count($currencies) > 1)
                  <div class="dropdown">

                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">

                        @if(session('symbol_left') != null)
                        <span >{{session('symbol_left')}}</span>
                        @else
                        <span >{{session('symbol_right')}}</span>
                        @endif
                       {{session('currency_code')}}


                      </button>
                      <ul class="dropdown-menu">
                        @foreach($currencies as $currency)
                        <li  @if(session('currency_title')==$currency->code) style="background:lightgrey;" @endif>
                          <button  onclick="myFunction2({{$currency->id}})" class="btn" style="background:none;" href="#">
                            @if($currency->symbol_left != null)
                            <span style="margin-left:10px; margin-right:10px;">{{$currency->symbol_left}}</span>
                            <span>{{$currency->code}}</span>
                            @else
                            <span style="margin-left:10px; margin-right:10px;">{{$currency->symbol_right}}</span>
                            <span>{{$currency->code}}</span>
                            @endif
                          </button>
                        </li>
                        @endforeach
                      </ul>
                    </div>
                    @include('web.common.scripts.changeCurrency')
                    @endif
                    <!-- END  Currency LANGUAGE CODE SECTION -->
              </div>

              <div class="navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <div class="nav-avatar nav-link">
                          <div class="avatar">
                          <?php
                          if(auth()->guard('customer')->check()){
                           if(auth()->guard('customer')->user()->avatar == null){ ?>
                            <img class="img-fluid" src="{{asset('web/images/miscellaneous/avatar.jpg')}}">
                          <?php }else{ ?>
                            <img class="img-fluid" src="{{auth()->guard('customer')->user()->avatar}}">
                          <?php
                                }
                             }
                          ?>
                          </div>
                          <span><?php if(auth()->guard('customer')->check()){ ?>@lang('website.Welcome')&nbsp;! {{auth()->guard('customer')->user()->first_name}} <?php }?> </span>
                        </div>
                      </li>
                      <?php if(auth()->guard('customer')->check()){ ?>
                      <li class="nav-item"> <a href="{{url('profile')}}" class="nav-link">@lang('website.Profile')</a> </li>
                      <li class="nav-item"> <a href="{{url('wishlist')}}" class="nav-link">@lang('website.Wishlist')</a> </li>
                      <li class="nav-item"> <a href="{{url('compare')}}" class="nav-link">@lang('website.Compare')&nbsp;(<span id="compare">{{$count}}</span>)</a> </li>
                      <li class="nav-item"> <a href="{{url('orders')}}" class="nav-link">@lang('website.Orders')</a> </li>
                      <li class="nav-item"> <a href="{{url('shipping-address')}}" class="nav-link">@lang('website.Shipping Address')</a> </li>
                      <li class="nav-item"> <a href="{{url('logout')}}" class="nav-link padding-r0">@lang('website.Logout')</a> </li>
                      <?php }else{ ?>
                        <li class="nav-item"><div class="nav-link">@lang('website.Welcome')!</div></li>
                        <li class="nav-item"> <a href="{{ URL::to('/login')}}" class="nav-link -before"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;@lang('website.Login/Register')</a> </li>                      <?php } ?>
                </ul>
              </div>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="header-maxi bg-header-bar">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-12 col-sm-12 col-lg-3">
            <a href="{{ URL::to('/')}}" class="logo">
        @if($result['commonContent']['setting'][77]->value=='name')
        <?=stripslashes($result['commonContent']['setting'][78]->value)?>
        @endif

        @if($result['commonContent']['setting'][77]->value=='logo')
        <img src="{{asset('').$result['commonContent']['setting'][15]->value}}" alt="<?=stripslashes($result['commonContent']['setting'][79]->value)?>">
        @endif
        </a>
          </div>
          <div class="col-6 col-sm-6 col-lg-2">
                <div class="phone-header">
                  <a href="#">
                      <i class="fas fa-phone"></i>
                      <span class="block">
                        <span class="title">@lang('website.Call Us Now')</span>
                        <span class="items">{{$result['commonContent']['setting'][11]->value}}</span>
                      </span>
                  </a>
                </div>
            </div>
          <div class="col-12 col-sm-7 col-md-8 col-lg-5">
            <form class="form-inline" action="{{ URL::to('/shop')}}" method="get">
              <div class="search">
                  <div class="select-control">
                      <select class="form-control" name="category">
                       @include('web.common.HeaderCategories')
                       @php    productCategories(); @endphp
                      </select>
                    </div>
                    <input type="search"  name="search" placeholder="@lang('website.Search entire store here')..." value="{{ app('request')->input('search') }}" aria-label="Search">
                <button class="btn btn-secondary" type="submit">
                <i class="fa fa-search"></i></button>
              </div>
            </form>
          </div>
          <div class="col-6 col-sm-6 col-md-4 col-lg-2">
            <ul class="top-right-list">
              <li class="cart-header dropdown head-cart-content">
                @include('web.headers.cartButtons.cartButton')
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="header-navbar bg-menu-bar">
      <div class="container">
        <nav id="navbar_1_6" class="navbar navbar-expand-lg  bg-nav-bar">

          <div class="navbar-collapse">
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link " href="{{url('/')}}" >
                  @lang('website.Home')
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="{{url('/shop')}}"  >
                  @lang('website.Shop')
                  <span class="badge badge-secondary">Hot</span>
                </a>

              </li>
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" >
                    @lang('website.News')
                  </a>
                  <div class="dropdown-menu">
                    @foreach($result['commonContent']['newsCategories'] as $categories)
                        <div class="dropdown-submenu">
                          <a class="dropdown-item" href="{{ URL::to('/news?category='.$categories->slug)}}">{{$categories->name}}</a>
                        </div>
                    @endforeach

                </li>
              <li class="nav-item dropdown">
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
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="{{url('contact')}}" >
                  @lang('website.Contact Us')
                </a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </header>
