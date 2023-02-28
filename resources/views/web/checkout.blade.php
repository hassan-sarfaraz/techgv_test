@extends('web.layout')
@section('content')
<!-- checkout Content -->
<section class="checkout-area">
  <div class="container">
    <div class="row">
      <div class="col-12 col-sm-12">
        <div class="row justify-content-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="{{ URL::to('/')}}">
                  @if(session()->get('locale') == 'en')
                  @lang('website.Home')
                  @else
                  الصفحة الرئيسية
                  @endif
                </a>
              </li>
              <li class="breadcrumb-item">
                <a href="javascript:void(0)">
                  @if(session()->get('locale') == 'en')
                  @lang('website.Checkout')
                  @else
                  تابع للدفع
                  @endif
                </a>
              </li>
              <li class="breadcrumb-item">
                <a href="javascript:void(0)">
                  @if(session('step')==0)
                  @if(session()->get('locale') == 'en')
                  @lang('website.Shipping Address')
                  @else
                  عنوان الشحن
                  @endif

                  @elseif(session('step')==1)
                  @if(session()->get('locale') == 'en')
                  @lang('website.Billing Address')
                  @else
                  عنوان الفواتي
                  @endif


                  @elseif(session('step')==2)

                  @if(session()->get('locale') == 'en')
                  @lang('website.Shipping Methods')
                  @else
                  طرق الشحن
                  @endif

                  @elseif(session('step')==3)

                  @if(session()->get('locale') == 'en')
                  @lang('website.Order Detail')
                  @else
                  تفاصيل الطلب
                  @endif
                  @endif
                </a>
              </li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="col-12 col-xl-9 checkout-left">
        <input type="hidden" id="hyperpayresponse" value="@if(!empty(session('paymentResponse'))) @if(session('paymentResponse')=='success') {{session('paymentResponse')}} @else {{session('paymentResponse')}}  @endif @endif">
        <div class="alert alert-danger alert-dismissible" id="paymentError" role="alert" style="display:none;">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          @if(!empty(session('paymentResponse')) and session('paymentResponse')=='error') {{session('paymentResponseData') }} @endif
        </div>
        <div class="row">
          <div class="checkout-module">
            <ul class="nav nav-pills mb-3 checkoutd-nav d-none d-lg-flex" id="pills-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link @if(session('step')==0) active @elseif(session('step')>0) active-check @endif" id="pills-shipping-tab" data-toggle="pill" href="#pills-shipping" role="tab" aria-controls="pills-shipping" aria-selected="true">
                  @if(session()->get('locale') == 'en')
                  @lang('website.Shipping Address')
                  @else
                  عنوان الشحن
                  @endif
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link @if(session('step')==1) active @elseif(session('step')>1) active-check @endif" @if(session('step')>=1) id="pills-billing-tab" data-toggle="pill" href="#pills-billing" role="tab" aria-controls="pills-billing" aria-selected="false" @endif >
                  @if(session()->get('locale') == 'en')
                  @lang('website.Billing Address')
                  @else
                  عنوان الفواتي
                  @endif
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link @if(session('step')==2) active @elseif(session('step')>2) active-check @endif" @if(session('step')>=2) id="pills-method-tab" data-toggle="pill" href="#pills-method" role="tab" aria-controls="pills-method" aria-selected="false" @endif>
                  @if(session()->get('locale') == 'en')
                  @lang('website.Shipping Methods')
                  @else
                  طرق الشحن
                  @endif

                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link @if(session('step')==3) active @elseif(session('step')>3) active-check @endif" @if(session('step')>=3) id="pills-order-tab" data-toggle="pill" href="#pills-order" role="tab" aria-controls="pills-order" aria-selected="false"@endif>
                  @if(session()->get('locale') == 'en')
                  @lang('website.Order Detail')
                  @else
                  تفاصيل الطلب
                  @endif

                </a>
              </li>
            </ul>
            <ul class="nav nav-pills mb-3 checkoutm-nav d-flex d-lg-none" id="pills-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link @if(session('step')==0) active @elseif(session('step')>0) active-check @endif" id="pills-shipping-tab" data-toggle="pill" href="#pills-shipping" role="tab" aria-controls="pills-shipping" aria-selected="true">1</a>
              </li>
              <li class="nav-item second">
                <a class="nav-link @if(session('step')==1) active @elseif(session('step')>1) active-check @endif" @if(session('step')>=1) id="pills-billing-tab" data-toggle="pill" href="#pills-billing" role="tab" aria-controls="pills-billing" aria-selected="false" @endif >2</a>
              </li>
              <li class="nav-item third">
                <a class="nav-link @if(session('step')==2) active @elseif(session('step')>2) active-check @endif" @if(session('step')>=2) id="pills-method-tab" data-toggle="pill" href="#pills-method" role="tab" aria-controls="pills-method" aria-selected="false" @endif>3</a>
              </li>
              <li class="nav-item fourth">
                <a class="nav-link @if(session('step')==3) active @elseif(session('step')>3) active-check @endif" @if(session('step')>=3) id="pills-order-tab" data-toggle="pill" href="#pills-order" role="tab" aria-controls="pills-order" aria-selected="false"@endif>4</a>
              </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade @if(session('step') == 0) show active @endif" id="pills-shipping" role="tabpanel" aria-labelledby="pills-shipping-tab">
                <form name="signup" enctype="multipart/form-data" class="form-validate" action="{{ URL::to('/checkout_shipping_address')}}" method="post">
                  <input type="hidden" required name="_token" id="csrf-token" value="{{ Session::token() }}" />
                  <div class="form-group">
                    <label for="firstName">
                      @if(session()->get('locale') == 'en')
                      @lang('website.First Name')
                      @else
                      الاسم الأول
                      @endif
                    </label>
                    <input type="text" required class="form-control field-validate" id="firstname" name="firstname" value="@if(!empty(session('shipping_address'))>0){{session('shipping_address')->firstname}}@endif" aria-describedby="NameHelp1" placeholder=" @if(session()->get('locale') == 'en')@lang('website.First Name')@elseالاسم الأول@endif">
                    <span style="color:red;" class="help-block error-content" hidden>@if(session()->get('locale') == 'en') @lang('website.Please enter your first name') @else الرجاء إدخال اسمك الأول. @endif</span>
                  </div>
                  <div class="form-group">
                    <label for="lastName">
                      @if(session()->get('locale') == 'en')
                      @lang('website.Last Name')
                      @else
                      الكنية
                      @endif
                    </label>
                    <input type="text" required class="form-control field-validate" id="lastname" name="lastname" value="@if(!empty(session('shipping_address'))>0){{session('shipping_address')->lastname}}@endif" aria-describedby="NameHelp1" placeholder="@if(session()->get('locale') == 'en')@lang('website.Last Name')@elseالكنية@endif">
                    <span style="color:red;" class="help-block error-content" hidden>@if(session()->get('locale') == 'en') @lang('website.Please enter your last name') @else الرجاء إدخال اسمك الأخير. @endif </span>

                  </div>
                  <?php if (Session::get('guest_checkout') == 1) { ?>
                    <div class="form-group">
                      <label for="lastName">@lang('website.Email')</label>
                      <input type="text" required class="form-control field-validate" id="email" name="email" value="@if(!empty(session('shipping_address'))>0){{session('shipping_address')->email}}@endif" aria-describedby="NameHelp1" placeholder="Enter Your Email">
                      <span style="color:red;" class="help-block error-content" hidden>@if(session()->get('locale') == 'en') @lang('website.Please enter your email') @else الرجاء إدخال عنوان بريدك الإلكتروني الصحيح. @endif </span>
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <label for="firstName">
                      @if(session()->get('locale') == 'en')
                      @lang('website.Company')
                      @else
                      الشركة
                      @endif
                    </label>
                    <input type="text" required class="form-control field-validate" id="company" aria-describedby="companyHelp" placeholder="@if(session()->get('locale') == 'en')@lang('website.Company')@elseالشركة@endif" name="company" value="@if(!empty(session('shipping_address'))>0) {{session('shipping_address')->company}}@endif">
                    <span style="color:red;" class="help-block error-content" hidden>@if(session()->get('locale') == 'en') @lang('website.Please enter your company name') @else الرجاء إدخال اسم شركتك. @endif </span>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputAddress1">
                      @if(session()->get('locale') == 'en')
                      @lang('website.Address')
                      @else
                      العنوان
                      @endif
                    </label>
                    <input type="text" required class="form-control field-validate" name="street" id="street" aria-describedby="addressHelp" placeholder=" @if(session()->get('locale') == 'en')
                       @lang('website.Please enter your address')
                        @else
                        الرجاء إدخال عنوانك.
                        @endif">
                    <span style="color:red;" class="help-block error-content" hidden>@if(session()->get('locale') == 'en') @lang('website.Please enter your address') @else الرجاء إدخال عنوانك. @endif </span>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelectCountry1">
                      @if(session()->get('locale') == 'en')
                      @lang('website.Country')
                      @else
                      دولة
                      @endif

                    </label>
                    <div class="select-control">
                      <select required class="form-control field-validate" id="entry_country_id" onChange="getZones();" name="countries_id" aria-describedby="countryHelp">
                        <option value="" selected>@if(session()->get('locale') == 'en')@lang('website.Country')@elseاختر الدولة@endif </option>
                        @if(!empty($result['countries'])>0)
                        @foreach($result['countries'] as $countries)
                        <option value="{{$countries->countries_id}}" @if(!empty(session('shipping_address'))>0) @if(session('shipping_address')->countries_id == $countries->countries_id) selected @endif @endif >{{$countries->countries_name}}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                    <span style="color:red;" class="help-block error-content" hidden>@if(session()->get('locale') == 'en') @lang('website.Please select your country') @else يرجى تحديد بلدك. @endif </span>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelectState1">
                      @if(session()->get('locale') == 'en')
                      @lang('website.State')
                      @else
                      المدينة
                      @endif
                    </label>
                    <div class="select-control">
                      <select required class="form-control field-validate" id="entry_zone_id" name="zone_id" aria-describedby="stateHelp">
                        <option value="">@if(session()->get('locale') == 'en')@lang('website.Select State')@elseاختر المدينة@endif </option>
                        @if(!empty($result['zones'])>0)
                        @foreach($result['zones'] as $zones)
                        <option value="{{$zones->zone_id}}" @if(!empty(session('shipping_address'))>0) @if(session('shipping_address')->zone_id == $zones->zone_id) selected @endif @endif >{{$zones->zone_name}}</option>
                        @endforeach
                        @endif

                        <option value="-1" @if(!empty(session('shipping_address'))>0) @if(session('shipping_address')->zone_id == 'Other') selected @endif @endif>@lang('website.Other')</option>
                      </select>
                    </div>
                    <small id="stateHelp" class="form-text text-muted"></small>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelectCity1">@if(session()->get('locale') == 'en')
                      City
                      @else
                      سيتي
                      @endif</label>
                    <input required type="text" class="form-control field-validate" id="city" name="city" value="@if(!empty(session('shipping_address'))>0){{session('shipping_address')->city}}@endif" placeholder="@if(session()->get('locale') == 'en')Enter Your City @else سيتي @endif">
                    <span style="color:red;" class="help-block error-content" hidden>
                      @if(session()->get('locale') == 'en')
                      @lang('website.Please enter your city')
                      @else
                      الرجاء إدخال مدينتك.
                      @endif
                    </span>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputZpCode1">
                      @if(session()->get('locale') == 'en')@lang('website.Zip/Postal Code')@else الرمز البريدي / الرمز البريدي@endif</label>
                    <input required type="number" class="form-control" id="postcode" aria-describedby="zpcodeHelp" placeholder="@if(session()->get('locale') == 'en')@lang('website.Zip/Postal Code')@else الرمز البريدي / الرمز البريدي@endif" name="postcode" value="@if(!empty(session('shipping_address'))>0){{session('shipping_address')->postcode}}@endif">
                    <span style="color:red;" class="help-block error-content" hidden>@if(session()->get('locale') == 'en') @lang('website.Please enter your Zip/Postal Code') @else الرجاء إدخال الرمز البريدي / الرمز البريدي الخاص بك. @endif </span>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputNumber1">
                      @if(session()->get('locale') == 'en')
                      @lang('website.Phone Number')
                      @else
                      رقم الهاتف
                      @endif
                    </label>
                    <input required type="text" class="form-control" id="delivery_phone" aria-describedby="numberHelp" placeholder="@if(session()->get('locale') == 'en')
                           @lang('website.Phone Number')
                           @elseرقم الهاتف@endif" name="delivery_phone" value="@if(!empty(session('shipping_address'))>0){{session('shipping_address')->delivery_phone}}@endif">
                    <span style="color:red;" class="help-block error-content" hidden>@if(session()->get('locale') == 'en') @lang('website.Please enter your valid phone number') @else الرجاء إدخال رقم هاتفك الصحيح. @endif </span>
                  </div>
                  <div class="col-12 col-sm-12">
                    <div class="row">
                      <button type="submit" class="btn btn-secondary">
                        @if(session()->get('locale') == 'en')
                        @lang('website.Continue')
                        @else
                        متابعة
                        @endif
                      </button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane fade @if(session('step') == 1) show active @endif" id="pills-billing" role="tabpanel" aria-labelledby="pills-billing-tab">
                <form name="signup" enctype="multipart/form-data" action="{{ URL::to('/checkout_billing_address')}}" method="post">
                  <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                  <div class="form-group">
                    <label for="exampleInputName1">@if(session()->get('locale') == 'en')
                      @lang('website.First Name')
                      @else
                      الاسم الأول
                      @endif</label>
                    <input type="text" class="form-control same_address" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif id="billing_firstname" name="billing_firstname" value="@if(!empty(session('billing_address'))>0){{session('billing_address')->billing_firstname}}@endif" aria-describedby="NameHelp1" placeholder="Enter Your Name">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputName2">@if(session()->get('locale') == 'en')
                      @lang('website.Last Name')
                      @else
                      الكنية
                      @endif</label>
                    <input type="text" class="form-control same_address" id="exampleInputName2" aria-describedby="NameHelp2" placeholder="Enter Your Name" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif id="billing_lastname" name="billing_lastname" value="@if(!empty(session('billing_address'))>0){{session('billing_address')->billing_lastname}}@endif">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputCompany1">@if(session()->get('locale') == 'en')
                      @lang('website.Company')
                      @else
                      الشركة
                      @endif</label>
                    <input type="text" class="form-control same_address" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif id="billing_company" name="billing_company" value="@if(!empty(session('billing_address'))>0){{session('billing_address')->billing_company}}@endif" id="exampleInputCompany1" aria-describedby="companyHelp" placeholder="Enter Your Company Name">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your company name')</span>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputAddress1">@if(session()->get('locale') == 'en')
                      @lang('website.Address')
                      @else
                      العنوان
                      @endif</label>
                    <input type="text" class="form-control same_address" id="exampleInputAddress1" aria-describedby="addressHelp" placeholder="Enter Your Address" @if(!empty(session('22'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif id="billing_street" name="billing_street" value="@if(!empty(session('billing_address'))>0){{session('billing_address')->billing_street}}@endif">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your address')</span>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelectCountry1">@if(session()->get('locale') == 'en')
                      @lang('website.Country')
                      @else
                      البلد
                      @endif </label>
                    <div class="select-control">
                      <select required class="form-control same_address_select" id="billing_countries_id" aria-describedby="countryHelp" onChange="getBillingZones();" name="billing_countries_id" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) disabled @endif @else disabled @endif>
                        <option value="">@lang('website.Select Country')</option>
                        @if(!empty($result['countries'])>0)
                        @foreach($result['countries'] as $countries)
                        <option value="{{$countries->countries_id}}" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->billing_countries_id == $countries->countries_id) selected @endif @endif >{{$countries->countries_name}}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                    <span class="help-block error-content" hidden>@lang('website.Please select your country')</span>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelectState1">@if(session()->get('locale') == 'en')
                      @lang('website.State')
                      @else
                      الدولة
                      @endif </label>
                    <div class="select-control">
                      <select required class="form-control same_address_select" name="billing_zone_id" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) disabled @endif @else disabled @endif id="billing_zone_id" aria-describedby="stateHelp">
                        <option value="">@lang('website.Select State')</option>
                        @if(!empty($result['zones'])>0)
                        @foreach($result['zones'] as $key=>$zones)
                        <option value="{{$zones->zone_id}}" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->billing_zone_id == $zones->zone_id) selected @endif @endif >{{$zones->zone_name}}</option>
                        @endforeach
                        @endif
                        <option value="-1" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->billing_zone_id == 'Other') selected @endif @endif>@lang('website.Other')</option>
                      </select>
                    </div>
                    <span class="help-block error-content" hidden>@lang('website.Please select your state')</span>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelectCity1">
                      @if(session()->get('locale') == 'en')
                      @lang('website.City')
                      @else
                      سيتي
                      @endif</label>
                    <input type="text" class="form-control same_address" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif id="billing_city" name="billing_city" value="@if(!empty(session('billing_address'))>0){{session('billing_address')->billing_city}}@endif" placeholder="Enter Your City">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your city')</span>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputZpCode1">@if(session()->get('locale') == 'en') @lang('website.Zip/Postal Code') @else الرمز البريدي / الرمز البريدي @endif</label>
                    <input type="text" class="form-control same_address" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif id="billing_zip" name="billing_zip" value="@if(!empty(session('billing_address'))>0){{session('billing_address')->billing_zip}}@endif" aria-describedby="zpcodeHelp" placeholder="Enter Your Zip / Postal Code">
                    <small id="zpcodeHelp" class="form-text text-muted"></small>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputNumber1">@if(session()->get('locale') == 'en') @lang('website.Phone Number') @else رقم الهاتف @endif</label>
                    <input type="text" class="form-control same_address" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif id="billing_phone" name="billing_phone" value="@if(!empty(session('billing_address'))>0){{session('billing_address')->billing_phone}}@endif" aria-describedby="numberHelp" placeholder="Enter Your Phone Number">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span>
                  </div>
                  <div class="form-group">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="same_billing_address" value="1" name="same_billing_address" @if(!empty(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) checked @endif @else checked @endif >
                      @if(session()->get('locale') == 'en')@lang('website.Same shipping and billing address')@else نفس عنوان الشحن والفواتير @endif

                      <small id="checkboxHelp" class="form-text text-muted"></small>
                    </div>
                  </div>

                  <div class="col-12 col-sm-12">
                    <div class="row">
                      <button type="submit" class="btn btn-secondary"><span>@if(session()->get('locale') == 'en')
                          @lang('website.Continue')
                          @else
                          متابعة
                          @endif<i class="fas fa-caret-right"></i></span></button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane fade  @if(session('step') == 2) show active @endif" id="pills-method" role="tabpanel" aria-labelledby="pills-method-tab">

                <div class="col-12 col-sm-12 ">
                  <div class="row">
                    <p>
                      @if(session()->get('locale') == 'en')
                      @lang('website.Please select a prefered shipping method to use on this order')
                      @else
                      يُرجى تحديد طريقة شحن مفضلة لاستخدامها في هذا الطلب.
                      @endif
                    </p>
                  </div>
                </div>
                <form name="shipping_mehtods" method="post" id="shipping_mehtods_form" enctype="multipart/form-data" action="{{ URL::to('/checkout_payment_method')}}">
                  <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                  @if(!empty($result['shipping_methods'])>0)
                  <input type="hidden" name="mehtod_name" id="mehtod_name">
                  <input type="hidden" name="shipping_price" id="shipping_price">

                  @foreach($result['shipping_methods'] as $shipping_methods)
                  <div class="heading">
                    <h2>
                      @if(session()->get('locale') == 'en')
                      {{$shipping_methods['name']}}
                      @else
                      @if($shipping_methods['name'] == 'Flat Rate')
                      معدل
                      @elseif($shipping_methods['name'] == 'Shipping Price')
                      سعر الشحن
                      @endif
                      @endif

                    </h2>
                    <hr>
                  </div>
                  <div class="form-check">

                    <div class="form-row">
                      @if($shipping_methods['success']==1)
                      <ul class="list" style="list-style:none; padding: 0px;">
                        @foreach($shipping_methods['services'] as $services)
                        <?php
                        if ($services['shipping_method'] == 'upsShipping')
                          $method_name = $shipping_methods['name'] . '(' . $services['name'] . ')';
                        else {
                          $method_name = $services['name'];
                        }
                        ?>
                        <li>
                          @php
                          $default_currency = DB::table('currencies')->where('is_default',1)->first();
                          if($default_currency->id == Session::get('currency_id')){

                          $currency_value = 1;
                          }else{
                          $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();

                          $currency_value = $session_currency->value;
                          }
                          if(!empty(session('shipping_detail')) and empty(session('shipping_detail')->mehtod_name)) {
                          session('shipping_detail')->mehtod_name = "Flat Rate";
                          }
                          @endphp
                          <input class="shipping_data" id="{{$method_name}}" type="radio" name="shipping_method" required value="{{$services['shipping_method']}}" shipping_price="{{$services['rate']}}" method_name="{{$method_name}}" @if(!empty(session('shipping_detail')) and !empty(session('shipping_detail'))> 0)
                          @if(session('shipping_detail')->mehtod_name == $method_name) checked @endif {{--@elseif($shipping_methods['is_default']==1) checked--}} @endif
                          >
                          <label for="{{$method_name}}">

                            @if(session()->get('locale') == 'en')
                            {{$services['name']}}
                            @else
                            @if($services['name'] == 'Flat Rate')
                            معدل
                            @elseif($services['name'] == 'Shipping Price')
                            سعر الشحن
                            @endif
                            @endif
                            --- {{Session::get('symbol_left')}} {{$services['rate']* $currency_value}}{{Session::get('symbol_right')}}</label>
                        </li>
                        @endforeach
                      </ul>
                      @else
                      <ul class="list" style="list-style:none; padding: 0px;">
                        <li>@lang('website.Your location does not support this') {{$shipping_methods['name']}}.</li>
                      </ul>
                      @endif
                    </div>
                  </div>
                  @endforeach
                  @endif
                  <div class="alert alert-danger alert-dismissible error_shipping" role="alert" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    @lang('website.Please select your shipping method')
                  </div>
                  <div class="row">
                    <button type="submit" class="btn btn-secondary"><span>@if(session()->get('locale') == 'en')CONTINUE @else استمر @endif<i class="fas fa-caret-right"></i></span></button>
                  </div>
                </form>


              </div>
              <div class="tab-pane fade @if(session('step') == 3) show active @endif" id="pills-order" role="tabpanel" aria-labelledby="pills-method-order">
                <?php
                $price = 0;
                ?>
                <form method='POST' id="update_cart_form" action='{{ URL::to('/place_order')}}'>
                  {!! csrf_field() !!}

                  <table class="table top-table">
                    <thead>
                      <tr class="d-flex">
                        @if(session()->get('locale') == 'en')
                        <th class="col-12 col-md-2">@lang('website.items')</th>
                        <th class="col-12 col-md-4"></th>
                        <th class="col-12 col-md-2">@lang('website.Price')</th>
                        <th class="col-12 col-md-2">@lang('website.Qty')</th>
                        <th class="col-12 col-md-2">@lang('website.SubTotal')</th>
                        @else
                        <th class="col-12 col-md-2">العناصر</th>
                        <th class="col-12 col-md-4"></th>
                        <th class="col-12 col-md-2">الأسعار</th>
                        <th class="col-12 col-md-2">الكمية</th>
                        <th class="col-12 col-md-2">المجموع الفرعي</th>
                        @endif
                      </tr>
                    </thead>

                    @foreach( $result['cart'] as $products)
                    <?php
                    $default_currency = DB::table('currencies')->where('is_default', 1)->first();
                    if ($default_currency->id == Session::get('currency_id')) {
                      $orignal_price = $products->final_price;
                    } else {
                      $session_currency = DB::table('currencies')->where('id', Session::get('currency_id'))->first();
                      $orignal_price = $products->final_price * $session_currency->value;
                    }

                    $price += $orignal_price * $products->customers_basket_quantity;
                    ?>

                    <tbody>
                      <tr class="d-flex">
                        <td class="col-12 col-md-2 item">
                          <input type="hidden" name="cart[]" value="{{$products->customers_basket_id}}">
                          <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="cart-thumb">
                            <img class="img-fluid" src="{{asset('').$products->image_path}}" alt="{{$products->products_name}}" alt="">
                          </a>
                        </td>
                        <td class="col-12 col-md-4 item-detail-left">
                          <div class="item-detail">
                            <h4>{{$products->products_name}}</h4>
                            <div class="item-attributes"></div>
                          </div>
                        </td>

                        <?php
                        $default_currency = DB::table('currencies')->where('is_default', 1)->first();
                        if ($default_currency->id == Session::get('currency_id')) {
                          $orignal_price = $products->final_price;
                        } else {
                          $session_currency = DB::table('currencies')->where('id', Session::get('currency_id'))->first();
                          $orignal_price = $products->final_price * $session_currency->value;
                        }

                        $tax = ($orignal_price * Config::get('global.p_constant'));
                        $orignal_price = $orignal_price + $tax;
                        $orignal_price = round($orignal_price, 2);
                        ?>

                        <td class="item-price col-12 col-md-2"><span>{{Session::get('symbol_left')}} {{pd_adjust_price($orignal_price+0)}}{{Session::get('symbol_right')}}</span></td>
                        <td class="col-12 col-md-2">
                          <div class="input-group item-quantity">

                            <input type="text" id="quantity" readonly name="quantity" class="form-control input-number" value="{{$products->customers_basket_quantity}}">
                          </div>

                        </td>
                        <td class="align-middle item-total col-12 col-md-2 subtotal" align="center"><span class="cart_price_{{$products->customers_basket_id}}">{{Session::get('symbol_left')}} {{$orignal_price * $products->customers_basket_quantity}}{{Session::get('symbol_right')}}</span>
                        </td>
                      </tr>
                      <tr class="d-flex">
                        <td class="col-12 col-md-2 p-0">
                          <div class="item-controls">
                            <button type="button" class="btn">
                              <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"><span class="fas fa-pencil-alt"></span></a>
                            </button>
                            <button type="button" class="btn">
                              <a href="{{ URL::to('/deleteCart?id='.$products->customers_basket_id)}}"><span class="fas fa-times"></span></a>
                            </button>
                          </div>
                        </td>
                        <td class="col-12 col-md-10 d-none d-md-block"></td>
                      </tr>
                    </tbody>
                    @endforeach
                  </table>
                  <?php
                  if (!empty(session('shipping_detail')) and !empty(session('shipping_detail')) > 0) {
                    $shipping_price = session('shipping_detail')->shipping_price;
                    $shipping_name = session('shipping_detail')->mehtod_name;
                  } else {
                    $shipping_price = 0;
                    $shipping_name = '';
                  }
                  $tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
                  $tax_rate = ($price * Config::get('global.p_constant'));
                  $coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');
                  $total_price = ($price + $tax_rate + $shipping_price) - $coupon_discount;

                  session(['total_price' => $total_price]);

                  ?>
                  <input type="hidden" name="method_payment" id="method_payment" value="">
                </form>
                <div class="col-12 col-sm-12">
                  <div class="row">
                    <div class="heading">
                      <h2>
                        @if(session()->get('locale') == 'en')
                        @lang('website.orderNotesandSummary')
                        @else
                        ملاحظات الطلب والملخص
                        @endif
                      </h2>
                      <hr>
                    </div>
                    <div class="form-group" style="width:100%; padding:0;">
                      <label for="exampleFormControlTextarea1">
                        @if(session()->get('locale') == 'en')
                        @lang('website.Please write notes of your order')
                        @else
                        يرجى كتابة ملاحظات من طلبك
                        @endif
                      </label>
                      <textarea name="comments" class="form-control" id="order_comments" rows="3">@if(!empty(session('order_comments'))){{session('order_comments')}}@endif</textarea>
                    </div>
                  </div>

                </div>
                <div class="col-12 col-sm-12 mb-3">
                  <div class="row">
                    <div class="heading">
                      <h2>
                        @if(session()->get('locale') == 'en')
                        @lang('website.Payment Methods')
                        @else
                        طرق الدفع
                        @endif
                      </h2>
                      <hr>
                    </div>

                    <div class="form-group" style="width:100%; padding:0;">
                      <p class="title">
                        @if(session()->get('locale') == 'en')
                        @lang('website.Please select a prefered payment method to use on this order')
                        @else
                        يرجى تحديد طريقة دفع مفضلة لاستخدامها في هذا الطلب
                        @endif
                      </p>

                      <div class="alert alert-danger error_payment" style="display:none" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        @if(session()->get('locale') == 'en')
                        @lang('website.Please select your payment method')
                        @else
                        يرجى تحديد طريقة الدفع الخاصة بك.
                        @endif
                      </div>

                      <form name="shipping_mehtods" method="post" id="payment_mehtods_form" enctype="multipart/form-data" action="{{ URL::to('/order_detail')}}">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <ul class="list" style="list-style:none; padding: 0px;">
                          @foreach($result['payment_methods'] as $payment_methods)
                          @if($payment_methods['active']==1)
                          <input id="payment_currency" type="hidden" onClick="paymentMethods();" name="payment_currency" value="{{$payment_methods['payment_currency']}}">
                          @if($payment_methods['payment_method']=='braintree')

                          <input id="{{$payment_methods['payment_method']}}_public_key" type="hidden" name="public_key" value="{{$payment_methods['public_key']}}">
                          <input id="{{$payment_methods['payment_method']}}_environment" type="hidden" name="{{$payment_methods['payment_method']}}_environment" value="{{$payment_methods['environment']}}">
                          <li>
                            <input type="radio" onClick="paymentMethods();" name="payment_method" class="payment_method" value="{{$payment_methods['payment_method']}}" @if(!empty(session('payment_method'))) @if(session('payment_method')==$payment_methods['payment_method']) checked @endif @endif>
                            <label for="{{$payment_methods['payment_method']}}">
                              {{$payment_methods['name']}}
                            </label>
                          </li>

                          @elseif($payment_methods['payment_method']=='Payd')
                          <li>
                            <input onClick="paymentMethods();" type="radio" name="payment_method" class="payment_method" id="{{$payment_methods['payment_method']}}" value="{{$payment_methods['payment_method']}}" @if(!empty(session('payment_method'))) @if(session('payment_method')==$payment_methods['payment_method']) checked @endif @endif>
                            <label for="{{$payment_methods['payment_method']}}">{{$payment_methods['name']}}</label>
                          </li>
                          @elseif($payment_methods['payment_method']=='pay_with_credit_card')
                          <li>
                            <input onClick="paymentMethods();" type="radio" name="payment_method" class="payment_method" id="{{$payment_methods['payment_method']}}" value="{{$payment_methods['payment_method']}}" @if(!empty(session('payment_method'))) @if(session('payment_method')==$payment_methods['payment_method']) checked @endif @endif>
                            <!-- <label for="{{$payment_methods['payment_method']}}">{{$payment_methods['name']}}</label> -->
                            <label for="{{$payment_methods['payment_method']}}">Credit/Debit Card Payment</label>
                          </li>
                          @elseif($payment_methods['payment_method']=='pay_in_installments')
                          <li>
                            <input onClick="paymentMethods();" type="radio" name="payment_method" class="payment_method" id="postpay" value="{{$payment_methods['payment_method']}}" @if(!empty(session('payment_method'))) @if(session('payment_method')==$payment_methods['payment_method']) checked @endif @endif>
                            <!-- <label for="{{$payment_methods['payment_method']}}">{{$payment_methods['name']}}</label> -->
                            <label for="{{$payment_methods['payment_method']}}">Pay in 3 with Postpay</label>
                          </li>
                          @elseif($payment_methods['payment_method']=='pickup_from_store')
                          <li>
                            <input onClick="paymentMethods();" type="radio" name="payment_method" class="payment_method" id="{{$payment_methods['payment_method']}}" value="{{$payment_methods['payment_method']}}" @if(!empty(session('payment_method'))) @if(session('payment_method')==$payment_methods['payment_method']) checked @endif @endif>
                            <label for="{{$payment_methods['payment_method']}}">{{$payment_methods['name']}}</label>
                          </li>
                          @else
                          <input id="{{$payment_methods['payment_method']}}_public_key" type="hidden" name="public_key" value="{{$payment_methods['public_key']}}">
                          <input id="{{$payment_methods['payment_method']}}_environment" type="hidden" name="{{$payment_methods['payment_method']}}_environment" value="{{$payment_methods['environment']}}">

                          <li>
                            <input onClick="paymentMethods();" type="radio" name="payment_method" class="payment_method" value="{{$payment_methods['payment_method']}}" @if(!empty(session('payment_method'))) @if(session('payment_method')==$payment_methods['payment_method']) checked @endif @endif>
                            <!-- <label for="{{$payment_methods['payment_method']}}">{{$payment_methods['name']}}</label> -->
                            <label for="{{$payment_methods['payment_method']}}">Pay on Delivery</label>
                          </li>
                          @endif

                          @endif
                          @endforeach
                        </ul>

                      </form>
                      <div class="float-left" id="msform">
                      </div>
                    </div>


                    <div class="button">

                      <!--- paypal -->
                      <div id="paypal_button" class="payment_btns" style="display: none"></div>

                      <button id="braintree_button" style="display: none" class="btn btn-dark payment_btns" data-toggle="modal" data-target="#braintreeModel">
                        @if(session()->get('locale') == 'en')
                        @lang('website.Order Now')
                        @else
                        اطلب الآن
                        @endif
                      </button>

                      <button id="stripe_button" class="btn btn-dark payment_btns" style="display: none" data-toggle="modal" data-target="#stripeModel">@if(session()->get('locale') == 'en')
                        @lang('website.Order Now')
                        @else
                        اطلب الآن
                        @endif</button>

                      <button id="cash_on_delivery_button" class="btn btn-dark payment_btns" style="display: none">@if(session()->get('locale') == 'en')
                        @lang('website.Order Now')
                        @else
                        اطلب الآن
                        @endif</button>
                      <button id="instamojo_button" class="btn btn-dark payment_btns" style="display: none" data-toggle="modal" data-target="#instamojoModel">@if(session()->get('locale') == 'en')
                        @lang('website.Order Now')
                        @else
                        اطلب الآن
                        @endif</button>

                      <a href="{{ URL::to('/checkout/hyperpay')}}" id="hyperpay_button" class="btn btn-dark payment_btns" style="display: none">@if(session()->get('locale') == 'en')
                        @lang('website.Order Now')
                        @else
                        اطلب الآن
                        @endif</a>

                      <button id="Payd_button" class="btn btn-dark payment_btns" style="display: none" data-toggle="modal" data-target="#paydModel">@if(session()->get('locale') == 'en')
                        @lang('website.Order Now')
                        @else
                        اطلب الآن
                        @endif</button>

                      <button id="pay_with_credit_card_button" class="btn btn-dark payment_btns" style="display: none" data-toggle="modal" data-target="#pay_with_credit_card_model">@if(session()->get('locale') == 'en')
                        @lang('website.Order Now')
                        @else
                        اطلب الآن
                        @endif</button>

                      <button id="pay_in_installments_button" class="btn btn-dark payment_btns" style="display: none" data-toggle="modal" data-target="#pay_in_installments_model">@if(session()->get('locale') == 'en')
                        @lang('website.Order Now')
                        @else
                        اطلب الآن
                        @endif</button>

                      <div class="form-group row" style="width: 100%;display:none;" id="pickup_from_store_dropdown">
                        <label for="pickup_store" class="col-sm-2 col-form-label">Store:</label>
                        <div class="col-sm-10">
                          <select name="pickup_store" id="pickup_store" class="form-control" style="width: 250px;">
                            @php
                            $stores_data = \App\Models\Core\Stores::select('stores.stores_id as id', 'stores.store_name as name', 'stores.status as status')
                            ->leftJoin('stores_info','stores_info.stores_id', '=', 'stores.stores_id')
                            ->where('stores_info.languages_id', 1)
                            ->where('stores.status', 1)
                            ->get();

                            foreach ($stores_data as $stores_datum) {
                            echo '<option value="'.$stores_datum['id'].'">'.$stores_datum['name'].'</option>';
                            }
                            @endphp
                          </select>
                        </div>
                      </div>
                      <button id="pickup_from_store_button" class="btn btn-dark payment_btns" style="display: none">@if(session()->get('locale') == 'en')
                        @lang('website.Order Now')
                        @else
                        اطلب الآن
                        @endif</button>

                    </div>
                  </div>
                  <!-- The braintree Modal -->
                  <div class="modal fade" id="braintreeModel">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form id="checkout" method="post" action="{{ URL::to('/place_order')}}">
                          <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                          <!-- Modal Header -->
                          <div class="modal-header">
                            <h4 class="modal-title">@lang('website.BrainTree Payment')</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <div id="payment-form"></div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-dark">@lang('website.Pay') {{Session::get('symbol_left')}}{{number_format((float)$total_price+0, 2, '.', '')}}{{Session::get('symbol_right')}}</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>


                  <!-- The payd Modal -->
                  <div class="modal fade" id="paydModel">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form id="payd_form" method="post" action="">
                          <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                          <input type="hidden" name="amount" value="{{ $total_price  }}">
                          <input type="hidden" name="cart" value="{{ json_encode($result['cart'])  }}">
                          <!-- Modal Header -->
                          <div class="modal-header">
                            <h4 class="modal-title">@lang('website.Payd Payment')</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <div class="from-group mb-3">
                              <div class="col-12"> <label for="inlineFormInputGroup">@lang('website.Full Name')</label></div>
                              <div class="input-group col-12">
                                <input type="text" name="fullname" id="firstName" value="@if(!empty(session('shipping_address'))>0){{session('shipping_address')->firstname.' '.session('shipping_address')->lastname}}@endif" placeholder="@lang('website.Full Name')" class="form-control">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your full name')</span>
                              </div>
                            </div>
                            <div class="from-group mb-3">
                              <div class="col-12"> <label for="inlineFormInputGroup">@lang('website.Email')</label></div>
                              <div class="input-group col-12">
                                <input type="text" value="<?php if (auth()->guard('customer')->check()) { ?>{{auth()->guard('customer')->user()->email}} <?php } ?>" name="email_id" id="email_id" placeholder="@lang('website.Email')" class="form-control">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your email address')</span>
                              </div>
                            </div>
                            <div class="from-group mb-3">
                              <div class="col-12"> <label for="inlineFormInputGroup">@lang('website.Phone Number')</label></div>
                              <div class="input-group col-12">
                                <input type="text" value="@if(!empty(session('shipping_address'))>0){{session('shipping_address')->delivery_phone}}@endif" name="phone_number" id="insta_phone_number" placeholder="@lang('website.Phone Number')" class="form-control">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span>
                              </div>
                            </div>

                            <div class="alert alert-danger alert-dismissible" id="insta_mojo_error" role="alert" style="display: none">
                              <span class="sr-only">@lang('website.Error'):</span>
                              <span id="payd-error-text"></span>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" id="pay_payd" class="btn btn-dark">@lang('website.Pay') {{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')}}</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <!-- Full payment order form -->
                  <?php
                  $price = 0;
                  ?>
                  <form method='POST' id="full_payment_cart_form" action='{{ route('postpayFullIntsallment')}}'>
                    {!! csrf_field() !!}
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <input type="hidden" name="amount" value="{{ $total_price  }}">
                    <input type="hidden" name="productCart" value="{{ json_encode($result['cart'])  }}">
                    <table class="table top-table" style="display: none;">
                      <thead>
                        <tr class="d-flex">
                          @if(session()->get('locale') == 'en')
                          <th class="col-12 col-md-2">@lang('website.items')</th>
                          <th class="col-12 col-md-4"></th>
                          <th class="col-12 col-md-2">@lang('website.Price')</th>
                          <th class="col-12 col-md-2">@lang('website.Qty')</th>
                          <th class="col-12 col-md-2">@lang('website.SubTotal')</th>
                          @else
                          <th class="col-12 col-md-2">العناصر</th>
                          <th class="col-12 col-md-4"></th>
                          <th class="col-12 col-md-2">الأسعار</th>
                          <th class="col-12 col-md-2">الكمية</th>
                          <th class="col-12 col-md-2">المجموع الفرعي</th>
                          @endif
                        </tr>
                      </thead>

                      @foreach( $result['cart'] as $products)
                      <?php
                      $default_currency = DB::table('currencies')->where('is_default', 1)->first();
                      if ($default_currency->id == Session::get('currency_id')) {
                        $orignal_price = $products->final_price;
                      } else {
                        $session_currency = DB::table('currencies')->where('id', Session::get('currency_id'))->first();
                        $orignal_price = $products->final_price * $session_currency->value;
                      }

                      $price += $orignal_price * $products->customers_basket_quantity;
                      ?>

                      <tbody>
                        <tr class="d-flex">
                          <td class="col-12 col-md-2 item">
                            <input type="hidden" name="cart[]" value="{{$products->customers_basket_id}}">
                            <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="cart-thumb">
                              <img class="img-fluid" src="{{asset('').$products->image_path}}" alt="{{$products->products_name}}" alt="">
                            </a>
                          </td>
                          <td class="col-12 col-md-4 item-detail-left">
                            <div class="item-detail">
                              <h4>{{$products->products_name}}</h4>
                              <div class="item-attributes"></div>
                            </div>
                          </td>

                          <?php
                          $default_currency = DB::table('currencies')->where('is_default', 1)->first();
                          if ($default_currency->id == Session::get('currency_id')) {
                            $orignal_price = $products->final_price;
                          } else {
                            $session_currency = DB::table('currencies')->where('id', Session::get('currency_id'))->first();
                            $orignal_price = $products->final_price * $session_currency->value;
                          }

                          $tax = ($orignal_price * Config::get('global.p_constant'));
                          $orignal_price = $orignal_price + $tax;
                          $orignal_price = round($orignal_price, 2);
                          ?>

                          <td class="item-price col-12 col-md-2"><span>{{Session::get('symbol_left')}} {{pd_adjust_price($orignal_price+0)}}{{Session::get('symbol_right')}}</span></td>
                          <td class="col-12 col-md-2">
                            <div class="input-group item-quantity">

                              <input type="text" id="quantity" readonly name="quantity" class="form-control input-number" value="{{$products->customers_basket_quantity}}">
                            </div>

                          </td>
                          <td class="align-middle item-total col-12 col-md-2 subtotal" align="center"><span class="cart_price_{{$products->customers_basket_id}}">{{Session::get('symbol_left')}} {{$orignal_price * $products->customers_basket_quantity}}{{Session::get('symbol_right')}}</span>
                          </td>
                        </tr>
                        <tr class="d-flex">
                          <td class="col-12 col-md-2 p-0">
                            <div class="item-controls">
                              <button type="button" class="btn">
                                <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"><span class="fas fa-pencil-alt"></span></a>
                              </button>
                              <button type="button" class="btn">
                                <a href="{{ URL::to('/deleteCart?id='.$products->customers_basket_id)}}"><span class="fas fa-times"></span></a>
                              </button>
                            </div>
                          </td>
                          <td class="col-12 col-md-10 d-none d-md-block"></td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    <?php
                    if (!empty(session('shipping_detail')) and !empty(session('shipping_detail')) > 0) {
                      $shipping_price = session('shipping_detail')->shipping_price;
                      $shipping_name = session('shipping_detail')->mehtod_name;
                    } else {
                      $shipping_price = 0;
                      $shipping_name = '';
                    }
                    $tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
                    $tax_rate = ($price * Config::get('global.p_constant'));
                    $coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');
                    $total_price = ($price + $tax_rate + $shipping_price) - $coupon_discount;

                    session(['total_price' => $total_price]);

                    ?>
                    <input type="hidden" name="method_payment" id="method_payment" value="">
                  </form>

                  <!-- Full payment order form -->
                  <?php
                  $price = 0;
                  ?>
                  <form method='POST' id="installments_cart_form" action='{{ route('postpayIntsallments')}}'>
                    {!! csrf_field() !!}
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <input type="hidden" name="amount" value="{{ $total_price  }}">
                    <input type="hidden" name="productCart" value="{{ json_encode($result['cart'])  }}">
                    <table class="table top-table" style="display: none;">
                      <thead>
                        <tr class="d-flex">
                          @if(session()->get('locale') == 'en')
                          <th class="col-12 col-md-2">@lang('website.items')</th>
                          <th class="col-12 col-md-4"></th>
                          <th class="col-12 col-md-2">@lang('website.Price')</th>
                          <th class="col-12 col-md-2">@lang('website.Qty')</th>
                          <th class="col-12 col-md-2">@lang('website.SubTotal')</th>
                          @else
                          <th class="col-12 col-md-2">العناصر</th>
                          <th class="col-12 col-md-4"></th>
                          <th class="col-12 col-md-2">الأسعار</th>
                          <th class="col-12 col-md-2">الكمية</th>
                          <th class="col-12 col-md-2">المجموع الفرعي</th>
                          @endif
                        </tr>
                      </thead>

                      @foreach( $result['cart'] as $products)
                      <?php
                      $default_currency = DB::table('currencies')->where('is_default', 1)->first();
                      if ($default_currency->id == Session::get('currency_id')) {
                        $orignal_price = $products->final_price;
                      } else {
                        $session_currency = DB::table('currencies')->where('id', Session::get('currency_id'))->first();
                        $orignal_price = $products->final_price * $session_currency->value;
                      }

                      $price += $orignal_price * $products->customers_basket_quantity;
                      ?>

                      <tbody>
                        <tr class="d-flex">
                          <td class="col-12 col-md-2 item">
                            <input type="hidden" name="cart[]" value="{{$products->customers_basket_id}}">
                            <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="cart-thumb">
                              <img class="img-fluid" src="{{asset('').$products->image_path}}" alt="{{$products->products_name}}" alt="">
                            </a>
                          </td>
                          <td class="col-12 col-md-4 item-detail-left">
                            <div class="item-detail">
                              <h4>{{$products->products_name}}</h4>
                              <div class="item-attributes"></div>
                            </div>
                          </td>

                          <?php
                          $default_currency = DB::table('currencies')->where('is_default', 1)->first();
                          if ($default_currency->id == Session::get('currency_id')) {
                            $orignal_price = $products->final_price;
                          } else {
                            $session_currency = DB::table('currencies')->where('id', Session::get('currency_id'))->first();
                            $orignal_price = $products->final_price * $session_currency->value;
                          }

                          $tax = ($orignal_price * Config::get('global.p_constant'));
                          $orignal_price = $orignal_price + $tax;
                          $orignal_price = round($orignal_price, 2);
                          ?>

                          <td class="item-price col-12 col-md-2"><span>{{Session::get('symbol_left')}} {{pd_adjust_price($orignal_price+0)}}{{Session::get('symbol_right')}}</span></td>
                          <td class="col-12 col-md-2">
                            <div class="input-group item-quantity">

                              <input type="text" id="quantity" readonly name="quantity" class="form-control input-number" value="{{$products->customers_basket_quantity}}">
                            </div>

                          </td>
                          <td class="align-middle item-total col-12 col-md-2 subtotal" align="center"><span class="cart_price_{{$products->customers_basket_id}}">{{Session::get('symbol_left')}} {{$orignal_price * $products->customers_basket_quantity}}{{Session::get('symbol_right')}}</span>
                          </td>
                        </tr>
                        <tr class="d-flex">
                          <td class="col-12 col-md-2 p-0">
                            <div class="item-controls">
                              <button type="button" class="btn">
                                <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"><span class="fas fa-pencil-alt"></span></a>
                              </button>
                              <button type="button" class="btn">
                                <a href="{{ URL::to('/deleteCart?id='.$products->customers_basket_id)}}"><span class="fas fa-times"></span></a>
                              </button>
                            </div>
                          </td>
                          <td class="col-12 col-md-10 d-none d-md-block"></td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    <?php
                    if (!empty(session('shipping_detail')) and !empty(session('shipping_detail')) > 0) {
                      $shipping_price = session('shipping_detail')->shipping_price;
                      $shipping_name = session('shipping_detail')->mehtod_name;
                    } else {
                      $shipping_price = 0;
                      $shipping_name = '';
                    }
                    $tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
                    $tax_rate = ($price * Config::get('global.p_constant'));
                    $coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');
                    $total_price = ($price + $tax_rate + $shipping_price) - $coupon_discount;

                    session(['total_price' => $total_price]);

                    ?>
                    <input type="hidden" name="method_payment" id="method_payment" value="">
                  </form>

                  <!-- The instamojo Modal -->
                  <div class="modal fade" id="instamojoModel">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form id="instamojo_form" method="post" action="">
                          <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                          <input type="hidden" name="amount" value="{{number_format((float)$total_price+0, 2, '.', '')}}">
                          <!-- Modal Header -->
                          <div class="modal-header">
                            <h4 class="modal-title">@lang('website.Instamojo Payment')</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <div class="from-group mb-3">
                              <div class="col-12"> <label for="inlineFormInputGroup">@lang('website.Full Name')</label></div>
                              <div class="input-group col-12">
                                <input type="text" name="firstName" id="firstName" placeholder="@lang('website.Full Name')" class="form-control">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your full name')</span>
                              </div>
                            </div>
                            <div class="from-group mb-3">
                              <div class="col-12"> <label for="inlineFormInputGroup">@lang('website.Email')</label></div>
                              <div class="input-group col-12">
                                <input type="text" name="email_id" id="email_id" placeholder="@lang('website.Email')" class="form-control">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your email address')</span>
                              </div>
                            </div>
                            <div class="from-group mb-3">
                              <div class="col-12"> <label for="inlineFormInputGroup">@lang('website.Phone Number')</label></div>
                              <div class="input-group col-12">
                                <input type="text" name="phone_number" id="insta_phone_number" placeholder="@lang('website.Phone Number')" class="form-control">
                                <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span>
                              </div>
                            </div>

                            <div class="alert alert-danger alert-dismissible" id="insta_mojo_error" role="alert" style="display: none">
                              <span class="sr-only">@lang('website.Error'):</span>
                              <span id="instamojo-error-text"></span>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" id="pay_instamojo" class="btn btn-dark">@lang('website.Pay') {{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')}}</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <!-- The stripe Modal -->
                  <div class="modal fade" id="stripeModel">
                    <div class="modal-dialog">
                      <div class="modal-content">

                        <main>
                          <div class="container-lg">
                            <div class="cell example example2">
                              <form>
                                <div class="row">
                                  <div class="field">
                                    <div id="example2-card-number" class="input empty"></div>
                                    <label for="example2-card-number" data-tid="elements_examples.form.card_number_label">@lang('website.Card number')</label>
                                    <div class="baseline"></div>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="field half-width">
                                    <div id="example2-card-expiry" class="input empty"></div>
                                    <label for="example2-card-expiry" data-tid="elements_examples.form.card_expiry_label">@lang('website.Expiration')</label>
                                    <div class="baseline"></div>
                                  </div>
                                  <div class="field half-width">
                                    <div id="example2-card-cvc" class="input empty"></div>
                                    <label for="example2-card-cvc" data-tid="elements_examples.form.card_cvc_label">@lang('website.CVC')</label>
                                    <div class="baseline"></div>
                                  </div>
                                </div>
                                <button type="submit" class="btn btn-dark" data-tid="elements_examples.form.pay_button">@lang('website.Pay') {{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')}}</button>

                                <div class="error" role="alert"><svg xmlns="https://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                    <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
                                    <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
                                  </svg>
                                  <span class="message"></span>
                                </div>
                              </form>
                              <div class="success">
                                <div class="icon">
                                  <svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                                    <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none"></circle>
                                    <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" d="M23.375 42.5488281 36.8840688 56.0578969 64.891932 28.0500338" stroke-width="4" stroke="#000" fill="none"></path>
                                  </svg>
                                </div>
                                <h3 class="title" data-tid="elements_examples.success.title">@lang('website.Payment successful')</h3>
                                <p class="message"><span data-tid="elements_examples.success.message">@lang('website.Thanks You Your payment has been processed successfully')</p>
                              </div>

                            </div>
                          </div>
                        </main>
                      </div>
                    </div>
                  </div>

                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      @php
      $default_currency = DB::table('currencies')->where('is_default',1)->first();
      if($default_currency->id == Session::get('currency_id')){
      $currency_value = 1;
      }else{
      $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();
      $currency_value = $session_currency->value;
      }
      @endphp
      <div class="col-12 col-xl-3 checkout-right">
        <table class="table right-table">
          <thead>
            <tr>
              <th scope="col" colspan="2" align="center">
                @if(session()->get('locale') == 'en')
                @lang('website.Order Summary')
                @else
                ملخص الطلب
                @endif</th>

            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">@if(session()->get('locale') == 'en')
                @lang('website.SubTotal')
                @else
                المجموع الفرعي
                @endif</th>
              <td align="right">{{Session::get('symbol_left')}} {{pd_adjust_price($price+0)}}{{Session::get('symbol_right')}}</td>
            </tr>
            <tr>
              <th scope="row">
                @if(session()->get('locale') == 'en')
                VAT ({{Config::get('global.p_constant') * 100}}%)
                @else
                ({{Config::get('global.p_constant') * 100}}%) قيمة ارتفاع الضرائب
                @endif
              </th>
              <td align="right">
                {{Session::get('symbol_left')}} {{pd_adjust_price($tax_rate)}}{{Session::get('symbol_right')}}
              </td>
            </tr>
            <tr>
              <th scope="row">@if(session()->get('locale') == 'en')
                @lang('website.Discount(Coupon)')
                @else
                الخصم (الكوبون)
                @endif</th>
              <td align="right">{{Session::get('symbol_left')}} {{number_format((float)session('coupon_discount'), 2, '.', '')+0*$currency_value}}{{Session::get('symbol_right')}}</td>

            </tr>
            <tr>
              <th scope="row">@if(session()->get('locale') == 'en') @lang('website.Shipping Cost') @else قيمة الشحن @endif</th>
              <td align="right">{{Session::get('symbol_left')}} <span class="sh_price">{{$shipping_price*$currency_value}}</span>{{Session::get('symbol_right')}}</td>

            </tr>
            <tr class="item-price">
              <th scope="row">
                @if(session()->get('locale') == 'en')
                @lang('website.Total')
                @else
                المجموع
                @endif</th>

              @php
              $totalCartPrice=pd_adjust_price((float)$total_price+0)+0*$currency_value;
              @endphp
              <td align="right">{{Session::get('symbol_left')}} <span class="t_price">{{$totalCartPrice}}</span>{{Session::get('symbol_right')}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  </div>
</section>

<script>
  jQuery(document).ready(function() {
    if (jQuery('.payment_method:checked').val() == 'pickup_from_store') {
      jQuery("#pickup_from_store_dropdown").show();
    } else {
      jQuery("#pickup_from_store_dropdown").hide();
    }
    if (jQuery('.payment_method:checked').val() == 'pay_with_credit_card') {
      // alert(jQuery('.payment_method:checked').val())
      jQuery("#pay_with_credit_card_button").show();
    }
    if (jQuery('.payment_method:checked').val() == 'pay_in_installments') {
      // alert(jQuery('.payment_method:checked').val())
      jQuery("#pay_in_installments_button").show();
    }
    
    jQuery(document).on('click', '#pay_with_credit_card_button', function(e) {
      jQuery("#full_payment_cart_form").submit();
    });
    jQuery(document).on('click', '#pay_in_installments_button', function(e) {
      jQuery("#installments_cart_form").submit();
    });

    jQuery(".payment_method").click(function() {
      if (jQuery('.payment_method:checked').val() == 'pay_in_installments') {
        var html = "<b>3 interest free payments of AED {{$totalCartPrice}} with postpay</b><p>Payment summary</p><ul id='progressbar'><li >AED {{number_format($totalCartPrice/3,2)}}<br>{{date('Y-M-d')}}</li><li>AED {{number_format($totalCartPrice/3,2)}}<br>{{date('Y-M-d',strtotime('+1 months'))}}</li><li>AED {{number_format($totalCartPrice/3,2)}}<br>{{date('Y-M-d',strtotime('+2 months'))}}</li></ul><ul><li>Buy now , pay in 3 monthly installments</li><li>No interest , No fees</li></ul>";
      jQuery("#msform").html(html);
      }else{
        jQuery("#msform").html("");
      }
      
    });

    jQuery(document).on('click', '#cash_on_delivery_button', function(e) {
      jQuery("#update_cart_form").submit();
    });
    jQuery(document).on('click', '#pickup_from_store_button', function(e) {
      jQuery("#update_cart_form").submit();
    });
    jQuery(document).on('click', '.payment_method', function(e) {
      var sh_price = $(".sh_price").text();
      var t_price = $(".t_price").text();
      $("#method_payment").val(jQuery(this).val());
      if (jQuery(this).val() == 'pickup_from_store') {
        $(".sh_price").text('0');
        $(".t_price").text(t_price - sh_price);
        jQuery("#pickup_from_store_dropdown").show();
      } else {
        $(".sh_price").text({{$shipping_price * $currency_value}});
        $(".t_price").text({{pd_adjust_price((float) $total_price + 0) + 0 * $currency_value}});
        jQuery("#pickup_from_store_dropdown").hide();
      }
    });
  });
</script>
@endsection