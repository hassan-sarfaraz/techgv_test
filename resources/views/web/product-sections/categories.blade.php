<!-- Products content -->
@if(!empty($result['commonContent']['categories']))
<section class="products-content">
  <div class="container bg-white p-4 mt-4 mb-4">
    <div class="products-area category-area">
      <!-- heading -->
      <div class="heading">
        @if(session()->get('locale') == 'en')
            <h2 class="font-weight-bolder">@lang('website.Categories')</h2>
        @else
            <h2 class="font-weight-bolder>فئات</h2>
        @endif
        <hr>
      </div>
      <div class="row">
        <!-- categories -->
        <?php $counter = 0;?>
        @foreach($result['commonContent']['categories'] as $categories_data)
                @if($counter<=7)
                <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                  <!-- categories -->
                  <div class="product">
                      <article>
                        <div class="module">
                          <div class="cat-thumb">
                              <a href="{{ URL::to('/shop?category='.$categories_data->slug)}}">
                                <img class="img-fluid" src="{{asset('').$categories_data->path}}" alt="{{$categories_data->name}}">
                              </a>
                          </div>
                          <a href="{{ URL::to('/shop?category='.$categories_data->slug)}}" class="cat-title">
                            {{$categories_data->name}}
                          </a>
                        </div>
                      </article>
                  </div>
                </div>
                @endif
                <?php $counter++;?>
        @endforeach

      </div>
    </div>


  </div>
</section>
<section class="products-content">
  <div class="container bg-white p-4 mb-4">
    <div class="products-area category-area">
      <!-- heading -->
      <div class="heading">
        <h2 class="font-weight-bolder">Brands</h2>
        <hr>
      </div>
      <div class="row">
        <!-- categories -->
        <?php $count = 0;?>
        @foreach($result['commonContent']['brands'] as $brands_data)
          @if($count<=16)
            <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3">
              <!-- categories -->
              <div class="product">
                <article>
                  <div class="module">
                    <a href="{{ URL::to('/shop?category='.$categories_data->slug)}}">
                    <div class="cat-thumb">
                      <img class="img-fluid" src="{{asset('').$brands_data->path}}" alt="{{$brands_data->name}}">
                    </div>
                      {{$brands_data->name}}
                    </a>
                  </div>
                </article>
              </div>
            </div>
          @endif
          <?php $count++;?>
        @endforeach

      </div>
    </div>
  </div>
</section>
@endif
