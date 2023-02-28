<section class="blog-content">
  <div class="container">

   <div class="blog-area">

  	 <div class="row">

  		 <div class="col-12 col-lg-8">
  				 <div class="row">
  					 @if($result['news']['success']==1)
  					 @foreach($result['news']['news_data'] as $key=>$news_data)
  						 <div class="col-12 col-sm-12 col-md-6">
  								 <div class="blog">
  									 <div class="blog-thumbnail">
  										 <img class="img-thumbnail" src="{{asset('').$news_data->image_path}}" width="100%">
  										 @if($news_data->is_feature==1)
  										 <div class="badge badge-primary"><span>@lang('website.Featured')</span></div>
  										 @endif
  									 </div>

                     <a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}">
  									 <h6 class="blog-title">{{$news_data->news_name}}</h6>
                     </a>
  											 <p>
  													{{$news_data->news_description}}
  											 </p>
  								 </div>
  						 </div>
  					 @endforeach
  					 @endif

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
  			 </div>
  		 </div>

   </div>
  </div>
</section>
