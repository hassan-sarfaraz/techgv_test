<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Images;
use App\Models\Core\Setting;
use App\Models\Core\Languages;
use App\Models\Core\Manufacturers;
use App\Models\Core\Categories;
use App\Models\Core\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use App\Http\Controllers\AdminControllers\AlertController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;


class ProductController extends Controller
{

  public function __construct(Products $products, Languages $language,Images $images,Categories $category, Setting $setting,
    Manufacturers $manufacturer)
  {
      $this->category = $category;
      $this->language = $language;
      $this->images = $images;
      $this->manufacturer = $manufacturer;
      $this->products = $products;
      $this->myVarsetting = new SiteSettingController($setting);
      $this->myVaralter = new AlertController($setting);

  }

  public function display(Request $request){
      $language_id = '1';
      $categories_id = $request->categories_id;
      $product  = $request->product;
      $title = array('pageTitle' => Lang::get("labels.Products"));
      $subCategories = $this->category->getter($language_id);
      $products = $this->products->paginator($request);
      $results['products'] = $products;
      $results['currency'] = $this->myVarsetting->getSetting();
      $results['units'] = $this->myVarsetting->getUnits();
      $results['subCategories'] = $subCategories;
      $currentTime =  array('currentTime'=>time());
      return view("admin.products.index",$title)->with('results', $results)->with('categories_id',$categories_id)->with('product',$product);

  }

  public function add(Request $request){
     $title = array('pageTitle' => Lang::get("labels.AddProduct"));
     $language_id      =   '1';
     $allimage = $this->images->getimages();
     $result = array();
     $categories =  $this->category->recursivecategories($request);

     $parent_id = array();
     $option = '<ul class="list-group list-group-root well">';

      foreach($categories as $parents){

        if(in_array( $parents->categories_id, $parent_id)){
          $checked = 'checked';
        }else{
          $checked = '';
        }

        $option .= '<li href="#" class="list-group-item">
          <label style="width:100%">
            <input id="categories_'.$parents->categories_id.'" '.$checked.' type="checkbox" class=" required_one categories sub_categories" name="categories[]" value="'.$parents->categories_id.'">
          '.$parents->categories_name.'
          </label></li>';

        if(isset($parents->childs)){
          $option .= '<ul class="list-group">
          <li class="list-group-item">';
          $option .= $this->childcat($parents->childs, $parent_id);
          $option .= '</li></ul>';
        }
      }
      $option .= '</ul>';

     $result['categories'] = $option;

     $result['manufacturer'] = $this->manufacturer->getter($language_id);
     $taxClass = DB::table('tax_class')->get();
     $result['taxClass'] = $taxClass;
     $result['languages'] = $this->myVarsetting->getLanguages();
     $result['units'] = $this->myVarsetting->getUnits();
     return view("admin.products.add", $title)->with('result', $result)->with('allimage',$allimage);

  }

	public function childcat($childs, $parent_id){

		$contents = '';
		foreach($childs as $key => $child){

      if(in_array( $child->categories_id, $parent_id)){
        $checked = 'checked';
      }else{
        $checked = '';
      }

      $contents.='<label> <input id="categories_'.$child->categories_id.'" parents_id="'.$child->parent_id.'"  type="checkbox" name="categories[]" class="required_one sub_categories categories sub_categories_'.$child->parent_id.'" value="'.$child->categories_id.'" '.$checked.'> '.$child->categories_name.'</label>';

      if(isset($child->childs)){
        $contents .= '<ul class="list-group">
        <li class="list-group-item">';
        $contents.= $this->childcat($child->childs,$parent_id);
        $contents.= "</li></ul>";
		  }

		}
		return $contents;
	 }

  public function edit(Request $request){
    $allimage = $this->images->getimages();
    $result =  $this->products->edit($request);
    //dd($result['categories_array']);
    $categories =  $this->category->recursivecategories($request);

    $parent_id = $result['categories_array'];
    $option = '<ul class="list-group list-group-root well">';

    foreach($categories as $parents){

      if(in_array( $parents->categories_id, $parent_id)){
        $checked = 'checked';
      }else{
        $checked = '';
      }

      $option .= '<li href="#" class="list-group-item">
        <label style="width:100%">
          <input id="categories_'.$parents->categories_id.'" '.$checked.' type="checkbox" class=" required_one categories sub_categories" name="categories[]" value="'.$parents->categories_id.'">
        '.$parents->categories_name.'
        </label></li>';

      if(isset($parents->childs)){
        $option .= '<ul class="list-group">
        <li class="list-group-item">';
        $option .= $this->childcat($parents->childs, $parent_id);
        $option .= '</li></ul>';
      }
    }

    $option .= '</ul>';
    $result['categories'] = $option;
    $title = array('pageTitle' => Lang::get("labels.EditProduct"));
    return view("admin.products.edit", $title)->with('result', $result)->with('allimage',$allimage);

  }

