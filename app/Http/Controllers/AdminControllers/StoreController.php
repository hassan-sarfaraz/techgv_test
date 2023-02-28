<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Stores;
use App\Models\Core\Setting;
use App\Models\Core\Languages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Exception;
use Kyslik\ColumnSortable\Sortable;

class StoreController extends Controller {

    public function __construct(Stores $stores,Languages $language)
    {
        $this->stores   =$stores;
        $this->language = $language;
    }

    public function display(){
        $title = array('pageTitle' => Lang::get("labels.stores"));
        $stores = $this->stores->paginator(5);
        return view("admin.stores.index")->with('stores',$stores);
    }

    public function add(Request $request){
        $title = array('pageTitle' => Lang::get("labels.AddStore"));
        return view("admin.stores.add",$title);
    }

    public function insert(Request $request){
        $title = array('pageTitle' => Lang::get("labels.AddStore"));
        $this->stores->insert($request);
        return redirect()->back()->with('update', 'Content has been created successfully!');
    }

    public function edit(Request $request){
        $title = array('pageTitle' => Lang::get("labels.EditStore"));
        $stores_id = $request->id;
        $editStore = $this->stores->edit($stores_id);
        return view("admin.stores.edit",$title)->with('editStore', $editStore);
    }


    public function update(Request $request){
        $messages = 'update is not successful' ;
        $title = array('pageTitle' => Lang::get("labels.EditStore"));
        $this->validate($request, [
            'id'        => 'required',
            'name'      => 'required',
            'email'     => 'required|email',
            'status'    => 'required',

        ]);
        $this->stores->updaterecord($request);
        return redirect()->back()->with('update', 'Content has been created successfully!');

    }

    //delete Manufacturers
    public function delete(Request $request){
        $this->stores->destroyrecord($request);
        return redirect()->back()->withErrors([Lang::get("labels.storesDeletedMessage")]);
    }

    public function filter(Request $request){

        $name = $request->FilterBy;
        $param = $request->parameter;
        $title = array('pageTitle' => Lang::get("labels.stores"));
        $stores = $this->stores->filter($name,$param);
        return view("admin.stores.index",$title)->with('stores', $stores)->with('name',$name)->with('param',$param);
    }



}
