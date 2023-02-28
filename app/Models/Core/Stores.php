<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class Stores extends Model
{
    use Sortable;

    public function __construct() {
        $varsetting = new SiteSettingController();
        $this->varsetting = $varsetting;
    }

    public function stores_info(){
        return $this->hasOne('App\stores_info');
    }

    public $sortable = ['stores_id', 'store_name', 'store_email','store_phone','store_address','status','created_at','updated_at'];

    public function paginator(){
        $stores =  Stores::sortable(['stores_id'=>'desc'])
                    ->leftJoin('stores_info','stores_info.stores_id', '=', 'stores.stores_id')
                    ->select('stores.stores_id as id', 'stores.store_name as name', 'stores.store_email as email', 'stores.store_phone as phone', 'stores.store_address as address','stores.status as status','stores.store_lat','stores.store_lng')
                    ->where('stores_info.languages_id', '1')
                    ->paginate(5);
        return $stores;
    }

    public function getter($language_id){
        if($language_id == null){
            $language_id = '1';
        }
        $stores =  Stores::sortable(['stores_id'=>'desc'])
                    ->leftJoin('stores_info','stores_info.stores_id', '=', 'stores.stores_id')
                    ->select('stores.stores_id as id', 'stores.store_name as name', 'stores.store_email as email', 'stores.store_phone as phone', 'stores.store_address as address','stores.status as status','stores.store_lat','stores.store_lng')
                    ->where('stores_info.languages_id', $language_id)
                    ->get();
        return $stores;
    }

    public function insert($request){

        $date_added	    = date('y-m-d h:i:s');
        $languages_id 	=  '1';

        $stores_id = DB::table('stores')->insertGetId([
            'store_name'        => $request->name,
            'store_email'	    => $request->email,
            'store_phone'	    => $request->phone,
            'store_address'	    => $request->address,
            'status'	        => $request->status,
            'store_lat'         => $request->store_lat,
            'store_lng'         => $request->store_lng,
            'created_at'		=> $date_added,
        ]);

        DB::table('stores_info')->insert([
            'stores_id'  	=> $stores_id,
            'languages_id'	=> $languages_id,
        ]);

    }

    public function edit($stores_id){

        $editStore = DB::table('stores')
            ->leftJoin('stores_info','stores_info.stores_id', '=', 'stores.stores_id')
            ->select('stores.stores_id as id', 'stores.store_name as name', 'stores.store_email as email', 'stores.store_phone as phone', 'stores.store_address as address','stores.status as status','stores.store_lat','stores.store_lng')
            ->where('stores.stores_id', $stores_id)
            ->get();

        return $editStore;
    }

    public function filter($name,$param){
        switch ( $name )
        {
            case 'Name':
                $stores = Stores::sortable(['stores_id'=>'desc'])
                        ->leftJoin('stores_info','stores_info.stores_id', '=', 'stores.stores_id')
                        ->select('stores.stores_id as id', 'stores.store_name as name', 'stores.store_email as email', 'stores.store_phone as phone', 'stores.store_address as address','stores.status as status','stores.store_lat','stores.store_lng')
                        ->where('stores.store_name', 'LIKE', '%' . $param . '%')->paginate('10');
                break;

            case 'Email':
                $stores = Stores::sortable(['stores_id'=>'desc'])
                        ->leftJoin('stores_info','stores_info.stores_id', '=', 'stores.stores_id')
                        ->select('stores.stores_id as id', 'stores.store_name as name', 'stores.store_email as email', 'stores.store_phone as phone', 'stores.store_address as address','stores.status as status','stores.store_lat','stores.store_lng')
                        ->where('stores.store_email', 'LIKE', '%' . $param . '%')->paginate('10');
                break;


            default:
                $stores = Stores::sortable(['stores_id'=>'desc'])
                        ->leftJoin('stores_info','stores_info.stores_id', '=', 'stores.stores_id')
                        ->select('stores.stores_id as id', 'stores.store_name as name', 'stores.store_email as email', 'stores.store_phone as phone', 'stores.store_address as address','stores.status as status','stores.store_lat','stores.store_lng')
                        ->where('stores_info.languages_id', '1')->paginate('10');
        }
        return $stores;
    }

    public function fetchAllmanufacturers($language_id){

        $getManufacturers = DB::table('manufacturers')
            ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
            ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date')
            ->where('manufacturers_info.languages_id', $language_id)->get();
        return $getManufacturers;
    }

    public function fetchmanufacturers(){

        $manufacturers = DB::table('manufacturers')
            ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
            ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
            ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
            ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
            ->where('manufacturers_info.languages_id', '1')->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL');


        return $manufacturers;
    }

    public function updaterecord($request){

        $last_modified 	=   date('y-m-d h:i:s');
        $languages_id = '1';

        DB::table('stores')->where('stores_id', $request->id)->update([
            'store_name' 	        => $request->name,
            'store_email'	        => $request->email,
            'store_phone'           => $request->phone,
            'store_address'         => $request->address,
            'store_lat'             => $request->store_lat,
            'store_lng'             => $request->store_lng,
            'status'                => $request->status,
            'updated_at'			=> $last_modified,
        ]);
        DB::table('stores_info')->where('stores_id', $request->id)->update([
            'languages_id'			=>	   $languages_id,
        ]);

    }

    public function destroyrecord($request){
        DB::table('stores')->where('stores_id', $request->stores_id)->delete();
        DB::table('stores_info')->where('stores_id', $request->stores_id)->delete();
    }

    public function fetchsortmanufacturers($name, $param){

        switch ( $name )
        {
            case 'Name':
                $manufacturers = DB::table('manufacturers')
                    ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
                    ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
                    ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
                    ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
                    ->where('manufacturers.manufacturer_name', 'LIKE', '%' . $param . '%')->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL')->paginate('10');
                break;

            case 'URL':
                $manufacturers = DB::table('manufacturers')
                    ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
                    ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
                    ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
                    ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
                    ->where('manufacturers_info.manufacturers_url', 'LIKE', '%' . $param . '%')->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL')->paginate('10');
                break;


            default:
                $manufacturers = DB::table('manufacturers')
                    ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
                    ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
                    ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
                    ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
                    ->where('manufacturers_info.languages_id', '1')->paginate('10');
        }


        return $manufacturers;


    }

}
