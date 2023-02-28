<?php

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Alert extends Model
{

    public function getUserDevices($customers_id){
      $device = DB::table('devices')->where('customers_id','=', $customers_id)->get();
      return $device;
    }

    public function getAlertSetting(){
      $setting = DB::table('alert_settings')->get();
      return $setting;
    }

    public function setting(){
      $setting = DB::table('settings')->get();
      return $setting;
    }

}