  public function update(Request $request){
    $result =  $this->products->updaterecord($request);
    $products_id      =   $request->id;
    if($request->products_type==1){
      return redirect('admin/products/attach/attribute/display/'.$products_id);
    }else{
      return redirect('admin/products/images/display/'.$products_id);
    }
  }

  public function delete(Request $request){
    $this->products->deleterecord($request);
    return redirect()->back()->withErrors([Lang::get("labels.ProducthasbeendeletedMessage")]);

  }

  public function insert(Request $request){

    $title = array('pageTitle' => Lang::get("labels.AddAttributes"));
    $language_id = '1';
    $products_id = $this->products->insert($request);
    $result['data'] = array('products_id'=>$products_id, 'language_id'=>$language_id);
    $alertSetting = $this->myVaralter->newProductNotification($products_id);
    if($request->products_type==1){
        return redirect('/admin/products/attach/attribute/display/'.$products_id);
    }else{
        return redirect('admin/products/images/display/'.$products_id);
    }
  }

  public function addinventory(Request $request){
      $title = array('pageTitle' => Lang::get("labels.ProductInventory"));
      $id =  $request->id;
      $result = $this->products->addinventory($id);
      return view("admin.products.inventory.add", $title)->with('result', $result);

      }

  public function ajax_min_max($id){
      $title = array('pageTitle' => Lang::get("labels.ProductInventory"));
      $result = $this->products->ajax_min_max($id);
      return  $result;

      }

      public function ajax_min_max_bulk($id){
        $title = array('pageTitle' => Lang::get("labels.ProductInventory"));
        $result = $this->products->ajax_min_max_bulk($id);
        return  $result;
  
        }

  public function ajax_attr($id){
      $title = array('pageTitle' => Lang::get("labels.ProductInventory"));
      $result = $this->products->ajax_attr($id);
      return view("admin.products.inventory.attribute_div")->with('result', $result);

      }
  public function get_promo_products_from($id){
    $title = array('pageTitle' => Lang::get("labels.ProductInventory"));
    $result = $this->products->get_promo_attributes_variables($id);
    return view("admin.products.inventory.promo_product_attributes_from")->with('result', $result);
  }
  public function get_promo_products_to($id){
    $title = array('pageTitle' => Lang::get("labels.ProductInventory"));
    $result = $this->products->get_promo_attributes_variables($id);
    return view("admin.products.inventory.promo_product_attributes_to")->with('result', $result);
  }

  public function addinventoryfromsidebar(Request $request){
      $title = array('pageTitle' => Lang::get("labels.ProductInventory"));
      $result = $this->products->addinventoryfromsidebar();
      return view("admin.products.inventory.add1", $title)->with('result', $result);

      }


      public function addinventoryfromsidebarBulk(Request $request){
        $title = array('pageTitle' => Lang::get("labels.ProductInventory"));
        $result = $this->products->addinventoryfromsidebarBulk();
        return view("admin.products.inventory.addBulk", $title)->with('result', $result);
  
        }



  ## Promotions
  public function add_promotion(Request $request){
    $title = array('pageTitle' => Lang::get("labels.ProductPromotion"));
    $result = $this->products->add_promotion();
    return view("admin.products.promotion.add", $title)->with('result', $result);
  }

  public function addnewpromotion(Request $request){
    $this->products->addnewpromotion($request);
    return redirect('admin/products/promotion/promotion_list')->withErrors([Lang::get("labels.promotionaddedsuccessfully")]);;
  }
  public function promotion_list(Request $request){
    $title = array('pageTitle' => Lang::get("labels.promotion_list"));
    $result =  $this->products->promotion_list($request);
    return view("admin.products.promotion.list",$title)->with('result', $result);
  }
  public function promotion_edit(Request $request){
    $title = array('pageTitle' => Lang::get("labels.promotion_list"));
    $result =  $this->products->promotion_edit($request);
    return view("admin.products.promotion.edit",$title)->with('result', $result);
  }
  public function promotion_update(Request $request){
    $title = array('pageTitle' => Lang::get("labels.promotion_list"));
    $result =  $this->products->promotion_update($request);
    return redirect('/admin/products/promotion/promotion_list');
  }
  public function promotion_delete(Request $request){
    $this->products->promotion_delete($request);
    return redirect()->back()->withErrors([Lang::get("labels.promotiondeletesuccessfully")]);
  }

  ## end of promotions

    public function addnewstock(Request $request){
      // return $request->all();  
      $this->products->addnewstock($request);
      return redirect()->back()->withErrors([Lang::get("labels.inventoryaddedsuccessfully")]);

     }

