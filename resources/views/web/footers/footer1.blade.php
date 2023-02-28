<!-- //footer style One -->
<link rel="stylesheet" href="{{ asset('/web/css/footer.css') }}"  media="all"/>
<section class="info-boxes-content">
    <div class="container">
        <div class="info-box-full bg-info-boxes-content" style="border: none;">
          <div class="row">
            <div class="col-12 col-md-4 col-lg-4 pl-xl-0">
                <div class="info-box" style="padding: 14px;text-align: center;background: #fff;border-radius: 12px;border: none;">
                    <div class="panel">
                        <div class="block">
                            <h3 style="text-align: center;margin-top: 18px;"><i class="fas fa-unlock" style="display: contents; font-size: 42px;"></i></h3><br/>
                            <h4 class="title font-weight-bold">
                              @if(session()->get('locale') == 'en')
                                @lang('website.securepayment')
                              @else
                              100٪ مدفوعات آمنة
                              @endif
                            </h4>
                            <p>
                            @if(session()->get('locale') == 'en')
                              @lang('website.securepayment_desc')
                            @else
                            تم التحقق من الخروج الآمن بواسطة Norton VeriSign.
                            @endif
                          </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4 pl-xl-0">
                <div class="info-box" style="padding: 14px;text-align: center;background: #fff;border-radius: 12px;border: none;">
                    <div class="panel">
                        <div class="block">
                            <h3 style="text-align: center;margin-top: 18px;"><i class="fas fa-tasks" style="display: contents; font-size: 42px;"></i></h3><br/>
                            <h4 class="title font-weight-bold">
                              @if(session()->get('locale') == 'en')
                                @lang('website.authentic_product')
                              @else
                              منتجات أصلية 100٪
                              @endif
                            </h4>
                            <p>
                            @if(session()->get('locale') == 'en')
                              @lang('website.authentic_product_desc')
                            @else
                            نحن نتعامل فقط مع المنتجات الأصلية
                            @endif
                          </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4 pl-xl-0">
                <div class="info-box" style="padding: 14px;text-align: center;background: #fff;border-radius: 12px;border: none;">
                  <div class="panel">
                      <div class="block">
                          <h3 style="text-align: center;margin-top: 18px;"><i class="fas fa-truck" style="display: contents; font-size: 42px;"></i></h3><br/>

                          <h4 class="title font-weight-bold">
                              @if(session()->get('locale') == 'en')
                                @lang('website.fast_delivery')
                              @else
                              منتجات أصلية 100٪
                              @endif
                            </h4>
                            <p>
                            @if(session()->get('locale') == 'en')
                              @lang('website.fast_delivery_desc')
                            @else
                            توصيل سريع وأسعار منافسة وخدمات ممتازة
                            @endif
                          </p>
                      </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</section>
 <footer id="footerOne" class="footer-area footer-one footer-desktop d-none d-lg-block d-xl-block">
    <div class="container">
      <div class="row">
        <div class="footer content">
              <div class="col-md-5">
                <div class="block-title block-subscribe-title">
                @if(session()->get('locale') == 'en')
                  <h3>JOIN OUR NEWSLETTER</h3>
                @else
                  <h3>اشترك في صحيفتنا الإخبارية</h3>
                @endif
                    <div class="form-subscribe-header">
                    @if(session()->get('locale') == 'en')
                    <label for="newsletter" class="text-small"><span>Receive the latest news, offers and deals going on</span></label>
                    @else
                    <label for="newsletter" class="text-small"><span>تلقي آخر الأخبار والعروض والصفقات الجارية</span></label>
                    @endif

                    </div>
                </div>
              </div>
              <div class="col-md-7">
                <div class="content">
                    <form class="form subscribe" novalidate="novalidate" action="/" method="get" id="newsletter-validate-detail">
                        <div class="field newsletter">
                            <div class="control">
                                <label for="newsletter">
                                    <input name="email" type="email" id="newsletter" placeholder=" @if(session()->get('locale') == 'en')Your email address... @else عنوان بريدك الإلكتروني... @endif" data-validate="{required:true, 'validate-email':true}">
                                </label>
                                <div class="radio-container">
                                    <span>
                                        <input type="radio" id="newsletter_male" checked name="gender" title="Male" class="input-radio validate-one-required-by-name" value="1">
                                        <label for="newsletter_male">
                                          @if(session()->get('locale') == 'en')
                                            Male
                                          @else
                                          ذكر
                                          @endif
                                        </label>
                                    </span>
                                    <span>
                                        <input type="radio" id="newsletter_female" name="gender" title="Female" class="input-radio validate-one-required-by-name" value="2">
                                        <label for="newsletter_female">
                                        @if(session()->get('locale') == 'en')
                                          Female
                                        @else
                                          أنثى
                                        @endif
                                      </label>
                                    </span>
                                </div>
                            </div>
                            <button class="action subscribe primary" title="Subscribe" type="submit" aria-label="Subscribe">
                                <span>@if(session()->get('locale') == 'en') JOIN @else انضم@endif</span>
                            </button>
                        </div>
                    </form>
                </div>
              </div>
          </div>
      </div>
      <div class="row">
        <div class="col-12 col-lg-3">
          <div class="single-footer">
            <h5>
              @if(session()->get('locale') == 'en')
                @lang('website.About Store')
              @else
              حول متجر
              @endif
            </h5>
            <div class="row">
              <div class="col-12 col-lg-8">
                <hr>
              </div>
            </div>
            <ul class="contact-list  pl-0 mb-0">
              {{--<li> <i class="fas fa-map-marker"></i>
              <span>
                @if(session()->get('locale') == 'en')
                  {{$result['commonContent']['setting'][4]->value}} {{$result['commonContent']['setting'][5]->value}} {{$result['commonContent']['setting'][6]->value}}, {{$result['commonContent']['setting'][7]->value}} {{$result['commonContent']['setting'][8]->value}}
                @else
                {{$result['commonContent']['setting'][4]->value_ar}} {{$result['commonContent']['setting'][5]->value_ar}} {{$result['commonContent']['setting'][6]->value_ar}}, {{$result['commonContent']['setting'][7]->value_ar}} {{$result['commonContent']['setting'][8]->value_ar}}
                @endif
              </span>
              </li>
              <li><i class="fas fa-phone"></i><span>({{$result['commonContent']['setting'][11]->value}})</span> </li>
              <li><i class="fas fa-envelope"></i><span> <a href="mailto:{{$result['commonContent']['setting'][3]->value}}" class="underline_animate">{{$result['commonContent']['setting'][3]->value}}</a> </span> </li>--}}
              <li><i class="fas fa-map-marker"></i> <span><span style="font-weight: bold;text-decoration: underline;">Sharjah:</span> Muwaileh Commercial <br> +971 6 575 4123</span></li>
              <li><i class="fas fa-map-marker"></i> <span><span style="font-weight: bold;text-decoration: underline;">Dubai:</span> Dubai Healthcare City <br> +971 4 420 6474</span></li>
              <li><i class="fas fa-map-marker"></i> <span><span style="font-weight: bold;text-decoration: underline;">Abu Dhabi:</span> Khalifa City A <br> +971 2 446 9406</span></li>
              <li><i class="fas fa-envelope"></i> <span> <a href="mailto:{{$result['commonContent']['setting'][3]->value}}" class="underline_animate">{{$result['commonContent']['setting'][3]->value}}</a> </span> </li>
            </ul>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <div class="footer-block">
              <div class="single-footer single-footer-left">
                <h5>
                  @if(session()->get('locale') == 'en')
                  @lang('website.Our Services')
                  @else
                  خدماتنا
                  @endif
                </h5>
                <div class="row">
                    <div class="col-12 col-lg-8">
                      <hr>
                    </div>
                  </div>
                <ul class="links-list pl-0 mb-0">
                  <li>
                    <a href="{{ URL::to('/')}}" class="underline_animate"><i class="fa fa-angle-right"></i>
                    @if(session()->get('locale') == 'en')
                      @lang('website.Home')
                    @else
                      الصفحة الرئيسية
                    @endif
                    </a> </li>
                <li> <a href="{{ URL::to('/shop')}}" class="underline_animate"><i class="fa fa-angle-right"></i>
                @if(session()->get('locale') == 'en')
                  @lang('website.Shop')
                @else
                تسوق
                @endif
                </a> </li>
                <li> <a href="{{ URL::to('/orders')}}" class="underline_animate"><i class="fa fa-angle-right"></i>
                @if(session()->get('locale') == 'en')
                  @lang('website.Orders')
                @else
                أوامر
                @endif
                </a> </li>
                <li> <a href="{{ URL::to('/viewcart')}}" class="underline_animate"><i class="fa fa-angle-right"></i>
                @if(session()->get('locale') == 'en')
                  @lang('website.Shopping Cart')
                @else
                عربة التسوق
                @endif
                </a> </li>
                <li> <a href="{{ URL::to('/wishlist')}}" class="underline_animate"><i class="fa fa-angle-right"></i>
                @if(session()->get('locale') == 'en')
                  @lang('website.Wishlist')
                @else
                الأماني
                @endif
                </a> </li>
                </ul>
              </div>

          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <div class="single-footer single-footer-right">
            <h5>
              @if(session()->get('locale') == 'en')
                  @lang('website.Information')
              @else
              المعلومات
              @endif
            </h5>
            <div class="row">
                <div class="col-12 col-lg-8">
                  <hr>
                </div>
              </div>
            <ul class="links-list pl-0 mb-0">
              @if(count($result['commonContent']['pages']))
                  @foreach($result['commonContent']['pages'] as $page)
                      <li> <a href="{{ URL::to('/page?name='.$page->slug)}}" class="underline_animate"><i class="fa fa-angle-right"></i>
                      @if(session()->get('locale') == 'en')
                      {{$page->name}}
                      @else
                      {{$page->name_ar}}
                      @endif
                    </a> </li>
                  @endforeach
              @endif
                  <li> <a href="{{ URL::to('/contact')}}" class="underline_animate"><i class="fa fa-angle-right"></i>
                  @if(session()->get('locale') == 'en')
                    @lang('website.Contact Us')
                  @else
                  اتصل بنا
                  @endif
                </a> </li>
            </ul>
          </div>
        </div>

        <div class="col-12 col-lg-3">
          <div class="single-footer">
            @if(!empty($result['commonContent']['setting'][89]) and $result['commonContent']['setting'][89]->value==1)
              <div class="newsletter">
                  <h5>@lang('website.Subscribe for Newsletter')</h5>
                  <div class="row">
                      <div class="col-12 col-lg-8">
                        <hr>
                      </div>
                    </div>
                  <div class="block">
                      <form class="form-inline">
                          <div class="search">
                            <input type="email" name="email" id="email" placeholder="@lang('website.Your email address here')">
                            <button type="button" id="subscribe" class="btn btn-secondary">@lang('website.Subscribe')</button>
                              @lang('website.Subscribe')
                              </button>
                              <button class="btn-secondary fas fa-location-arrow" type="submit">
                              </button>
                              <div class="alert alert-success alert-dismissible success-subscribte" role="alert" style="opacity: 500; display: none;"></div>

                              <div class="alert alert-danger alert-dismissible error-subscribte" role="alert" style="opacity: 500; display: none;"></div>
                          </div>
                        </form>
                  </div>
              </div>
              @endif
              <div class="socials">
                  <h5>
                  @if(session()->get('locale') == 'en')
                    @lang('website.Follow Us')
                    @else
                    تابعنا
                    @endif
                  </h5>
                  <div class="row">
                      <div class="col-12 col-lg-8">
                        <hr>
                      </div>
                    </div>
                  <ul class="list">
                    <li>
                        @if(!empty($result['commonContent']['setting'][50]->value))
                          <a href="{{$result['commonContent']['setting'][50]->value}}" class="fab fa-facebook-f" target="_blank"></a>
                          @else
                            <a href="#" class="fab fa-facebook-f"></a>
                          @endif
                      </li>
                      <li>
                      @if(!empty($result['commonContent']['setting'][52]->value))
                          <a href="{{$result['commonContent']['setting'][52]->value}}" class="fab fa-twitter" target="_blank"></a>
                      @else
                          <a href="#" class="fab fa-twitter"></a>
                      @endif</li>
                      <li>
                      @if(!empty($result['commonContent']['setting'][51]->value))
                          <a href="{{$result['commonContent']['setting'][51]->value}}"  target="_blank"><i class="fab fa-google"></i></a>
                      @else
                          <a href="#"><i class="fab fa-google"></i></a>
                      @endif
                      </li>
                      <li>
                      @if(!empty($result['commonContent']['setting'][53]->value))
                          <a href="{{$result['commonContent']['setting'][53]->value}}" class="fab fa-linkedin-in" target="_blank"></a>
                      @else
                          <a href="#" class="fab fa-linkedin-in"></a>
                      @endif
                      </li>
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
                        <img class="img-fluid" src="{{asset('web/images/miscellaneous/payments.png')}}">
                    </div>

                  </div>
                  <div class="col-12 col-md-6">
                    <div class="footer-info">
                        © @lang('website.Copy Rights').  <a href="{{url('/page?name=privacy-policy')}}" class="underline_animate">@lang('website.Privacy')</a>&nbsp;&bull;&nbsp;<a href="{{url('/page?name=terms')}}" class="underline_animate">@lang('website.Terms')</a>
                    </div>

                  </div>
              </div>
            </div>
          </div>
    </div>
</footer>