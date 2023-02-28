<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Manufacturers;
use App\Models\Core\Images;
use App\Models\Core\Setting;
use App\Models\Core\Languages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Exception;
use Kyslik\ColumnSortable\Sortable;




class ManufacturerController extends Controller
{

    //
    public function __construct(Manufacturers $manufacturer,Languages $language,Images $images)
    {
      $this->manufacturers =$manufacturer;
      $this->language = $language;
      $this->images = $images;
    }

    public function display(){
      $title = array('pageTitle' => Lang::get("labels.Manufacturers"));
      $manufacturers = $this->manufacturers->paginator(5);
      return view("admin.manufacturers.index")->with('manufacturers',$manufacturers);
    }

    public function add(Request $request){
      $allimage = $this->images->getimages();
      $title = array('pageTitle' => Lang::get("labels.AddManufacturer"));
      return view("admin.manufacturers.add",$title)->with('allimage',$allimage);
    }

    public function insert(Request $request){
      $title = array('pageTitle' => Lang::get("labels.AddManufacturer"));
      $this->manufacturers->insert($request);
      return redirect()->back()->with('update', 'Content has been created successfully!');
    }

    public function edit(Request $request){
      $title = array('pageTitle' => Lang::get("labels.EditManufacturers"));
      $manufacturers_id = $request->id;
      $editManufacturer = $this->manufacturers->edit($manufacturers_id);
      $allimage = $this->images->getimages();
      return view("admin.manufacturers.edit",$title)->with('editManufacturer', $editManufacturer)->with('allimage',$allimage);
    }


    public function update(Request $request){
        $messages = 'update is not successfull' ;
        $title = array('pageTitle' => Lang::get("labels.EditManufacturers"));
        $this->validate($request, [
            'id' => 'required',
            'oldImage' => 'required',
            'old_slug' => 'required',
            'slug' => 'required',
            'name' => 'required',
            'manufacturers_url' => 'required',

        ]);
           $this->manufacturers->updaterecord($request);
            return redirect()->back()->with('update', 'Content has been created successfully!');

        }

   //delete Manufacturers
    public function delete(Request $request){

        $this->manufacturers->destroyrecord($request);
        return redirect()->back()->withErrors([Lang::get("labels.manufacturersDeletedMessage")]);
        }

    public function filter(Request $request){

          $name = $request->FilterBy;
          $param = $request->parameter;
          $title = array('pageTitle' => Lang::get("labels.Manufacturers"));
          $manufacturers = $this->manufacturers->filter($name,$param);
          return view("admin.manufacturers.index",$title)->with('manufacturers', $manufacturers)->with('name',$name)->with('param',$param);
    }



}
