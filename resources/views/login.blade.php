@extends('web.layout')
@section('content')
<!-- login Content -->
<section class="page-area">
	<div class="container">


			<div class="row">
					<div class="col-12 col-sm-12">
							<div class="row justify-content-end">
									<nav aria-label="breadcrumb">
											<ol class="breadcrumb">
												<li class="breadcrumb-item"><a href="{{ URL::to('/')}}">
													@if(session()->get('locale') == 'en')
														@lang('website.Home')
													@else
														الصفحة الرئيسية
													@endif
												</a></li>
												<li class="breadcrumb-item active" aria-current="page">
													@if(session()->get('locale') == 'en')
													@lang('website.Login')
													@else
														تسجيل الدخول
													@endif

												</li>
											</ol>
										</nav>
							</div>
					</div>
				<div class="col-12 col-sm-12 col-md-6">
					@if(Session::has('loginError'))
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
									<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
									<span class="sr-only">@lang('website.Error'):</span>
									{!! session('loginError') !!}

									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
							</div>
					@endif
					@if(Session::has('success'))
							<div class="alert alert-success alert-dismissible fade show" role="alert">
									<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
									<span class="sr-only">@lang('website.success'):</span>
									{!! session('success') !!}

									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
							</div>
					@endif
						<div class="col-12"><h4 class="heading login-heading">
						@if(session()->get('locale') == 'en')
							@lang('website.Login')
						@else
						تسجيل الدخول
						@endif

					</h4></div>
					<div class="registration-process">

						<form  enctype="multipart/form-data"   action="{{ URL::to('/process-login')}}" method="post">
							{{csrf_field()}}
								<div class="from-group mb-3">
									<div class="col-12"> <label for="inlineFormInputGroup">
									@if(session()->get('locale') == 'en')
										@lang('website.Email')
									@else
									بريد الالكتروني
									@endif
									</label></div>
									<div class="input-group col-12">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fas fa-at"></i></div>
										</div>
										<input type="email" name="email" id="email" placeholder="@if(session()->get('locale') == 'en')@lang('website.Email')@elseبريد الالكتروني@endif"class="form-control email-validate">
										<span class="help-block" hidden>@lang('website.Please enter your valid email address')</span>
								 </div>
								</div>
								<div class="from-group mb-3">
										<div class="col-12"> <label for="inlineFormInputGroup">
											@if(session()->get('locale') == 'en')
											@lang('website.Password')
											@else
											كلمه السر
										    @endif
										</label></div>
										<div class="input-group col-12">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fas fa-lock"></i></div>
											</div>
											<input type="password" name="password" id="password" placeholder="@if(session()->get('locale') == 'en') @lang('website.Password')@elseكلمه السر@endif" class="form-control field-validate">
											<span class="help-block" hidden>@lang('website.This field is required')</span>										</div>
									</div>
									<div class="col-12 col-sm-12">
											<button type="submit" class="btn btn-secondary">
												@if(session()->get('locale') == 'en')
													@lang('website.Login')
												@else
												تسجيل الدخول
												@endif
											</button>
										<a href="{{ URL::to('/forgotPassword')}}" class="btn btn-link">
										@if(session()->get('locale') == 'en')
											@lang('website.Forgot Password')
										@else
										هل نسيت كلمة السر
										@endif
									</a>
									</div>
						</form>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-6">
						<div class="col-12"><h4 class="heading login-heading">

							@if(session()->get('locale') == 'en')
                              @lang('website.NEW CUSTOMER')
                            @else
                            عميل جديد
                            @endif
						</h4></div>
						<div class="registration-process">
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

							@if(Session::has('error'))
								<div class="alert alert-danger alert-dismissible fade show" role="alert">
										<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
										<span class="sr-only">@lang('website.Error'):</span>
										{!! session('error') !!}

																<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																	</button>
								</div>
							@endif

							@if(Session::has('success'))
								<div class="alert alert-success alert-dismissible fade show" role="alert">
										<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
										<span class="sr-only">@lang('website.Success'):</span>
										{!! session('success') !!}

																<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
																</button>
								</div>
							@endif

							<form name="signup" enctype="multipart/form-data"  action="{{ URL::to('/signupProcess')}}" method="post">
								{{csrf_field()}}
								<div class="from-group mb-3">
									<div class="col-12"> <label for="inlineFormInputGroup"><strong style="color: red;">*</strong>
									@if(session()->get('locale') == 'en')
										@lang('website.First Name')
									@else
									الاسم الأول
									@endif
									</label></div>
									<div class="input-group col-12">
										<div class="input-group-prepend">
												<div class="input-group-text"><i class="fas fa-signature"></i></div>
										</div>
										<input  name="firstName" type="text" class="form-control field-validate" id="firstName" placeholder="@if(session()->get('locale') == 'en')@lang('website.First Name')@elseالاسم الأول@endif" value="{{ old('firstName') }}">
										<span class="help-block" hidden>@lang('website.Please enter your first name')</span>
									</div>
								</div>
								<div class="from-group mb-3">
									<div class="col-12"> <label for="inlineFormInputGroup"><strong style="color: red;">*</strong>
									@if(session()->get('locale') == 'en')
										@lang('website.Last Name')
									@else
									الكنية
									@endif

									</label></div>
									<div class="input-group col-12">
										<div class="input-group-prepend">
												<div class="input-group-text"><i class="fas fa-signature"></i></div>
										</div>
										<input  name="lastName" type="text" class="form-control field-validate field-validate" id="lastName" placeholder="@if(session()->get('locale') == 'en')@lang('website.Last Name')@elseالكنية@endif" value="{{ old('lastName') }}">
										<span class="help-block" hidden>@lang('website.Please enter your last name')</span>
									</div>
								</div>
									<div class="from-group mb-3">
										<div class="col-12"> <label for="inlineFormInputGroup"><strong style="color: red;">*</strong>
										@if(session()->get('locale') == 'en')
											@lang('website.Email Adrress')
										@else
										عنوان البريد الإلكتروني
										@endif

									</label></div>
										<div class="input-group col-12">
											<div class="input-group-prepend">
													<div class="input-group-text"><i class="fas fa-at"></i></div>
											</div>
											<input  name="email" type="text" class="form-control" id="inlineFormInputGroup" placeholder="@if(session()->get('locale') == 'en')@lang('website.Email Adrress')@elseعنوان البريد الإلكتروني@endif" value="{{ old('email') }}">
											<span class="help-block" hidden>@lang('website.Please enter your valid email address')</span>
										</div>
									</div>
									<div class="from-group mb-3">
											<div class="col-12"> <label for="inlineFormInputGroup"><strong style="color: red;">*</strong>
											@if(session()->get('locale') == 'en')
											@lang('website.Password')
											@else
											كلمه السر
										    @endif
										   </label></div>
											<div class="input-group col-12">
												<div class="input-group-prepend">
														<div class="input-group-text"><i class="fas fa-lock"></i></div>
												</div>
												<input name="password" id="password" type="password" class="form-control"  placeholder="@if(session()->get('locale') == 'en')@lang('website.Password') @elseكلمه السر@endif">
												<span class="help-block" hidden>@lang('website.Please enter your password')</span>

											</div>
										</div>
										<div class="from-group mb-3">
												<div class="col-12"> <label for="inlineFormInputGroup"><strong style="color: red;">*</strong>@if(session()->get('locale') == 'en') @lang('website.Confirm Password')@elseتأكيد كلمة المرور@endif
											</label></div>
												<div class="input-group col-12">
													<div class="input-group-prepend">
															<div class="input-group-text"><i class="fas fa-lock"></i></div>
													</div>
													<input type="password" class="form-control" id="re_password" name="re_password" placeholder="@if(session()->get('locale') == 'en') @lang('website.Confirm Password')@elseتأكيد كلمة المرور@endif">
													<span class="help-block" hidden>@lang('website.Please re-enter your password')</span>
													<span class="help-block" hidden>@lang('website.Password does not match the confirm password')</span>
												</div>
											</div>
											<div class="from-group mb-3">
												<div class="col-12" > <label for="inlineFormInputGroup"><strong  style="color: red;">*</strong>
												@if(session()->get('locale') == 'en')
												@lang('website.Gender')
												@else
												جنس تذكير أو تأنيث
												@endif

											</label></div>
												<div class="input-group col-12">
													<div class="input-group-prepend">
															<div class="input-group-text"><i class="fas fa-signature"></i></div>
													</div>
													<select class="form-control field-validate" name="gender" style="border: 1px solid #ced4da !important;" id="inlineFormCustomSelect">
														<option selected value="">
															@if(session()->get('locale') == 'en')
															@lang('website.Choose...')
																@else
																يختار...
																@endif
														</option>
														<option value="0" @if(!empty(old('gender')) and old('gender')==0) selected @endif>@lang('website.Male')</option>
														<option value="1" @if(!empty(old('gender')) and old('gender')==1) selected @endif>@lang('website.Female')</option>
													</select>
													<span class="help-block" hidden>@lang('website.Please select your gender')</span>
												</div>
											</div>
											<div class="from-group mb-3">
													<div class="input-group col-12">
														<input required style="margin:4px;"class="form-controlt checkbox-validate" type="checkbox">
														@if(session()->get('locale') == 'en')
															@lang('website.Creating an account means you are okay with our')
														@else
														إنشاء حساب يعني أنك بخير مع
														@endif  @if(!empty($result['commonContent']['pages'][3]->slug))&nbsp;<a href="{{ URL::to('/page?name='.$result['commonContent']['pages'][3]->slug)}}">@endif
														@if(session()->get('locale') == 'en')
															@lang('website.Terms and Services')
														@else
														الشروط والخدمات
														@endif
														@if(!empty($result['commonContent']['pages'][3]->slug))
													    </a>@endif, @if(!empty($result['commonContent']['pages'][1]->slug))<a href="{{ URL::to('/page?name='.$result['commonContent']['pages'][1]->slug)}}">@endif
														@if(session()->get('locale') == 'en')
															@lang('website.Privacy Policy')
														@else
														سياسة خاصة
														@endif

														@if(!empty($result['commonContent']['pages'][1]->slug))</a> @endif &nbsp;
														@if(session()->get('locale') == 'en') and @else و @endif &nbsp; @if(!empty($result['commonContent']['pages'][2]->slug))<a href="{{ URL::to('/page?name='.$result['commonContent']['pages'][2]->slug)}}">@endif
														@if(session()->get('locale') == 'en')
														@lang('website.Refund Policy')
														@else
														سياسة الاسترجاع
														@endif
														 @if(!empty($result['commonContent']['pages'][3]->slug))</a>@endif.
														<span class="help-block" hidden>@lang('website.Please accept our terms and conditions')</span>
													</div>
												</div>
										<div class="col-12 col-sm-12">
												<button type="submit" class="btn btn-primary">
													@if(session()->get('locale') == 'en')
														Create an Account
													@else
													انشئ حساب
													@endif
												</button>

										</div>
							</form>
						</div>
				</div>
				<div class="col-12 col-sm-12 my-5">
						<div class="registration-socials">
					<div class="row align-items-center justify-content-between">
									<div class="col-12 col-sm-6">
										@if(session()->get('locale') == 'en')
											Access Your Account Through Your Social Networks
										@else
											الوصول إلى حسابك من خلال شبكات التواصل الاجتماعي الخاصة بك
										@endif
									</div>
									<div class="col-12 col-sm-6 right">
										  @if($result['commonContent']['setting'][61]->value==1)
											<a href="login/google" type="button" class="btn btn-google"><i class="fab fa-google-plus-g"></i>&nbsp;Google</a>
											@endif
											@if($result['commonContent']['setting'][2]->value==1)
											<a  href="login/facebook" type="button" class="btn btn-facebook"><i class="fab fa-facebook-f"></i>&nbsp;Facebook</a>
											@endif
									</div>
							</div>
					</div>
				</div>
			</div>

	</div>
</section>


@endsection
