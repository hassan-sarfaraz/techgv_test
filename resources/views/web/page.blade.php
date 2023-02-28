@extends('web.layout')
@section('content')

<section class="aboutus-content aboutus-content-one">
  <div class="container">
    <div class="heading">
      <h2>{{$result['pages'][0]->name}}</h2>
      <hr style="margin-bottom: 10;">
    </div>
    {!! stripslashes($result['pages'][0]->description) !!}

    @if($result['pages'][0]->slug == "promotions")
    @php
    $promoBanners = DB::table('constant_banners')
    ->leftJoin('image_categories','constant_banners.banners_image','=','image_categories.image_id')
    ->select('constant_banners.*','image_categories.path')
    ->where('languages_id', 1)
    ->where('type', 100)
    ->groupBy('constant_banners.banners_id')
    ->orderby('type','ASC')
    ->get();
    @endphp
    <div class="banner-one">
      <div class="container bg-white p-4 mb-4">
        <div class="group-banners">
          <div class="row">
            @if(count($promoBanners) > 0)
            @foreach($promoBanners as $homeBanners)
            <div class="col-12 col-md-4 mb-4">
              <figure class="banner-image ">
                <a href="{{ $homeBanners->banners_url}}">
                  <img class="img-fluid" src="{{asset('').$homeBanners->path}}" alt="Banner Image">
                </a>
              </figure>
            </div>
            @endforeach
            @else
            <h5>Sorry! No promotion banner available.</h5>
            @endif
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>

</section>

@endsection