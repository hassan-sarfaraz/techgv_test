<?php

namespace App\Http\Controllers\AdminControllers;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use Hash;
use App\Models\Core\Administrator;
use App\Models\Core\Pages;
use App\Models\Core\NewsCategory;
use App;
use Lang;
use Auth;
use Illuminate\Http\Request;

class PagesController extends Controller
{

	public function pages(Request $request){
		$title 			= array('pageTitle' => Lang::get("labels.Pages"));
    $result = Pages::pages();
		return view("admin.pages.index",$title)->with('result', $result);

	}

	public function addpage(Request $request){

		$title = array('pageTitle' => Lang::get("labels.AddPage"));
     $result = Pages::addpage();
		return view("admin.pages.add", $title)->with('result', $result);

	}

	//addNewPage
	public function addnewpage(Request $request){

		$title = array('pageTitle' => Lang::get("labels.AddPage"));
      Pages::addnewpage($request);
		$message = Lang::get("labels.PageAddedMessage");
		return redirect()->back()->withErrors([$message]);

	}

	//editnew
	public function editpage(Request $request){

		$title = array('pageTitle' => Lang::get("labels.EditPage"));
    $result = Pages::editpage($request);
		return view("admin.pages.edit", $title)->with('result', $result);

	}


	//updatePage
	public function updatepage(Request $request){

       Pages::updatepage($request);
		$message = Lang::get("labels.PageUpdateMessage");
		return redirect()->back()->withErrors([$message]);

	}


	//pageStatus
	public function pageStatus(Request $request){

        Pages::pageStatus($request);
    		return redirect()->back()->withErrors([Lang::get("labels.PageStatusMessage")]);

	}


	//listing web pages
	public function webpages(Request $request){

		$title 			= array('pageTitle' => Lang::get("labels.Pages"));
    $result = Pages::webpages($request);
		return view("admin.pages.webpages.index",$title)->with('result', $result);

	}

	public function addwebpage(Request $request){

		$title = array('pageTitle' => Lang::get("labels.AddPage"));
     $result = Pages::addwebpage($request);
		return view("admin.pages.webpages.add", $title)->with('result', $result);

	}

	//addNewPage
	public function addnewwebpage(Request $request){

		$title = array('pageTitle' => Lang::get("labels.AddPage"));
      Pages::addnewwebpage($request);
		$message = Lang::get("labels.PageAddedMessage");
		return redirect()->back()->withErrors([$message]);

	}

	//editnew
	public function editwebpage(Request $request){

		$title = array('pageTitle' => Lang::get("labels.EditPage"));
    $result = Pages::editwebpage($request);
		return view("admin.pages.webpages.edit", $title)->with('result', $result);

	}


	//updatePage
	public function updatewebpage(Request $request){

      Pages::updatewebpage($request);
		$message = Lang::get("labels.PageUpdateMessage");
		return redirect()->back()->withErrors([$message]);

	}


	//pageStatus
	public function pageWebStatus(Request $request){

        Pages::pageWebStatus($request);
			return redirect()->back()->withErrors([Lang::get("labels.PageStatusMessage")]);

	}


}
