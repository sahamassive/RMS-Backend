<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use App\Models\Section;
use App\Models\Brand;
use Auth;
use Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FoodController extends Controller
{
    public function foods(){
        $food=DB::table('food')
                    ->join('sections','sections.id','food.section_id')
                    ->join('categories','categories.id','food.category_id')
                    ->join('brands','brands.id','food.brand_id')
                    ->select('food.*','sections.name as section_name','categories.category_name','brands.name as brand_name')
                    ->get();
        return response()->json($food);
    }

    public function quickfoods(){
        $foods = Food::get()->toArray();
        return response()->json($foods);
    }
    public function spFoods(){
        $foods = Food::where('status','1')->get()->toArray();
        return response()->json($foods);
    }
    public function foodByCategory($id){
        $foods = Food::where('category_id',$id)->get()->toArray();
        return response()->json($foods);
    }
    public function foodEdit($id){
        $food = DB::table('food')
                    ->join('sections', 'food.section_id', '=', 'sections.id')
                    ->join('categories', 'food.category_id', '=', 'categories.id')
                    ->join('brands', 'food.brand_id', '=', 'brands.id')
                    ->select('food.*', 'categories.category_name', 'sections.name as section_name', 'brands.name as brand_name')
                    ->where('food.id', $id)
                    ->first(); 
        return response()->json($food);
    } 
    
public function foodUpdate(Request $request, $id){
    $food = Food::findorfail($id);

    $food->section_id=$request->section_id;
    $food->category_id=$request->category_id;  
    $food->brand_id=$request->brand_id;
    $food->recipe_id=1;
    $food->food_review_id=1;
    $food->name=$request->name;
    $food->description=$request->description;
    $food->speciality=$request->speciality;
    $food->price=$request->price;
    $food->meta_title=$request->meta_title;
    $food->meta_description=$request->meta_description;
    $food->meta_keywords=$request->meta_keywords;

        //check image
        $image=$request->file('image');
        if($image){
            $extension = $image->getClientOriginalExtension();
            if(
                $extension == 'jpeg' || $extension == 'JPEG' ||
                $extension == 'jpg' || $extension == 'JPG' ||
                $extension == 'img' || $extension == 'IMG' ||
                $extension == 'png' || $extension == 'PNG'
            ){
                //image path
                $path1 = public_path('foods/large/' . $food->image);
                $path2 = public_path('foods/medium/' . $food->image);
                $path3 = public_path('foods/small/' . $food->image);
                if (File::exists($path1)) {
                    //delete prevoius image
                    @unlink($path1);	
                    @unlink($path2);	
                    @unlink($path3);	
                }
                //change image name
                $imageName = time() . "." . $extension;
                //store image
                $largeimagePath = public_path('foods/large/'.$imageName);
                $mediumimagePath = public_path('foods/medium/'.$imageName);
                $smallimagePath  = public_path('foods/small/'.$imageName);

                //customize image size
                Image::make($image)->resize(1000,1000)->save($largeimagePath);
                Image::make($image)->resize(500,500)->save($mediumimagePath);
                Image::make($image)->resize(250,250)->save($smallimagePath);

                $food->image = $imageName;
            }
            else{
                return response()->json([
                    //error message
                    'msg'=>'Your inserted file is not an image.'
                ]);
            }
        }
        if($food->update()){
            return response()->json([
                'msg'=>'Food Updated Successfully'
                ]);
        }
        else{
            return response()->json([
                'msg'=>'Error Occured'
                ]);
        }

}

    public function updateFoodStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data);die;
            if ($data['status']== 'Active') {
                $status = 0;
            }
            else{
                $status = 1;
            }
            Food::where('id',$data['product_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'product_id'=> $data['product_id']]);
        }
    }

    public function deleteFood($id){
        $product_image = Food::select('product_image')->where('id',$id)->first();
        $product_image_path = 'admin/images/products/';

        if(file_exists($product_image_path.$product_image->product_image)){
            unlink($product_image_path.$product_image->product_image);
        }
        Food::where('id',$id)->delete();
        $message  = "Food Delete Successfully Done";
        return redirect()->back()->with("success",$message);
    }
    public function deleteFoodVideo($id){
        $productVideo = Food::select('product_video')->where('id',$id)->first();

        // Get Video Path
        $videoPath = 'admin/videos/product_video/';
        // Delete product video if exist product_video folder
        if(file_exists($videoPath.$productVideo->product_video)){
            unlink($videoPath.$productVideo->product_video);
        }
        // Delete ProductVideo from products table
        Food::where('id',$id)->update(['product_video'=>'']);
        $message  = "Product Video has been Delete Successfully";
        return redirect()->back()->with("success",$message);
    }
    public function deleteFoodImage($id){
        //dd($id);
        // Get product image name
        $productImage = Food::select('product_image')->where('id',$id)->first();
        //dd($productImage);

        // Get Product Image Path
        $largeimagePath = 'admin/images/product_images/large/';
        $mediumimagePath = 'admin/images/product_images/medium/';
        $smallimagePath = 'admin/images/product_images/small/';

        // Delete Product Small Image if exists in small folder
        if(file_exists($smallimagePath.$productImage->product_image)){
            unlink($smallimagePath.$productImage->product_image);
        }
        // Delete Product Medium Image if exists in small folder
        if(file_exists($mediumimagePath.$productImage->product_image)){
            unlink($mediumimagePath.$productImage->product_image);
        }
        // Delete Product Large Image if exists in small folder
        if(file_exists($largeimagePath.$productImage->product_image)){
            unlink($largeimagePath.$productImage->product_image);
        }

        // Delete Productimage from products table
        Food::where('id',$id)->update(['product_image'=>'']);

        $message  = "Product Image has been Delete Successfully";
        return redirect()->back()->with("success",$message);
    }

    public function add_edit_food(Request $request, $id=null){
        //Session::put('page','products');
        if($id==''){
            $title = 'Add Product';
            $food = new Food();
            $getFood = array();
            $message = "Food Added Successfully";
        }
        else{
            $title = 'Edit Product';
            $food = Food::findorFail($id);
            //dd($product);
            //$getCategories = Product::with('subcategories')->where(['parent_id'=>0,'section_id'=>$product['section_id']])->get()->toArray();
            //dd($getCategories);
            $message = "Food Updated Successfully";
        }
         if($request->isMethod('post')){
             $data = $request->all();
             // echo "<pre>"; print_r(Auth::guard('admin')->user()); die;

              $rules = [ 
                  'category_id'=>'required',
                  'product_name'=>'required|regex:/^[\pL\s\-]+$/u',
                  'product_code'=>'required|regex:/^\w+$/',
                  'product_price'=>'required|numeric',
                  'product_color'=>'required|regex:/^[\pL\s\-]+$/u',
                 
                 ];
                 $this->validate($request,$rules);

                 if($request->hasFile('product_image')){
                 $image_temp = $request->file('product_image');
                     if($image_temp->isValid()){
                        //Get Image Extension
                        $extension = $image_temp->getClientOriginalExtension();
                        //Generate New Image Name
                        $imageName = rand(111,99999).'.'.$extension;
                        $largeimagePath = 'admin/images/product_images/large/'.$imageName;
                        $mediumimagePath = 'admin/images/product_images/medium/'.$imageName;
                        $smallimagePath = 'admin/images/product_images/small/'.$imageName;

                     Image::make($image_temp)->resize(1000,1000)->save($largeimagePath);
                     Image::make($image_temp)->resize(500,500)->save($mediumimagePath);
                     Image::make($image_temp)->resize(250,250)->save($smallimagePath);
                     $product->product_image = $imageName;
                    }
                }
                //Upload Product Video
                if($request->hasFile('product_video')){
                    $video_temp = $request->file('product_video');
                    if($video_temp->isValid()){
                        //Upload Video in video folder
                        //$video_name = $video_temp->getClientOriginalName();
                        $extension = $video_temp->getClientOriginalExtension();
                        //$videoName = $video_name.'-'.rand(111,99999).'.'.$extension;
                        $videoName = rand(111,99999).'.'.$extension;
                        $videoPath = 'admin/videos/product_video/';
                        $video_temp->move($videoPath,$videoName);
                        // Insert Video in product table
                        $product->product_video = $videoName;

                    }
                }
        
            $categoryDetails = Category::find($data['category_id']);
            //dd($categoryDetails);
                $product->section_id = $categoryDetails['section_id'];
                $product->category_id = $data['category_id'];
                $product->brand_id = $data['brand_id'];
                
                
                $adminType = Auth::guard('admin')->user()->type;
                $vendor_id = Auth::guard('admin')->user()->vendor_id;
                $admin_id = Auth::guard('admin')->user()->id;

                $product->admin_type = $adminType;
                $product->admin_id = $admin_id;
                if($adminType=='vendor'){
                    $product->vendor_id = $vendor_id;
                }else{
                    $product->vendor_id = 0;
                }

                $product->product_name = $data['product_name'];
                $product->product_code = $data['product_code'];
                $product->product_price = $data['product_price'];
                $product->product_color = $data['product_color'];
                $product->product_discount = $data['product_discount'];
                $product->product_weight = $data['product_weight'];
                $product->description = $data['description'];
                $product->meta_title = $data['meta_title'];
                $product->meta_description = $data['meta_description'];
                $product->meta_keywords = $data['meta_keywords'];
                if(!empty($data['is_featured'])){
                    $product->is_featured = $data['is_featured'];
                }else{
                    $product->is_featured = "No";
                }
                $product->status = 1;
                $product->save();

            return redirect('admin/products')->with('success',$message);
        }
        //Get sections with categories & subcategories
        $categories = Section::with('category')->get()->toArray();
        $brands = Brand::where('status',1)->get()->toArray();
        //dd($product['id']);
        //$categories = Category::get()->all();
        
        return view('admin.products.add-edit-product')->with(compact('title','categories','brands','product'));
    }

    public function addAttributes($id){
        $food = Food::findorFail($id);
        //dd($food);
        return view('admin.attributes.add_edit_attributes')->with(compact('food'));
    }

    public function foodInsert(Request $request){
        $food=new Food();
        
        $food->section_id=$request->section_id;
        $food->category_id=$request->category_id;  
        $food->brand_id=$request->brand_id;
        $food->recipe_id=1;
        $food->food_review_id=1;
        $food->name=$request->name;
        $food->description=$request->description;
        $food->speciality=$request->speciality;
        $food->price=$request->price;
        $food->meta_title=$request->meta_title;
        $food->meta_description=$request->meta_description;
        $food->meta_keywords=$request->meta_keywords;
        $image=$request->file('image');
        if($image){
            // $name_gen = hexdec(uniqid());
            // $img_ext = strtolower($image->getClientOriginalExtension());
            // $img_name = $name_gen . "." . $img_ext;
            // $up_location = 'foods/';
            // $image_up = $up_location . $img_name;
            // $image->move($up_location, $img_name);
            // $food->image=$image_up;        

            $extension = $image->getClientOriginalExtension();
            //Generate New Image Name
            $imageName = rand(111,99999).'.'.$extension;
            $largeimagePath =public_path('foods/large/'.$imageName);
            $mediumimagePath=public_path('foods/medium/'.$imageName);
            $smallimagePath =public_path('foods/small/'.$imageName);

            Image::make($image)->resize(1000,1000)->save($largeimagePath);
            Image::make($image)->resize(500,500)->save($mediumimagePath);
            Image::make($image)->resize(250,250)->save($smallimagePath);
            $food->image = $imageName;


        }
        $food->save();
        return response()->json([
            'msg'=>'Food Inserted Successfully'
            ]);

        }
    }
