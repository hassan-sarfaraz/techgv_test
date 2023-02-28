{{--
  @if($result['news']['success']==1)
      <!-- Blog content -->
      <section class="blog-content">

        <div class="container bg-white p-4">
          <div class="">
              <!-- heading -->
              <div class="heading">
                  <h2 class="font-weight-bolder">
                      @if(session()->get('locale') == 'en')
                          @lang('website.From our News')
                      @else
                          من أخبارنا
                      @endif
                      <small class="pull-right">
                          <a class="btn btn-sm btn-success" href="{{ URL::to('/news')}}">@lang('website.View All')</a>
                      </small>
                  </h2>
                  <hr>
              </div>
              <div class="row">
                  @if($result['news']['success']==1)
                      @foreach($result['news']['news_data'] as $key=>$news_data)
                          <div class="col-12 col-sm-6 col-lg-4 blog-col">
                              <div class="blog">
                                  <h2><a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}">{{$news_data->news_name}}</a>
                                      <span class="featured-tag">@lang('website.Featured')</span>
                                  </h2>
                                  <div class="blog-date">
                                      <?php
                                      $timestamp = strtotime($news_data->news_date_added);
                                      echo date('d',$timestamp);
                                      ?>
                                      <br>
                                      <?php
                                      echo date('M',$timestamp);
                                      ?>
                                  </div>
                                  <div class="overlay">
                                      <a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}" class="fas fa-search-plus"></a>
                                  </div>
                                  <img class="img-fluid" src="{{asset('').$news_data->image_path}}">

                              </div>
                          </div>
                      @endforeach
                  @endif

              </div>
          </div>
        </div>
      </section>
  @endif
--}}
