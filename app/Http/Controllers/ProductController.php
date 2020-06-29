<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Category;
use App\Product;
use Auth;
use Session;
use Image;
use App\ProductsAttribute;
use App\ProductsImage;
use DB;



class productController extends Controller
{
    public function addProduct(Request $request){
       if($request->isMethod('post')){
            $data = $request->all();
            //dd($data);
           if (empty($data['category_id'])) {
                return redirect()->back()->with('flash_message_success','Under Category is missing!');
            }
               $product = new product();
               $product->category_id = $data['category_id'];
               $product->product_name = $data['product_name'];
               $product->product_code = $data['product_code'];
               $product->product_color = $data['product_color'];
               if (!empty($data['description'])) {
                   $product->description = $data['description']; 
               }else{
               $product->description = ''; 
               } 
                if (!empty($data['care'])) {
                   $product->care = $data['care']; 
               }else{
               $product->care = ''; 
               } 

               $product->price = $data['price'];

                 //Upload File
                 if ($request->hasFile('image')) {
                     $image_tmp = Input::file('image');
                     if ($image_tmp->isValid()) {
                         //echo "test";die;
                        $extention = $image_tmp->getClientOriginalExtension();
                        $filename = rand(111,99999).'.'.$extention; 
                        $large_image_path = 'images/backend_images/products/large/'.$filename;
                        $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                        $small_image_path = 'images/backend_images/products/small/'.$filename;
                          //Resize Image
                        Image::make($image_tmp)->resize(1200,1200)->save($large_image_path);
                        Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                        Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                            //Strore images name in products table
                        $product->image = $filename;
                     }
                 }
                 if(empty($data['status'])){
                  $status=1;
                 }else{
                  $status=0;
                 }
                 $product->status=$status;
                // $product->image = ''; initially for checking
                 $product->save();
                 return redirect('admin/view-products')->with('flash_message_success','Product has been added Successfully'); 
        }
        //Category dropdown start
         	    $categories = Category::where(['parent_id'=>0])->get();
    	        $categories_dropdown = "<option value='' selected disabled>Select</option>";
    	        foreach($categories as $cat) {
    	          $categories_dropdown .="<option value=' ".$cat->id."'>".$cat->name."</option>";
    		        $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
    		        foreach ($sub_categories as $sub_cat) {
    			             $categories_dropdown .= "<option value ='".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
    		        }
    	        }
          //Category dropdown end
    	return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }


