<!-- //banner fourteen -->
<div class="banner-fourteen">

    <div class="container">
      <div class="group-banners">
          <div class="row">
              <div class="col-8 col-sm-8">
                <div class="row">
                  <div class="col-12 col-md-6">
                    @if(count($result['commonContent']['homeBanners'])>0)
                     @foreach(($result['commonContent']['homeBanners']) as $homeBanners)
                        @if($homeBanners->type==26)
                    <figure class="banner-image ">
                      <a href="#"><img class="img-fluid" src="{{asset('').$homeBanners->path}}" alt="Banner Image"></a>
                    </figure>
                    @endif
                   @endforeach
                  @endif
                  @if(count($result['commonContent']['homeBanners'])>0)
                   @foreach(($result['commonContent']['homeBanners']) as $homeBanners)
                      @if($homeBanners->type==28)
                    <figure class="banner-image ">
                      <a href="#"><img class="img-fluid" src="{{asset('').$homeBanners->path}}" alt="Banner Image"></a>
                    </figure>
                    @endif
                   @endforeach
                  @endif
                  </div>
                  <div class="col-12 col-md-6">
                    @if(count($result['commonContent']['homeBanners'])>0)
                     @foreach(($result['commonContent']['homeBanners']) as $homeBanners)
                        @if($homeBanners->type==27)
                    <figure class="banner-image ">
                      <a href="#"><img class="img-fluid" src="{{asset('').$homeBanners->path}}" alt="Banner Image"></a>
                    </figure>
                    @endif
                   @endforeach
                  @endif
                  @if(count($result['commonContent']['homeBanners'])>0)
                   @foreach(($result['commonContent']['homeBanners']) as $homeBanners)
                      @if($homeBanners->type==29)
                    <figure class="banner-image ">
                      <a href="#"><img class="img-fluid" src="{{asset('').$homeBanners->path}}" alt="Banner Image"></a>
                    </figure>
                    @endif
                   @endforeach
                  @endif
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-4">
                @if(count($result['commonContent']['homeBanners'])>0)
                 @foreach(($result['commonContent']['homeBanners']) as $homeBanners)
                    @if($homeBanners->type==25)
                  <figure class="banner-image banner-column-5">
                    <a href="#"><img class="img-fluid" src="{{asset('').$homeBanners->path}}" alt="Banner Image"></a>
                  </figure>
                  @endif
                 @endforeach
                @endif
              </div>
            </div>
      </div>
    </div>

</div>
