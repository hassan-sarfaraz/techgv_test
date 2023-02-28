@extends('web.layout')
@section('content')
<!-- Site Content -->
<section class="blog-content">
<div class="container">

 <div class="blog-area">

   <div class="row">

     <div class="col-12 col-lg-8">
         <div class="row">
           @foreach($result['commonContent']['featuredNews']['news_data'] as $key=>$news_data)
             <div class="col-12 col-sm-12">
                       <div class="blog">
                           <img class="img-thumbnail" src="{{asset('').$result['news'][0]->image}}" width="100%">



                         <h6 class="blog-title">{{$news_data->news_name}}<br>
                           </h6>
                             <p>
                                 <?php print_r($news_data->news_description) ?>
                             </p>
                             <div class="col-12 blog-control">


                                       <div class="row justify-content-between align-items-center">
                                           <div class="col-6 col-sm-6">
                                               {{$news_data->created_at}}
                                           </div>
                                           <div class="col-auto">

                                                   @if(!empty($result['commonContent']['setting'][50]->value))
                                                     <button class="btn btn-default"><a href="{{$result['commonContent']['setting'][50]->value}}" class="fab fa-facebook-f" target="_blank"></a></button>
                                                     @else
                                                       <button class="btn btn-default"><a href="#" class="fab fa-facebook-f"></a></button>
                                                     @endif

                                                 @if(!empty($result['commonContent']['setting'][52]->value))
                                                     <button class="btn btn-default"><a href="{{$result['commonContent']['setting'][52]->value}}" class="fab fa-twitter" target="_blank"></a></button>
                                                 @else
                                                     <button class="btn btn-default"><a href="#" class="fab fa-twitter"></a></button>
                                                 @endif
                                                 @if(!empty($result['commonContent']['setting'][51]->value))
                                                     <button class="btn btn-default"><a href="{{$result['commonContent']['setting'][51]->value}}"  target="_blank"><i class="fab fa-google"></i></a></button>
                                                 @else
                                                     <button class="btn btn-default"><a href="#"><i class="fab fa-google"></i></a></button>
                                                 @endif
                                                 @if(!empty($result['commonContent']['setting'][53]->value))
                                                     <button class="btn btn-default"><a href="{{$result['commonContent']['setting'][53]->value}}" class="fab fa-linkedin-in" target="_blank"></a></button>
                                                 @else
                                                     <button class="btn btn-default"><a href="#" class="fab fa-linkedin-in"></a></button>
                                                 @endif
                                                         </div>
                                                       </div>


                                           </div>
                                     </div>
             </div>
             @endforeach

         </div>
    </div>


                     <div class="col-12 col-lg-4  d-lg-block d-xl-block blog-menu">
                       <div class="category-div">
                           <div class="heading">
                               <h2>
                                @lang('website.Featured News')
                               </h2>
                               <hr style="margin-bottom: 0;">
                             </div>
                             @foreach($result['commonContent']['featuredNews']['news_data'] as $key=>$news_data)
                             <div class="media">
                                 <img src="{{asset('').$news_data->image_path}}" alt="John Doe" class=" mt-1" style="width:68px;height:68px;">
                                 <div class="media-body">
                                   <a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}">
                                    <h4>{{$news_data->news_name}}</h4>
                                   </a>
                                   <p>{{$news_data->news_description}}</p>
                                 </div>
                               </div>
                               	@endforeach
                       </div>
                       <div class="category-div">
                           <div class="heading">
                               <h2>
                                STAY CONNECTED
                               </h2>
                               <hr style="margin-bottom: 0;">
                             </div>
                             <ul>
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
               </div>
             </div>
</section>

@endsection