    public function editproduct(Request $request, $id=null){
        if ($request->isMethod('post')) {
            $data = $request->all();
              //Upload File
                 if ($request->hasFile('image')) {
                     $image_tmp = Input::file('image');
                     if ($image_tmp->isValid()) {
                         //echo "test";die;
                        $extention = $image_tmp->getClientOriginalExtension();
                        $filename = rand(111,99999).'.'.$extention; 
                        $large_image_path = 'images/backend_images/products/large/'.$filename;
                        $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                        $small_image_path = 'images/backend_images/products/small/'.$filename;
                          //Resize Image
                        Image::make($image_tmp)->resize(1200,1200)->save($large_image_path);
                        Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                        Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                       
                     }
                 }else{
                    $filename = $data['current_image'];
                 }

                 if(empty($data['description'])){
                    $data['description']='';
                 }
                   if(empty($data['care'])){
                    $data['care']='';
                 }
                if(empty($data['status'])){
                  $status=1;
                 }else{
                  $status=0;
                 }
           // dd($data);
            product::where(['id'=>$id])->update(['category_id'=>$data['category_id'],'product_name'=>$data['product_name'],'product_code'=>$data['product_code'],'product_color'=>$data['product_color'],'description'=>$data['description'],'care'=>$data['care'],'price'=>$data['price'],'image'=>$filename,'status'=>$status]);
            return redirect('admin/view-products')->with('flash_message_success','Product has been updated Successfully!');
        }
         $productDetails = Product::where(['id'=>$id])->first();
         
          //Category dropdown start
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";
        foreach($categories as $cat) {
            if($cat->id==$productDetails->category_id){
                $selected = 'selected';
            }else{
                $selected = ''; 
            }
            $categories_dropdown .="<option value=' ".$cat->id."'".$selected.">".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                 if($sub_cat->id==$productDetails->category_id){
                $selected = 'selected';
            }else{
                $selected = ''; 
            }
                $categories_dropdown .= "<option value ='".$sub_cat->id."'".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
          //Category dropdown end


         return view('admin.products.edit_product')->with(compact('productDetails','categories_dropdown'));
         
          
    }

    public function deleteProductImage(Request $request, $id=null ){
      //Get product image name
      $productImage = Product::where(['id'=>$id])->first();
      //echo $productImage->image;

      // get product image path
       $large_image_path = 'images/backend_images/products/large/';
       $medium_image_path = 'images/backend_images/products/medium/';
       $small_image_path = 'images/backend_images/products/small/';
       //echo $large_image_path;
       //Delete  large image if not exist in folder
       if (file_exists($large_image_path.$productImage->image)) {
      // echo $large_image_path.$productImage->image;die;
           unlink($large_image_path.$productImage->image);
       }

        //Delete  large image if not exist in folder
       if (file_exists($medium_image_path.$productImage->image)) {
           unlink($medium_image_path.$productImage->image);
       }

        //Delete  large image if not exist in folder
       if (file_exists($small_image_path.$productImage->image)) {
           unlink($small_image_path.$productImage->image);
       }
         //delete image from product table
        Product::where(['id'=>$id])->update(['image'=>'']);
       return redirect()->back()->with('flash_message_success','Product Image has been deleted Successfully');
        //return view('admin.products.delete_product');
    }
    public function deleteAltImage(Request $request, $id=null ){
      //Get product image name
      $productImage = ProductsImage::where(['id'=>$id])->first();
      //echo $productImage->image;

      // get product image path
       $large_image_path = 'images/backend_images/products/large/';
       $medium_image_path = 'images/backend_images/products/medium/';
       $small_image_path = 'images/backend_images/products/small/';
       //echo $large_image_path;
       //Delete  large image if not exist in folder
       if (file_exists($large_image_path.$productImage->image)) {
      // echo $large_image_path.$productImage->image;die;
           unlink($large_image_path.$productImage->image);
       }

        //Delete  large image if not exist in folder
       if (file_exists($medium_image_path.$productImage->image)) {
           unlink($medium_image_path.$productImage->image);
       }

        //Delete  large image if not exist in folder
       if (file_exists($small_image_path.$productImage->image)) {
           unlink($small_image_path.$productImage->image);
       }
         //delete image from product table
        ProductsImage::where(['id'=>$id])->delete();
       return redirect()->back()->with('flash_message_success','Product Alternate Image(s) has been deleted Successfully');
        //return view('admin.products.delete_product');
    }


    public function viewproduct(Request $request){
        $products = Product::orderBy('id','DESC')->get();
        foreach ($products as $key=>$val){
            $category_name = Category::where(['id'=>$val->category_id])->first();
            $products[$key]->category_name = $category_name->name;
        }
       // dd($products);
        return view('admin/products/view_products')->with(compact('products'));

    }
    public function deleteproduct($id = null){
      Product::where(['id'=>$id])->delete();
      return redirect()->back()->with('flash_message_success','Product  has been deleted Successfully');

    }
    public function addAttribute(Request $request , $id = null){
      $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
      //$productDetails = json_decode(json_encode($productDetails));
      // echo "<pre>"; print_r($productDetails);die;
      if ($request->isMethod('post')) {
        $data = $request->all();
        //echo "<pre>"; print_r($data);die;
        foreach ($data['sku'] as $key => $value) {
          if (!empty($value)) {
            //SKU Check
            $attrCountSKU = ProductsAttribute::where('sku',$value)->count();
            if ($attrCountSKU>0) {
              return redirect ('admin/add-attributes/'.$id)->with('flash_message_error','SKU already exists! Please add another SKU.');
            }
             //SKU duplicate  size Check
            $attrCountSize = ProductsAttribute::where(['product_id'=>$id,'size'=>$data['size'][$key]])->count();
          
            
            if ($attrCountSize>0) {
              return redirect ('admin/add-attributes/'.$id)->with('flash_message_error','"'.$data['size'][$key].'" Size already exists! Please add another Size.');
            }
            $attribute = new ProductsAttribute;
            $attribute->product_id = $id;
            $attribute->sku = $value;
            $attribute->size = $data['size'][$key];
            $attribute->price = $data['price'][$key];
            $attribute->stock = $data['stock'][$key];
            $attribute->save();
          }
        }
        return redirect('admin/add-attributes/'.$id)->with('flash_message_success','Attributes has been added successfully !');
      }
      return view('admin.products.add_attributes')->with(compact('productDetails'));
    }
      public function editAttribute(Request $request , $id = null){
      if ($request->isMethod('post')) {
        $data = $request->all();
        //echo "<pre>"; print_r($data);die;
        foreach ($data['idAttr'] as $key => $attr) {
          productsAttribute::where(['id'=>$data['idAttr'][$key]])->update(['price'=>$data['price'][$key],'stock'=>$data['stock'][$key]]);
        }
        return redirect()->back()->with('flash_message_success','Product Attributes has been updated successfully !');
      }
    }

    public function addImages(Request $request , $id = null){
         $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
          //$productDetails = json_decode(json_encode($productDetails));
          // echo "<pre>"; print_r($productDetails);die;
      if ($request->isMethod('post')) {
        //add images
        $data = $request->all();
        //echo "<pre>";print_r($data);die;
        if($request->hasFile('image')){
          $files = $request->file('image');
          foreach ($files as $file) {
             //echo "<pre>";print_r($files);die;
          //upload image after resize
              $image = new ProductsImage;
              $extention = $file->getClientOriginalExtension();
              $filename = rand(111,99999).'.'.$extention;
              $large_image_path = 'images/backend_images/products/large/'.$filename;
              $medium_image_path = 'images/backend_images/products/medium/'.$filename;
              $small_image_path = 'images/backend_images/products/small/'.$filename;
                              //Resize Image
              Image::make($file)->save($large_image_path);
              Image::make($file)->resize(600,600)->save($medium_image_path);
              Image::make($file)->resize(300,300)->save($small_image_path);
              $image->image = $filename;
              $image->product_id = $data['product_id'];
              $image->save();
          
          }
         
        }
        
       return redirect('admin/add-images/'.$id)->with('flash_message_success','Product Image has been added successfully !');
      }
     
        $productsImg = ProductsImage::where(['product_id'=>$id])->get();
        $productsImg=json_decode(json_encode($productsImg));
       
        $productsImages = "";
      foreach ($productsImg as $image) {
            $productsImages .="<tr>
            <td>".$image->id."</td>
            <td>".$image->product_id."</td>
            <td><img src='/images/backend_images/products/small/$image->image' style= height:100px;></td>
            <td> <a rel='$image->id' rel1 = 'delete-alt-image'  href = 'javascript:' class='btn btn-danger btn-mini deleterecord' title='delete product Image'>Delete</a></td>
            </tr> ";
      }
      //echo "<pre>";print_r($productsimages);die;
      return view('admin.products.add_images')->with(compact('productDetails','productsImages'));
    }
    public function deleteattribute($id = null){
      productsAttribute::where(['id'=>$id])->delete();
      return redirect()->back()->with('flash_message_success',"Attribute has been deleted succesfully !");
    } 
   


    public function products($url=null){
      //Show 404 pages if Category URL does not exit
      $countCategory = Category::where(['url'=>$url,'status'=>1])->count();
      //echo $countCategory;die;
      if($countCategory==0){
        abort(404);
      }
      //echo $url;
      //Get all categories and subcategories
       $categories = Category::with('categories')->where(['parent_id'=>0])->get();
     
      $categoryDetails = Category::where(['url'=>$url])->first();
      //echo $categoryDetails->id;  4
    if($categoryDetails->parent_id==0){
    //If url is main category url
     $subCategories = Category::where(['parent_id'=>$categoryDetails->id])->get();//array form
    //echo $subCategories->id;
   //echo "<pre>";print_r($subCategories);die;
   
     foreach($subCategories as $key=>$subcat){

      $cat_ids[]= $subcat->id;// array form
     }
   // echo "<pre>"; print_r($cat_ids);die;
     $productsAll = Product::whereIn('category_id',$cat_ids)->where('status',1)->get();
     
      // $productsAll = json_decode(json_encode($productsAll));
    // echo "<pre>"; print_r($productsAll);die;
      }else{
        //if url is sub category url
       $productsAll = Product::where(['category_id'=> $categoryDetails->id])->where('status',1)->get();
      }
      $products = Product::all();
      // echo $productsAll;
     return view('products.listing')->with(compact('categoryDetails','productsAll','categories','products'));

    }
    


    public function product($id=null){
      //show 404 page if product is disabled
      $productsCount = product::where(['id'=>$id,'status'=>1])->count();
      if ($productsCount==0) {
         abort(404);
       } 
      //getting productDetails
          $productDetails = Product::with('attributes')->where('id',$id)->first();
          $productDetails = json_decode(json_encode($productDetails));
   // echo "<pre>"; print_r($productDetails);die;
        $relatedProducts = product::where('id', '!=',$id)->where(['category_id'=>$productDetails->category_id])->get();
        //$relatedProducts =json_decode(json_encode($relatedProducts));
        // foreach ($relatedProducts->chunk(3) as $chunk) {
        //   foreach ($chunk as $item) {
        //     echo $item;echo "br";
        //   }
        //   echo "<br><br><br>";
        // }
        // die;
          //echo "<pre>"; print_r($relatedProducts);die; 
         //Get all categories and subcategories
       $categories = Category::with('categories')->where(['parent_id'=>0])->get();
         //Get product Alternative image
       $productAltImages = ProductsImage::where('product_id',$id)->get();
        //$productAltImages = json_decode(json_encode($productAltImages));
       // echo "<pre>";print_r($productAltImages);die;   
      //get product Details

      $productDetails = Product::where('id',$id)->first();

      $total_stock = productsAttribute::where('product_id',$id)->sum('stock'); 
      return view('products.detail')->with(compact('productDetails','categories','productAltImages','total_stock','relatedProducts'));//,['$product'=>$product]);
    }


    
    public function getproductprice(Request $request){
      $data = $request->all();
      //echo "<pre>";print_r($data);die;
      $proArr = explode("-",$data['idSize']);
     // echo $proArr[0];echo $proArr[1];die;
      $proAttr =ProductsAttribute::where(['product_id'=>$proArr[0],'size'=>$proArr[1]])->first();
      echo $proAttr->price;
      echo "#";
      echo $proAttr->stock;

    }
    public function addtocart(Request $request){
      $data = $request->all();
      //echo "<pre>";print_r($data);die;
       
        $session_id = Session::get('session_id');
        if(!isset($session_id)){        
            $session_id = str_random(40);
            Session::put('session_id',$session_id);
        }
      if(empty($data['user_email'])){
        $data['user_email']='';
      }
        $sizeArr = explode('-',$data['size']);
        $product_size = $sizeArr[1];
      

        $countProducts = DB::table('cart')->where(['product_id' => $data['product_id'],'product_color' => $data['product_color'],'size' => $data['size'],'session_id' => $session_id])->count();
        if($countProducts>0){
            return redirect()->back()->with('flash_message_error','Product already exist in Cart!');
        }else{
            $getSKU = ProductsAttribute::select('sku')->where(['product_id' => $data['product_id'], 'size' =>$sizeArr[1]])->first();
      
      DB::table('cart')->insert(['product_id'=>$data['product_id'],'product_name'=>$data['product_name'],'product_code'=>$getSKU->sku,'product_color'=>$data['product_color'],'size'=>$sizeArr[1],'price'=>$data['product_price'],'session_id'=>$session_id,'quantity'=>$data['quantity'],'user_email'=>$data['user_email']]);
      }
        return redirect('cart')->with('flash_message_success','Product has been added in Cart!');
    }
      public function cart(){       
        $session_id = Session::get('session_id');
        $userCart = DB::table('cart')->where(['session_id' => $session_id])->get();
        foreach($userCart as $key => $product){
            $productDetails = Product::where('id',$product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }
        /*echo "<pre>"; print_r($userCart); die;*/
        return view('products.cart')->with(compact('userCart'));
      
    }
    public function deleteCartProduct($id=null){
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        DB::table('cart')->where('id',$id)->delete();
        return redirect('cart')->with('flash_message_success','Product has been deleted in Cart!');
    }
     public function updateCartQuantity($id=null,$quantity=null){
        // Session::forget('CouponAmount');
        // Session::forget('CouponCode');
         $getCartDetails = DB::table('cart')->select('product_code','quantity')->where('id',$id)->first();
         $getAttributeStock = ProductsAttribute::where('sku',$getCartDetails->product_code)->first();
         $updated_quantity = $getCartDetails->quantity+$quantity;
         if($getAttributeStock->stock>=$updated_quantity){
            DB::table('cart')->where('id',$id)->increment('quantity',$quantity); 
            return redirect('cart')->with('flash_message_success','Product Quantity has been updated in Cart!');   
        }else{
            return redirect('cart')->with('flash_message_error','Required Product Quantity is not available!');    
        }
    }
                              

         
}