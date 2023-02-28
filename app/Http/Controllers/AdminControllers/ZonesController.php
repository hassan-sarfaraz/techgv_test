<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Zones;
use App\Models\Core\Countries;
use App\Tax_class;
use App\Tax_rate;
use App\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;

class ZonesController extends Controller
{
    //
    public function __construct( Zones $zones, Countries $countries){
        $this->Countries = $countries;
        $this->Zones = $zones;
    }

    public function index(Request $request){
      $title = array('pageTitle' => Lang::get("labels.ListingZones"));
      $zones = $this->Zones->paginator();
      return view("admin.zones.index",$title)->with('zones', $zones);
    }

    public function add(Request $request){
        $title = array('pageTitle' => Lang::get("labels.AddZone"));
        $result = array();
        $message = array();
        $countries = $this->Countries->getter();
        $result['countries'] = $countries;
        $result['message'] = $message;
        return view("admin.zones.add", $title)->with('result', $result);
    }

    public function insert(Request $request){
        $this->Zones->insert($request);
        $message = Lang::get("labels.ZoneAddedMessage");
        return Redirect::back()->with('message',$message);
    }

    public function edit(Request $request){
        $title = array('pageTitle' => Lang::get("labels.EditZone"));
        $result = array();
        $result['message'] = array();

        $zones = $this->Zones->edit($request);
        $countries = $this->Countries->getter();
        $result['countries'] = $countries;
        $result['zones'] = $zones;
        return view("admin..zones.edit",$title)->with('result', $result);
    }

    public function update(Request $request){
        $this->Zones->updaterecord($request);
        $countryData['message'] = 'Zone has been updated successfully!';
        $message = Lang::get("labels.Zone has been updated successfully");
        return Redirect::back()->with('message',$message);
    }

    public function delete(Request $request){
        $this->Zones->deleterecord($request);
        return redirect()->back()->withErrors([Lang::get("labels.ZoneDeletedTax")]);
    }

    public function filter(Request $request){
        $name = $request->FilterBy;
        $param = $request->parameter;
        $title = array('pageTitle' => Lang::get("labels.ListingZones"));
        $zones = $this->Zones->filter($request);
        return view("admin.zones.index",$title)->with('zones', $zones)->with('name', $name)->with('param', $param);
    }

}
