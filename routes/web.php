<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
     return view('welcome');
 });




Route::match(['get','post'],'/admin','AdminController@login');
;
Route::get('logout','AdminController@logout');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//index page
Route::get('/','IndexController@index');

//Cacetory/Listing page
Route::get('/products/{urls}','ProductController@products');
//product detail page
Route::get('product/{id}','ProductController@product');
// Cart Page
Route::match(['get', 'post'],'/cart','ProductController@cart');
// Delete Product from Cart Route
Route::get('/cart/delete-product/{id}','ProductController@deleteCartProduct');
// Update Product Quantity from Cart
Route::get('/cart/update-quantity/{id}/{quantity}','ProductController@updateCartQuantity');



//add to cart
Route::match(['get','post'],'/add-cart','ProductController@addtocart');
//Get Product Attributes Price same as in main,js
Route::get('/get-product-price','ProductController@getproductprice');


Route::group(['middleware'=>['auth']],function(){
Route::get('/admin/dashboard','AdminController@dashboard');
Route::get('/admin/settings','AdminController@settings');
Route::get('/admin/check-pwd','AdminController@chkpassword');
Route::match(['get','post'],'/admin/update-pwd','AdminController@updatepassword');


//Categories Routes (Admin)
Route::match(['get','post'],'/admin/add-category','CategoryController@addcategory');
Route::get('/admin/view-categories','CategoryController@viewCategories');
Route::match(['get','post'],'/admin/edit-category/{id}','CategoryController@editCategory');
Route::match(['get','post'],'/admin/delete-category/{id}','CategoryController@deleteCategory');
});
//product Route
Route::match(['get','post'],'/admin/add-products','ProductController@addproduct');
Route::get('/admin/view-products','ProductController@viewproduct');
Route::match(['get','post'],'/admin/edit-products/{id}','ProductController@editProduct');
Route::get('admin/delete_product/{id}','ProductController@deleteproduct');
Route::get('admin/delete-product-image/{id}','productController@deleteProductImage');
Route::get('admin/delete-alt-image/{id}','productController@deleteAltImage');


//Route for Product Attribute
Route::match(['get','post'],'admin/add-attributes/{id}','ProductController@addAttribute');
Route::match(['get','post'],'admin/edit-attributes/{id}','ProductController@editAttribute');
Route::match(['get','post'],'admin/add-images/{id}','ProductController@addImages');
Route::get('admin/delete-attribute/{id}', 'ProductController@deleteAttribute');


//Coupons Route
Route::match(['get','post'],'admin/add-coupon','CouponsController@addCoupon');
Route::get('/admin/view-coupons','CouponsController@viewCoupons');
Route::match(['get','post'],'/admin/edit-coupon/{id}','CouponsController@editCoupon');
Route::get('admin/delete-coupon/{id}','CouponsController@deleteCoupon');
