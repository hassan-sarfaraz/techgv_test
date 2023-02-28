<!-- //footer style Four -->
 <footer id="footerFour"  class="footer-area footer-four footer-desktop d-none d-lg-block d-xl-block">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-12 col-lg-3">
            <a class="logo" href="{{url('/')}}">
            <strong>E</strong>KOMMERCE
          </a>
            <p>
              {{$result['commonContent']['setting'][111]->value}}
            </p>
            <ul class="contact-list  pl-0 mb-0">
              <li> <i class="fas fa-map-marker"></i><span>{{$result['commonContent']['setting'][4]->value}} {{$result['commonContent']['setting'][5]->value}} {{$result['commonContent']['setting'][6]->value}}, {{$result['commonContent']['setting'][7]->value}} {{$result['commonContent']['setting'][8]->value}}</span> </li>
              <li> <i class="fas fa-phone"></i><span>({{$result['commonContent']['setting'][11]->value}})</span> </li>
              <li> <i class="fas fa-envelope"></i><span> <a href="mailto:{{$result['commonContent']['setting'][3]->value}}">{{$result['commonContent']['setting'][3]->value}}</a> </span> </li>

            </ul>

        </div>
        <div class="col-12 col-lg-4">
          <h5>
             @lang('website.Contact Us')
          </h5>
          <div class="form">
            <form enctype="multipart/form-data" action="{{ URL::to('/processContactUs')}}" method="post">
              <input name="_token" value="{{ csrf_token() }}" type="hidden">

              <label class="first-label" for="email">@lang('website.Full Name')</label>
              <div class="input-group">
                  <input type="text" class="form-control" id="name" name="name" placeholder="@lang('website.Please enter your name')" aria-describedby="inputGroupPrepend" required>
                  <span class="help-block error-content" hidden>@lang('website.Please enter your name')</span>
              </div>
              <label for="email">@lang('website.Email')</label>
              <div class="input-group">
                  <input type="email"  name="email" class="form-control" id="validationCustomUsername" placeholder="Enter Email here.." aria-describedby="inputGroupPrepend" required>
                  <span class="help-block error-content" hidden>@lang('website.Please enter your valid email address')</span>

              </div>
              <label for="email">@lang('website.Message')</label>
              <textarea type="text" name="message"  placeholder="write your message here..." rows="5" cols="56"></textarea>
              <span class="help-block error-content" hidden>@lang('website.Please enter your message')</span>
              <button type="submit" class="btn btn-secondary">@lang('website.Send') <i class="fas fa-location-arrow"></i></button>
            </form>
          </div>
        </div>
        <div class="col-12 col-lg-3">
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
                <h5>@lang('website.Follow Us')</h5>
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
                        © @lang('website.Copy Rights').  <a href="{{url('/page?name=refund-policy')}}">@lang('website.Privacy')</a>&nbsp;&bull;&nbsp;<a href="{{url('/page?name=term-services')}}">@lang('website.Terms')</a>
                    </div>

                  </div>
              </div>
            </div>
          </div>
    </div>
</footer>
