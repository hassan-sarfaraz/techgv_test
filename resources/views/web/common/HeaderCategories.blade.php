<?php
    function productCategoriesMenu(){
        $categories = recursivecategories();
        if($categories){
            $parent_id = 0;
            $option = '';
            foreach($categories as $parents){
                if(!isset($parents->childs)){
                    continue;
                }
                $option .='<li class="col-sm-4" style="float: left; line-height: 2;width: auto;">
                <strong class="title"><a >'.strtoupper($parents->categories_name).'</a></strong>
                <ul>';
                if(isset($parents->childs)){
                $i = 1;
                    $option .= childcatManue($parents->childs, $i, $parent_id);
                }
            $option .= '</ul></li>';
            }
            echo $option;
        }
    }

    function productCategories(){
        $categories = recursivecategories();
        if($categories){
            $parent_id = 0;
            $option = '<option value="0">'. Lang::get("website.Choose Any Category").'</option>';
            foreach($categories as $parents){
                if($parents->slug==app('request')->input('category')){
                    $selected = "selected";
                }else {
                    $selected = "";
                }
                $option .= '<option value="'.$parents->slug.'" '.$selected.'>'.$parents->categories_name.'</option>';

                if(isset($parents->childs)){
                    $i = 1;
                    $option .= childcat($parents->childs, $i, $parent_id);
                }
            }
            echo $option;
        }
    }

    function childcat($childs, $i, $parent_id){
        $contents = '';
        foreach($childs as $key => $child){
            $dash = '';
            for($j=1; $j<=$i; $j++){
                $dash .=  '-';
            }
            if($child->slug==app('request')->input('category')){
                $selected = "selected";
            }else {
                $selected = "";
            }
            $contents.='<option value="'.$child->slug.'" '.$selected.'>'.$dash.$child->categories_name.'</option>';
            if(isset($child->childs)){
                $k = $i+1;
                $contents.= childcat($child->childs,$k,$parent_id);
            }
            elseif($i>0){
                $i=1;
            }
        }
        return $contents;
    }

    function childcatManue($childs, $i, $parent_id){
        $contents = '';
        foreach($childs as $key => $child){
            $contents.='<li class="inner-li"><a href="'.url('/').'/shop?category='.$child->slug.'">'.$child->categories_name.'</li></a>';
            if(isset($child->childs)){
                $k = $i+1;
                $contents.= childcatManue($child->childs,$k,$parent_id);
            }
            elseif($i>0){
                $i=1;
            }
        }
        return $contents;
    }


    function recursivecategories(){
        $items = DB::table('categories')
                ->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
                ->select('categories.categories_id', 'categories.categories_slug as slug','categories_description.categories_name', 'categories.parent_id')
                ->where('categories_description.language_id','=', Session::get('language_id'))
                //->orderby('categories_id','ASC')
                ->get();
        if($items->isNotEmpty()){
            $childs = array();
            foreach($items as $item)
                $childs[$item->parent_id][] = $item;

            foreach($items as $item) if (isset($childs[$item->categories_id]))
                $item->childs = $childs[$item->categories_id];

            $tree = $childs[0];
            return  $tree;
        }
    }



    function headerBrands(){

$manufacturers =  DB::table('manufacturers')->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
    ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
    ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
    ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
    ->where('manufacturers_info.languages_id', '1')->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL')->get();


return $manufacturers;
}
