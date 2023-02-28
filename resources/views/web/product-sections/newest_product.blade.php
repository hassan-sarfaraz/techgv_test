<!-- Products content -->
@if($result['products']['success']==1)
<section class="products-content">
  <div class="container bg-white p-4 mb-4">
    <div class="products-area">
      <!-- heading -->
      <div class="heading">
        <h2 class="font-weight-bolder">
          @if(session()->get('locale') == 'en')
          @lang('website.Newest Products')
          @else
          أحدث المنتجات
          @endif
          <small class="pull-right">
            <a class="btn btn-sm btn-success" href="{{url('/shop')}}">@lang('website.View All')</a>
          </small>
        </h2>
        <hr>
      </div>
      <div class="row">
        @if($result['products']['success']==1)
        @foreach($result['products']['product_data'] as $key=>$products)

        <div class="col-12 col-sm-12 col-md-6 col-lg-3">

          <!-- Product -->
          @include('web.common.product')
        </div>
        @endforeach
        @endif
      </div>
    </div>
  </div>
</section>
@endif