     public function addnewstockBulk(Request $request){
      // return $request->all();
      $this->products->addnewstockBulk($request);
      return redirect()->back()->withErrors([Lang::get("labels.inventoryaddedsuccessfully")]);

     }

  public function addminmax(Request $request){

    $this->products->addminmax($request);
    return redirect()->back()->withErrors([Lang::get("labels.Min max level added successfully")]);

      }

  public function displayProductImages(Request $request){

    $title  = array('pageTitle' => Lang::get("labels.AddImages"));
    $products_id = $request->id;
    $result = $this->products->displayProductImages($request);
    return view("admin.products/images/index", $title)->with('result', $result)->with('products_id',$products_id);

      }

  public function addProductImages($products_id){

    $title = array('pageTitle' => Lang::get("labels.AddImages"));
    $allimage = $this->images->getimages();
    $result =   $this->products->addProductImages($products_id);
    return view('admin.products.images.add', $title)->with('result', $result)->with('products_id',$products_id)->with('allimage',$allimage);

      }

  public function insertProductImages(Request $request){
      $product_id = $this->products->insertProductImages($request);
      return redirect()->back()->with('product_id', $product_id);

      }

  public function editProductImages($id){

    $allimage = $this->images->getimages();
    $products_images = $this->products->editProductImages($id);
    return view("admin/products/images/edit")->with('products_images', $products_images)->with('allimage',$allimage);

  }

  public function updateproductimage(Request $request){

    $title = array('pageTitle' => Lang::get("labels.Manage Values"));
    $result = $this->products->updateproductimage($request);
    return redirect()->back();

      }

  public function deleteproductimagemodal(Request $request){

      $products_id = $request->products_id;
      $id = $request->id;
      $result['data'] = array('products_id'=>$products_id, 'id'=>$id);
      return view("admin/products/images/modal/delete")->with('result', $result);

  }

  public function deleteproductimage(Request $request){
   $this->products->deleteproductimage($request);
   return redirect()->back()->with('success',trans('labels.DeletedSuccessfully'));

      }

  public function addproductattribute(Request $request){

          $title = array('pageTitle' => Lang::get("labels.AddAttributes"));
          $result = $this->products->addproductattribute($request);
          return view("admin.products.attribute.add", $title)->with('result', $result);

      }

  public function addnewdefaultattribute(Request $request){

      $products_attributes =  $this->products->addnewdefaultattribute($request);
      return($products_attributes);

  }

  public function editdefaultattribute(Request $request){

      $result = $this->products->editdefaultattribute($request);
      return view("admin/products/pop_up_forms/editdefaultattributeform")->with('result', $result);

  }

  public function updatedefaultattribute(Request $request){
    $products_attributes = $this->products->updatedefaultattribute($request);
          return($products_attributes);

      }

  public function deletedefaultattributemodal(Request $request){

      $products_id = $request->products_id;
      $products_attributes_id = $request->products_attributes_id;
      $result['data'] = array('products_id'=>$products_id, 'products_attributes_id'=>$products_attributes_id);
      return view("admin/products/modals/deletedefaultattributemodal")->with('result', $result);

  }

  public function deletedefaultattribute(Request $request){
        $products_attributes =  $this->products->deletedefaultattribute($request);
          return($products_attributes);
  }

  public function showoptions(Request $request){
        $products_attributes = $this->products->showoptions($request);
        return($products_attributes);
    }

  public function editoptionform(Request $request){
      $result = $this->products->editoptionform($request);
      return view("admin/products/pop_up_forms/editproductattributeoptionform")->with('result', $result);

  }

  public function updateoption(Request $request){
      $products_attributes = $this->products->updateoption($request);
      return($products_attributes);
      }

  public function showdeletemodal(Request $request){

    $products_id = $request->products_id;
  	$products_attributes_id = $request->products_attributes_id;
  	$result['data'] = array('products_id'=>$products_id, 'products_attributes_id'=>$products_attributes_id);
    return view("admin/products/modals/deleteproductattributemodal")->with('result', $result);

  }

  public function deleteoption(Request $request){

    $products_attributes = $this->products->deleteoption($request);
    return($products_attributes);

  }

  public function getOptionsValue(Request $request){
     $value = $this->products->getOptionsValue($request);
    if(count($value)>0){
        foreach($value as $value_data){
            $value_name[] = "<option value='".$value_data->products_options_values_id."'>".$value_data->options_values_name."</option>";
        }
    }else{
        $value_name = "<option value=''>".Lang::get("labels.ChooseValue")."</option>";
    }
      print_r($value_name);
  }

  public function currentstock(Request $request){

      $result = $this->products->currentstock($request);
      print_r(json_encode($result));

   }


  }
