<!-- //banner one -->
@if(count($result['commonContent']['homeBanners'])>0)
    <div class="banner-one">
        <div class="container bg-white p-4 mb-4">
            <div class="group-banners">
                <div class="heading">
                    <h2 class="font-weight-bolder">
                                Latest Promotions <small class="pull-right"><a class="btn btn-sm btn-success" href="/page?name=promotions">View All</a></small>
                    </h2>
                    <hr>
                </div>
                <div class="row">
                    @foreach(($result['commonContent']['homeBanners']) as $homeBanners)
                        @if($homeBanners->type==3)
                            <div class="col-12 col-md-4">
                                <figure class="banner-image ">
                                    <a href="{{ $homeBanners->banners_url}}">
                                        <img class="img-fluid" src="{{asset('').$homeBanners->path}}" alt="Banner Image">
                                    </a>
                                </figure>
                            </div>
                        @endif
                        @if($homeBanners->type==4)
                            <div class="col-12 col-md-4">
                                <figure class="banner-image ">
                                    <a href="{{ $homeBanners->banners_url}}">
                                        <img class="img-fluid" src="{{asset('').$homeBanners->path}}" alt="Banner Image">
                                    </a>
                                </figure>
                            </div>
                        @endif
                        @if($homeBanners->type==5)
                            <div class="col-12 col-md-4">
                                <figure class="banner-image ">
                                    <a href="{{ $homeBanners->banners_url}}">
                                        <img class="img-fluid" src="{{asset('').$homeBanners->path}}" alt="Banner Image">
                                    </a>
                                </figure>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
