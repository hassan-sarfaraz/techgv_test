<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;



class Theme extends Model
{
  public function getBanners($banner_id){
      if($banner_id == 1){
        $banner_title = 'banner1';
      }
      elseif($banner_id == 2){
        $banner_title = 'banner2_3_4';
      }
      elseif($banner_id == 3){
        $banner_title = 'banner2_3_4';
      }
      elseif($banner_id == 4){
        $banner_title = 'banner2_3_4';
      }
      elseif($banner_id == 5){
        $banner_title = 'banner5_6';
      }
      elseif($banner_id == 6){
        $banner_title = 'banner5_6';
      }
      elseif($banner_id == 7){
        $banner_title = 'banner7_8';
      }
      elseif($banner_id == 8){
        $banner_title = 'banner7_8';
      }
      elseif($banner_id == 9){
        $banner_title = 'banner9';
      }
      elseif($banner_id == 10){
        $banner_title = 'banner10_11_12';
      }
      elseif($banner_id == 11){
        $banner_title = 'banner10_11_12';
      }
      elseif($banner_id == 12){
        $banner_title = 'banner10_11_12';
      }
      elseif($banner_id == 13){
        $banner_title = 'banner13_14_15';
      }
      elseif($banner_id == 14){
        $banner_title = 'banner13_14_15';
      }
      elseif($banner_id == 15){
        $banner_title = 'banner13_14_15';
      }
      elseif($banner_id == 16){
        $banner_title = 'banner16_17';
      }
      elseif($banner_id == 17){
        $banner_title = 'banner16_17';
      }
      elseif($banner_id == 18){
        $banner_title = 'banner18_19';
      }
      elseif($banner_id == 19){
        $banner_title = 'banner18_19';
      }
      elseif($banner_id == 20){
        $banner_title = 'style0';
      }
      elseif($banner_id == 21){
        $banner_title = 'ad_banner1';
      }
      else{
        $banner_title = 'ad_banner2';
      }

     $records = array();
      $languages = DB::table('languages')->get();
      foreach ($languages as $key => $value) {
        $records[$key] = (array) $value;
        $banners = DB::table('constant_banners')
       ->leftJoin('image_categories','constant_banners.banners_image','=','image_categories.image_id')
       ->select('constant_banners.*','image_categories.path')
       ->where('constant_banners.languages_id', $value->languages_id)
       ->where('constant_banners.banners_title', $banner_title)
       ->groupBy('constant_banners.banners_id')
       ->orderby('type','ASC')
       ->get();
        $records[$key]['banners'] = $banners;
      }
    return $records;
  }

  public function getSliders($carousal_id){

     $records = array();
      $languages = DB::table('languages')->get();
      foreach ($languages as $key => $value) {
        $records[$key] = (array) $value;
        $sliders = DB::table('sliders_images')
                     ->leftJoin('languages','languages.languages_id','=','sliders_images.languages_id')
                     ->leftJoin('image_categories','sliders_images.sliders_image','=','image_categories.image_id')
                     ->where('sliders_images.languages_id', $value->languages_id)
                     ->where('sliders_images.carousel_id', $carousal_id)
                     ->select('sliders_images.*','image_categories.path')
                     ->orderBy('sliders_images.sliders_id','ASC')
                     ->groupBy('sliders_images.sliders_id')
                     ->get();
        $records[$key]['sliders'] = $sliders;
      }
    return $records;
  }

  public function getBannersForUpdate($banner_title){

     $banner_title = $banner_title;
     $records = array();
      $languages = DB::table('languages')->get();
      foreach ($languages as $key => $value) {
        $records[$key] = (array) $value;
        $banners = DB::table('constant_banners')
       ->leftJoin('image_categories','constant_banners.banners_image','=','image_categories.image_id')
       ->select('constant_banners.*','image_categories.path')
       ->where('constant_banners.languages_id', $value->languages_id)
       ->where('constant_banners.banners_title', $banner_title)
       ->groupBy('constant_banners.banners_id')
       ->orderby('type','ASC')
       ->get();
        $records[$key]['banners'] = $banners;
      }

    return $records;
  }

  public function getSlidersForUpdate($carousal_id){

         $records = array();
          $languages = DB::table('languages')->get();
          foreach ($languages as $key => $value) {
            $records[$key] = (array) $value;
            $sliders = DB::table('sliders_images')
                         ->leftJoin('languages','languages.languages_id','=','sliders_images.languages_id')
                         ->leftJoin('image_categories','sliders_images.sliders_image','=','image_categories.image_id')
                         ->where('sliders_images.languages_id', $value->languages_id)
                         ->where('sliders_images.carousel_id', $carousal_id)
                         ->select('sliders_images.*','image_categories.path')
                         ->orderBy('sliders_images.sliders_id','ASC')
                         ->groupBy('sliders_images.sliders_id')
                         ->get();
            $records[$key]['sliders'] = $sliders;
          }
        return $records;
  }



  public function updateBanners($request){
    DB::table('constant_banners')->where('banners_id', $request->banner_id)->update([
        'banners_image'			 =>	  $request->image_id,
        'banners_url'			 =>	  $request->url,
        ]);
  }

  public function updateSliders($request){
    DB::table('sliders_images')->where('sliders_id', $request->slider_id)->update([
        'sliders_image'			 =>	  $request->image_id,
        'sliders_url'			 =>	  $request->url,
        ]);
  }


}
