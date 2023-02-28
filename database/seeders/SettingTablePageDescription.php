<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class SettingTablePageDescription extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages_descriptions =  DB::table('pages_description')->get();
        foreach( $pages_descriptions as $data){
            if($data->name == 'Privacy Policy'){
                DB::table('pages_description')->where('page_description_id', $data->page_description_id)->update(array('name_ar' => 'سياسة الخصوصية'));  
            }
            if($data->name == 'Term & Services'){
                DB::table('pages_description')->where('page_description_id', $data->page_description_id)->update(array('name_ar' => 'الشروط والخدمات'));  
            }
            if($data->name == 'Refund Policy'){
                DB::table('pages_description')->where('page_description_id', $data->page_description_id)->update(array('name_ar' => 'سياسة الاسترجاع'));  
            }
            if($data->name == 'About Us'){
                DB::table('pages_description')->where('page_description_id', $data->page_description_id)->update(array('name_ar' => 'معلومات عنا'));  
            }
            if($data->name == 'Privacy Policy'){
                DB::table('pages_description')->where('page_description_id', $data->page_description_id)->update(array('name_ar' => 'سياسة خاصة'));  
            }
            if($data->name == 'Refund Policy'){  
                DB::table('pages_description')->where('page_description_id', $data->page_description_id)->update(array('name_ar' => 'سياسة الاسترجاع'));  
            }
            if($data->name == 'About Us'){
                DB::table('pages_description')->where('page_description_id', $data->page_description_id)->update(array('name_ar' => 'معلومات عنا'));
            }
            if($data->name == 'Contact Us'){
                DB::table('pages_description')->where('page_description_id', $data->page_description_id)->update(array('name_ar' => 'اتصل بنا'));
            }
            
        }
    }
}
