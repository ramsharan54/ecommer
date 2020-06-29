<?php

namespace App\Http\Controllers;
use App\Category;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public static function mainCategories(){
    	$maincategories = Category::where(['parent_id'=>0])->get();
    	/*$maincategories = json_decode(json_encode($maincategories));
    		print_r($maincategories);die;*/
    	return $maincategories;
    }
}
