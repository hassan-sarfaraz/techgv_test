<!-- Bootstrap Carousel Content Full Screen -->
<section class="carousel-content">
    <div class="container-fuild">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                @php $is_first = 1; @endphp
                @foreach($result['slides'] as $key => $slides_data)
                    @if(isset($slides_data->path) and !empty($slides_data->path))
                        <li data-target="#myCarousel" data-slide-to="{{ $key }}" class="@if($is_first == 1) active @endif"></li>
                        @php $is_first = 0; @endphp
                    @endif
                @endforeach
            </ol>
            <div class="carousel-inner">
                @php $is_first = 1; @endphp
                @foreach($result['slides'] as $key=>$slides_data)
                    @if(isset($slides_data->path) and !empty($slides_data->path))
                        <div class="carousel-item  @if($is_first == 1) active @endif">
                            {{--
                              @if($slides_data->type == 'category')
                                <a href="{{ URL::to('/shop?category='.$slides_data->url)}}">
                              @elseif($slides_data->type == 'product')
                                <a href="{{ URL::to('/product-detail/'.$slides_data->url)}}">
                              @elseif($slides_data->type == 'mostliked')
                                <a href="{{ URL::to('shop?type=mostliked')}}">
                              @elseif($slides_data->type == 'topseller')
                                <a href="{{ URL::to('shop?type=topseller')}}">
                              @elseif($slides_data->type == 'deals')
                                <a href="{{ URL::to('shop?type=deals')}}">
                              @endif
                            --}}
                            <img width="100%" class="first-slide" src="{{asset($slides_data->path)}}" width="100%" alt="">
                        </div>
                        @php $is_first = 0; @endphp
                    @endif
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                <span class="fa fa-angle-left" aria-hidden="true"></span>
                <span class="sr-only">@lang('website.Previous')</span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                <span class="fa fa-angle-right" aria-hidden="true"></span>
                <span class="sr-only">@lang('website.Next')</span>
            </a>
        </div>
    </div>
</section>
