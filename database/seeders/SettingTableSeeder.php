<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings =  DB::table('settings')->get();
        foreach( $settings as $setting){
            if($setting->name == 'address'){
                DB::table('settings')->where('id', $setting->id)->update(array('value_ar' => 'مدينة دبي الطبية ، طريق ابن سينا'));  
            }
            if($setting->name == 'city'){
                DB::table('settings')->where('id', $setting->id)->update(array('value_ar' => 'أبو ظبي'));  
            }
            if($setting->name == 'country'){
                DB::table('settings')->where('id', $setting->id)->update(array('value_ar' => 'الإمارات العربية المتحدة'));  
            }
            if($setting->name == 'contact_us_email'){
                DB::table('settings')->where('id', $setting->id)->update(array('value_ar' => 'info@proteindistrict.ae'));  
            }
            if($setting->name == 'phone_no'){
                DB::table('settings')->where('id', $setting->id)->update(array('value_ar' => '+971 4 4206474'));  
            }
        }
        
}
}
