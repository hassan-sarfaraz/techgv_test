@if($result['flash_sale']['success']==1)
<section class="products-content">
  <div class="container bg-white p-4 mb-4">
    @if($result['flash_sale']['success']==1)
    <div class="products-area">
      <!-- ..........tabs start ......... -->
      <div class="row">
        <div class="col-md-12">
          <div class="nav nav-pills" role="tablist">
            @if($result['top_seller']['success']==1)
            <a class="nav-link nav-item nav-index active show" href="#featured" id="featured-tab" data-toggle="pill" role="tab">@lang('website.TopSales')</a>
            @endif
            @if($result['special']['success']==1)
            <a class="nav-link nav-item nav-index" href="#special" id="special-tab" data-toggle="pill" role="tab" >@lang('website.Special')</a>
            @endif
            @if($result['most_liked']['success']==1)
            <a class="nav-link nav-item nav-index" href="#liked" id="liked-tab" data-toggle="pill" role="tab" >@lang('website.MostLiked')</a>
            @endif
          </div>
          <!-- Tab panes -->
          <div class="tab-content">
            @if($result['top_seller']['success']==1)
            <div role="tabpanel" class="tab-pane fade active show" id="featured" aria-labelledby="featured-tab">
              <div id="owl-tab" class="owl-tab owl-carousel">
                @foreach($result['top_seller']['product_data'] as $key=>$products)
                @include('web.common.product')
                @endforeach

                <div class="product last-product">
                  <article>
                    <div class="icons">
                      <a href="{{url('/shop')}}">
                        <i class="fas fa-angle-right icon"></i>
                      </a>
                      <a href="{{url('/shop')}}" class="btn btn-block btn-link">@lang('website.View')</a>
                    </div>
                  </article>
                </div>

              </div>
              <!-- 1st tab -->
            </div>
            @endif
            @if($result['special']['success']==1)
            <div role="tabpanel" class="tab-pane fade" id="special" aria-labelledby="special-tab">
                <div id="owl-tab" class="owl-tab owl-carousel">
                  @foreach($result['special']['product_data'] as $key=>$products)
                  @include('web.common.product')
                  @endforeach

                    <div class="product last-product">
                      <article>
                        <div class="icons">
                            <a href="{{url('/shop')}}">
                                <i class="fas fa-angle-right icon"></i>
                              </a>
                              <a href="{{url('/shop')}}" class="btn btn-block btn-link">@lang('website.View')</a>
                        </div>
                      </article>
                    </div>

                  </div>
              <!-- 2nd tab -->
            </div>
            @endif
            @if($result['most_liked']['success']==1)
            <div role="tabpanel" class="tab-pane fade" id="liked" aria-labelledby="liked-tab">
                <div id="owl-tab" class="owl-tab owl-carousel">
                    @foreach($result['most_liked']['product_data'] as $key=>$products)
                    @include('web.common.product')
                    @endforeach

                    <div class="product last-product">
                      <article>
                        <div class="icons">
                            <a href="{{url('/shop')}}">
                                <i class="fas fa-angle-right icon"></i>
                              </a>
                              <a href="{{url('/shop')}}" class="btn btn-block btn-link">@lang('website.View')</a>
                        </div>
                      </article>
                    </div>

                  </div>
              <!-- 3rd tab -->
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
</section>
@endif
