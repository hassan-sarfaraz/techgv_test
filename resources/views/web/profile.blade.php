@extends('web.layout')
@section('content')
<!-- Profile Content -->
<section class="profile-content">
   <div class="container">
     <div class="row">
         <div class="col-12 col-sm-12">
             <div class="row justify-content-end">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">
                        @if(session()->get('locale') == 'en') 
                          @lang('website.Home')
                        @else
                        الصفحة الرئيسية
                        @endif
                        </a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                          @if(session()->get('locale') == 'en') 
                            @lang('website.myProfile')
                          @else
                            ملفي الشخصي
                          @endif
                        </li>
                     </ol>
                   </nav>
             </div>
         </div>
         <div class="col-12 media-main">
             <div class="media">
                 <img src="{{auth()->guard('customer')->user()->avatar ?? asset('images/user.png')}}" alt="avatar">
                 <div class="media-body">
                   <div class="row">
                     <div class="col-12 col-sm-4 col-md-6">
                       <h4>{{auth()->guard('customer')->user()->first_name}} {{auth()->guard('customer')->user()->last_name}}<br>
                         <small>{{auth()->guard('customer')->user()->email}} </small></h4>
                     </div>
                     <div class="col-12 col-sm-8 col-md-6 detail">

                       <p>E-mail:<span><a href="#">{{auth()->guard('customer')->user()->email}}</a></span></p>
                     </div>
                     </div>
                 </div>

             </div>
         </div>
       <div class="col-12 col-lg-3">
           <div class="heading">
               <h2>
               @if(session()->get('locale') == 'en') 
               @lang('website.My Account')
                @else
                حسابي
                @endif  
               </h2>
               <hr >
             </div>

           <ul class="list-group">
               <li class="list-group-item">
                   <a class="nav-link" href="{{ URL::to('/profile')}}">
                       <i class="fas fa-user"></i>
                       @if(session()->get('locale') == 'en') 
                     @lang('website.Profile')
                     @else 
                     الملف الشخصي
                     @endif
                   </a>
               </li>
               <li class="list-group-item">
                   <a class="nav-link" href="{{ URL::to('/wishlist')}}">
                       <i class="fas fa-heart"></i>
                       @if(session()->get('locale') == 'en') 
                    @lang('website.Wishlist')
                    @else 
                    الأماني
                     @endif
                   </a>
               </li>
               <li class="list-group-item">
                   <a class="nav-link" href="{{ URL::to('/orders')}}">
                       <i class="fas fa-shopping-cart"></i>
                       @if(session()->get('locale') == 'en') 
                     @lang('website.Orders')
                     @else 
                     أوامر
                     @endif
                   </a>
               </li>
               <li class="list-group-item">
                   <a class="nav-link" href="{{ URL::to('/shipping-address')}}">
                       <i class="fas fa-map-marker-alt"></i>
                       @if(session()->get('locale') == 'en') 
                    @lang('website.Shipping Address')
                    @else 
                    عنوان الشحن
                     @endif
                   </a>
               </li>
               <li class="list-group-item">
                   <a class="nav-link" href="{{ URL::to('/logout')}}">
                       <i class="fas fa-power-off"></i>
                       @if(session()->get('locale') == 'en') 
                     @lang('website.Logout')
                     @else 
                     الخروج
                     @endif
                   </a>
               </li>
             </ul>
       </div>
       <div class="col-12 col-lg-9 ">
           <div class="heading">
               <h2>
                   
                   @if(session()->get('locale') == 'en') 
                   @lang('website.Personal Information')
                     @else 
                     المعلومات الشخصية
                     @endif
               </h2>
               <hr >
             </div>
             <form name="updateMyProfile" class="align-items-center" enctype="multipart/form-data" action="{{ URL::to('updateMyProfile')}}" method="post">
               @csrf
                @if( count($errors) > 0)
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <span class="sr-only">@lang('website.Error'):</span>
                            {{ $error }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endforeach
                @endif

                @if(session()->has('error'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session()->get('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">@lang('website.Error'):</span>
                        {{ session()->get('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">@lang('website.Error'):</span>
                        {!! session('loginError') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session()->has('success') )
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session()->get('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="form-group row justify-content-center">
                  <div class="col-12 media-main">
                      <div class="media">
                        @if(!empty(auth()->guard('customer')->user()->avatar))
                            <input type="hidden" name="customers_old_picture" value="{{ auth()->guard('customer')->user()->avatar }}">
                        @else
                          <input type="hidden" name="customers_old_picture" value="">
                        @endif
                          <img style="margin-bottom:-50px;" src="{{auth()->guard('customer')->user()->avatar ?? asset('images/user.png')}}" alt="avatar">
                          <div class="media-body"style="margin-left:70px; margin-bottom:-50px;">
                            <div class="row">
                              <div class="col-12 col-sm-4 col-md-6">
                                 <input name="picture" id="userImage" type="file" class="inputFile" onChange="showPreview(this);" /><br>
                              </div>
                            </div>
                          </div>

                      </div>
                  </div>
                </div>

                 <div class="form-group row">
                   <label for="firstName" class="col-sm-2 col-form-label">@if(session()->get('locale') == 'en')
                          @lang('website.First Name')
                        @else
                          الاسم الأول
                        @endif</label>
                   <div class="col-sm-10">
                     <input type="text" required name="customers_firstname" class="form-control" id="inputName" value="{{ auth()->guard('customer')->user()->first_name }}" placeholder="@if(session()->get('locale') == 'en') @lang('website.First Name') @else الاسم الأول @endif">
                   </div>
                 </div>
                 <div class="form-group row">
                   <label for="lastName" class="col-sm-2 col-form-label">@if(session()->get('locale') == 'en')
                          @lang('website.Last Name')
                        @else
                          الكنية
                        @endif</label>
                   <div class="col-sm-10">
                     <input type="text" required name="customers_lastname" placeholder="@if(session()->get('locale') == 'en')@lang('website.Last Name')@elseالكنية@endif" class="form-control field-validate" id="lastName" value="{{ auth()->guard('customer')->user()->last_name }}">
                   </div>
                 </div>
                 <div class="form-group row">
                   <label for="gender"  class="col-sm-2 col-form-label">
                   @if(session()->get('locale') == 'en')
                   @lang('website.Gender')
                        @else
                        الجنس
                        @endif</label>
                     <div class="col-5 col-sm-5">
                         <div class="select-control">
                             <select name="gender" required class="form-control" id="exampleSelectGender1" aria-describedby="genderHelp">
                               <option value="0" @if(auth()->guard('customer')->user()->gender == 0) selected @endif>@lang('website.Male')</option>
                               <option value="1"  @if(auth()->guard('customer')->user()->gender == 1) selected @endif>@lang('website.Female')</option>
                              </select>
                         </div>
                     </div>
                     <div class="col-7 col-sm-5">
                         <div class="input-group date">
                           <div class="input-group-prepend">
                               <span class="input-group-text"><i class="fas fa-phone"></i></span>
                             </div>
                             <input name="customers_telephone" type="tel"  placeholder="@if(session()->get('locale') == 'en')@lang('website.Phone Number') @else رقم الهاتف @endif" value="{{ auth()->guard('customer')->user()->phone }}" class="form-control">
                           </div>
                     </div>

                   </div>
                   <div class="form-group row">
                       <label for="inputPassword" class="col-sm-2 col-form-label">
                       @if(session()->get('locale') == 'en')
                       Date of Birth
                        @else
                        تاريخ الميلاد
                        @endif
                        </label>
                       <div class="col-7 col-sm-5">
                           <div class="input-group date">
                               <input readonly name="customers_dob" type="text" data-provide="datepicker" class="form-control" placeholder="@if(session()->get('locale') == 'en')@lang('website.Date of Birth') @elseتاريخ الميلاد@endif" value="{{ auth()->guard('customer')->user()->dob }}" aria-label="date-picker" aria-describedby="date-picker-addon1">

                               <div class="input-group-prepend">
                                   <span class="input-group-text" id="date-picker-addon1"><i class="fas fa-calendar-alt"></i></span>
                                 </div>
                             </div>

                       </div>
                     </div>

                   <button type="submit" class="btn btn-primary">
                     @if(session()->get('locale') == 'en')
                      @lang('website.Update')
                    @else
                    تحديث                     
                    @endif</button>
             </form>

         <!-- ............the end..... -->
       </div>
       <div style="margin-top:20px;"class="col-12 col-lg-9 offset-3 ">
           <div class="heading">
               <h2>
                  @if(session()->get('locale') == 'en')
                   @lang('website.Change Password')
                   @else
                   تغيير كلمة المرور
                   @endif
               </h2>
               <hr >
             </div>
             <form name="updateMyPassword" class="" enctype="multipart/form-data" action="{{ URL::to('/updateMyPassword')}}" method="post">
                 @csrf
                 <div class="form-group row">
                     <label for="new_password" class="col-sm-4 col-form-label">
                      @if(session()->get('locale') == 'en')
                      @lang('website.New Password')
                      @else
                      كلمة المرور الجديدة
                      @endif</label>
                     <div class="col-sm-8">
                         <input name="new_password" type="password" class="form-control" id="new_password" placeholder="@if(session()->get('locale') == 'en')@lang('website.New Password')@else  كلمة المرور الجديدة @endif">
                         <span class="help-block error-content" hidden>@lang('website.Please enter your password and should be at least 6 characters long')</span>
                     </div>
                 </div>
                 <div class="button">
                     <button type="submit" class="btn btn-dark">@if(session()->get('locale') == 'en')
                      @lang('website.Update')
                    @else
                    تحديث                     
                    @endif</button>
                 </div>
             </form>

         <!-- ............the end..... -->
       </div>
     </div>
   </div>
 </section>
 @endsection
