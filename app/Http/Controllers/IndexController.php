<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        // Product in Descending
        $productsAll = Product::orderBy('id','DESC')->get();

         // Product in Ascending
        $productsAll = Product::get();
         // Product in Ramdom
        $productsAll = Product::inRandomOrder()->where('status',1)->get();
        
        //Get all categories and subcategories
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();// here(with('categoris') is using relation mapping)
       // $categories = json_decode(json_encode($categories));
       // echo "<pre>";  print_r($categories);die;
       foreach ($categories as $cat){
       // echo $cat->name; echo "<br>";
        $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $subcat){
        //echo $subcat->name;echo "<br>";
       }
     }


        return view('index')->with(compact('productsAll','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
