@extends('web.layout')
@section('content')
<!-- End Header Content -->
<!-- NOTIFICATION CONTENT -->
 @include('web.common.notifications')
<!-- END NOTIFICATION CONTENT -->
<!-- Carousel Content -->
<?php  echo $final_theme['carousel']; ?>
<!-- Fixed Carousel Content -->
<!-- Banners Content -->
<!-- Products content -->
<?php
$product_section_orders = json_decode($final_theme['product_section_order'], true);

foreach ($product_section_orders as $product_section_order){
  if($product_section_order['order'] == 1 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  @include($r)
<?php
  }
  if($product_section_order['order'] == 2 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  @include($r)
<?php
  }
  if($product_section_order['order'] == 3 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  {{--@include($r)--}}
<?php
  }
  if($product_section_order['order'] == 4 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  @include($r)
<?php
  }
  if($product_section_order['order'] == 5 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  @include($r)
<?php
  }
  if($product_section_order['order'] == 6 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  @include($r)
<?php
  }
  if($product_section_order['order'] == 7 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  @include($r)
<?php
  }
  if($product_section_order['order'] == 8 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  @include($r)
<?php
  }
  if($product_section_order['order'] == 9 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  @include($r)
<?php
  }
  if($product_section_order['order'] == 10 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  @include($r)
<?php
  }
  if($product_section_order['order'] == 11 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  @include($r)
<?php
  }
  if($product_section_order['order'] == 12 && $product_section_order['status'] == 1){
  $r =   'web.product-sections.' . $product_section_order['file_name'];
?>
  @include($r)
<?php
  }
}
?>
<div class="modal fade" tabindex="-1" id="promotionPop" role="dialog" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="promotionPop" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
    <div class="modal-header" style="padding: 0.3rem 0.5rem;border: none;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body pt-0 pb-4">
      <div class="row align-items-center">
        <div class="col-12 col-md-12">

          <div class="promo-box">
              @if(session()->get('locale') == 'en')
                  <div class="WelcomePopup__header">Welcome to Protein District</div>
                  <p class="text-03" style="font-size: 14px;text-align: center;max-width: 85%;margin: 1rem auto;">
                      Get 30% off your first order! <br />Use <strong>PD30</strong> Coupon to avail this offer
                  </p>
                  <center>
                      <a href="{{url('/shop')}}" class="btn rounded btn-sm btn-block btn-success w-75">Shop Now!</a>
                  </center>
              @else
                  <div class="WelcomePopup__header">Welcome to Protein District</div>
                  <p class="text-03" style="font-size: 14px;text-align: center;max-width: 85%;margin: 1rem auto;">
                      Get 30% off your first order! <br />Use <strong>PD30</strong> Coupon to avail this offer
                  </p>
                  <center>
                      <a href="{{url('/shop')}}" class="btn rounded btn-sm btn-block btn-success w-75">Shop Now!</a>
                  </center>
              @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@include('web.common.scripts.Like')
@endsection
