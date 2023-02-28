<header id="headerMobile" class="header-area header-mobile d-lg-none d-xl-none">
    <div class="header-mini bg-top-bar">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-12">

            <nav id="navbar_0" class="navbar navbar-expand-md navbar-dark navbar-0">
              <div class="navbar-lang">
                @if(count($languages) > 1)
                <div class="dropdown">
                    <button class="btn" style="background:none;" href="#">
                      <img style="margin-right:-30px;"src="{{asset('').session('language_image')}}" width="17px" />
                    </button>
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                     {{session('language_name')  == 'Arabic' ? 'عربي': session('language_name')}}
                    </button>
                    <ul class="dropdown-menu">
                      @foreach($languages as $language)
                      <li  @if(session('locale')==$language->code) style="background:lightgrey;" @endif>
                        <button  onclick="myFunction1({{$language->languages_id}})" class="btn" style="background:none;" href="#">
                          <img style="margin-left:10px; margin-right:10px;"src="{{asset('').$language->image_path}}" width="17px" />
                          <span>{{$language->name  == 'Arabic' ? 'عربي': $language->name}}</span>
                        </button>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                  @include('web.common.scripts.changeLanguage')
                  @endif

                  <!--  CHANGE Currency CODE SECTION -->
                  @if(count($currencies) > 1)
                  <div class="dropdown">
                      <button class="btn" style="background:none;" href="#">
                       @if(session('symbol_left') != null)
                       <!-- <span style="margin-right:-30px;">{{session('symbol_left')}}</span> -->
                       @else
                       <!-- <span style="margin-right:-30px;">{{session('symbol_right')}}</span> -->
                       @endif
                      </button>
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                       {{session('currency_code')}}
                      </button>
                      <ul class="dropdown-menu">
                        @foreach($currencies as $currency)
                        <li  @if(session('currency_title')==$currency->code) style="background:lightgrey;" @endif>
                          <button  onclick="myFunction2({{$currency->id}})" class="btn" style="background:none;" href="#">
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
                    <!-- END  Currency LANGUAGE CODE SECTION -->
              </div>
              <div class="contact d-none d-md-block">
                <i class="fas fa-phone"></i> Call us Now 888-9636-6000
              </div>

            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="header-maxi bg-header-bar ">
      <div class="container">

        <div class="row align-items-center">
          <div class="col-2 pr-0">
              <div class="navigation-mobile-container">
                  <a href="javascript:void(0)" class="navigation-mobile-toggler">
                      <span class="fas fa-bars"></span>
                  </a>
                  <nav id="navigation-mobile">
                      <div class="logout-main">
                          <div class="welcome">
                            <span><?php if(auth()->guard('customer')->check()){ ?>@lang('website.Welcome')&nbsp;! {{auth()->guard('customer')->user()->first_name}} <?php }?> </span>
                          </div>
                          @if(auth()->guard('customer')->check())
                          <div class="logout">
                              <a href="{{url('logout')}}" class="">@lang('website.Logout')</a>
                          </div>
                          @endif
                      </div>

                      <a href="{{url('/')}}" class="main-manu btn btn-primary">
                        @lang('website.Home')
                      </a>

                      <!-- <a class="main-manu btn btn-primary" data-toggle="collapse" href="#shoppages" role="button" aria-expanded="false" aria-controls="shoppages">
                          @lang('website.infoPages')<span><i class="fas fa-chevron-down"></i></span>
                          <span><i class="fas fa-chevron-up"></i></span>
                      </a>
                      <div class="sub-manu collapse multi-collapse" id="shoppages">
                          <ul class="unorder-list">
                            <li class="">
                              @foreach($result['commonContent']['pages'] as $page)
                              <a href="{{ URL::to('/page?name='.$page->slug)}}" class="btn btn-primary">
                                {{$page->name}}
                              </a>
                              @endforeach
                            </li>
                          </ul>
                      </div> -->

                      <!-- <a class=" main-manu btn btn-primary" data-toggle="collapse" href="#productpages" role="button" aria-expanded="false" aria-controls="productpages">
                        @lang('website.News') <span><i class="fas fa-chevron-down"></i></span>
                        <span><i class="fas fa-chevron-up"></i></span>
                      </a>
                      <div class="sub-manu collapse multi-collapse" id="productpages">
                          <ul class="unorder-list">
                            <li class="">
                              @foreach($result['commonContent']['newsCategories'] as $categories)
                              <a href="{{ URL::to('/news?category='.$categories->slug)}}" class="btn btn-primary">
                                {{$categories->name}}
                              </a>
                              @endforeach
                            </li>
                          </ul>
                      </div> -->
                      <?php if(auth()->guard('customer')->check()){ ?>
                       <a href="{{url('profile')}}" class="main-manu btn btn-primary">@lang('website.Profile')</a>
                       <a href="{{url('wishlist')}}" class="main-manu btn btn-primary">@lang('website.Wishlist')</a>
                       <a href="{{url('compare')}}" class="main-manu btn btn-primary">@lang('website.Compare')&nbsp;(<span id="compare">{{$count}}</span>)</a>
                       <a href="{{url('orders')}}" class="main-manu btn btn-primary">@lang('website.Orders')</a>
                       <a href="{{url('shipping-address')}}" class="main-manu btn btn-primary">@lang('website.Shipping Address')</a>
                       <a href="{{url('logout')}}" class="main-manu btn btn-primary">@lang('website.Logout')</a>
                      <?php }else{ ?>
                        <div class="nav-link">@lang('website.Welcome')!</div>
                         <a href="{{ URL::to('/login')}}" class="main-manu btn btn-primary"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;@lang('website.Login/Register')</a>
                       <?php } ?>
                  </nav>
              </div>

          </div>



          <div class="col-8">
            <a href="{{ URL::to('/')}}" class="logo">
            
            @if($result['commonContent']['setting'][77]->value=='name')
            <?=stripslashes($result['commonContent']['setting'][78]->value)?>
            @endif

            @if($result['commonContent']['setting'][77]->value=='logo')
            <img src="{{asset('').$result['commonContent']['setting'][15]->value}}" alt="<?=stripslashes($result['commonContent']['setting'][79]->value)?>">
            @endif
           </a>
          </div>

          <div class="col-2 pl-0">
              <div class="cart-dropdown dropdown head-cart-content">
               @include('web.headers.cartButtons.cartButton')
              </div>
          </div>
        </div>
      </div>
    </div>
    <div class="header-navbar bg-menu-bar">
      <div class="container">
        <form class="form-inline" action="{{ URL::to('/shop')}}" method="get">
          <div class="search">
            <!-- <div class="select-control">
              <select class="form-control" name="category">
                <?php productCategories(); ?>
              </select>
            </div> -->
            <input  type="search" placeholder="Search entire store here">
            <button class="btn btn-secondary" type="submit">
            <i class="fa fa-search"></i></button>
          </div>
        </form>
      </div>
    </div>
</header>
