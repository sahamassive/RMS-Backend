<?php

// namespace App\Http\Controllers\Food;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

// class CategoryController extends Controller
// {
//     //
// }

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Section;
use Image;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function categories(){
        Session::put('page','categories');
        $categories = Category::with(['section','parentcategory'])->get()->toArray();
        return view('admin.categories.categories')->with(compact('categories')); 
    }

    public function updateCategoryStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data);die;
            if ($data['status']== 'Active') {
                $status = 0;
            }
            else{
                $status = 1;
            }
            Category::where('id',$data['category_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'category_id'=> $data['category_id']]);
        }
    }

    public function deleteCategory($id){
        $category_image = Category::select('category_image')->where('id',$id)->first();
        $category_image_path = 'admin/images/categories/';

        if(file_exists($category_image_path.$category_image->category_image)){
            unlink($category_image_path.$category_image->category_image);
        }
        Category::where('id',$id)->delete();
        $message  = "Category Delete Successfully Done";
        return redirect()->back()->with("success",$message);
    }

    public function add_edit_category(Request $request,$id = null ){
        Session::put('page','categories');
        if($id==''){
            $title = "Add Category";
            $category = new Category();
            $getCategories = array();
            $message = "Category Added Successfully";
        }
        else{
            $title = "Edit Category";
            $category = Category::findorFail($id);
            $getCategories = Category::with('subcategories')->where(['parent_id'=>0,'section_id'=>$category['section_id']])->get()->toArray();
            //dd($getCategories);
            $message = "Category Updated Successfully";
        }
        if($request->isMethod('post')){
            $data = $request->all();

             $rules = [
                'category_name'=>'required|regex:/^[\pL\s\-]+$/u',
                'section_id'=>'required',
                'url'=>'required',
                'category_image'=>'image',
                ];
                $this->validate($request,$rules);

                if($request->hasFile('category_image')){
                $image_temp = $request->file('category_image');
                if($image_temp->isValid()){
                    //Get Image Extension
                    $extension = $image_temp->getClientOriginalExtension();
                    //Generate New Image Name
                    $imageName = rand(111,99999).'.'.$extension;
                    $imagePath = 'admin/images/categories/'.$imageName;
                    Image::make($image_temp)->save($imagePath);
                    if(!empty($data['current_category_image'])){
                        unlink('admin/images/categories/'.$data['current_category_image']);
                    }
                }
                
            }elseif (!empty($data['current_category_image'])) {
                $imageName = $data['current_category_image'];
            }
            else{
                $imageName = "";
            }
                if($request->category_discount == ''){
                    $category_discount = 0;
                }
                else{
                    $category_discount = $request->category_discount;
                }
                $category->category_name = $request->category_name;
                $category->section_id = $request->section_id;
                $category->parent_id = $request->parent_id;
                $category->category_image = $imageName;
                $category->category_discount = $category_discount;
                $category->description = $request->description;
                $category->url = $request->url;
                $category->meta_title = $request->meta_title;
                $category->meta_description = $request->meta_description;
                $category->meta_keywords = $request->meta_keywords;
                $category->status = 1;
                $category->save();

            return redirect('admin/categories')->with('success',$message);
        }
        $sections = Section::get()->all();
        //$categories = Category::get()->all();
        
        return view('admin.categories.add-edit-category')->with(compact('title','category','sections','getCategories'));
    }

    public function appendCategoryLevel(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $getCategories = Category::with('subcategories')->where(['parent_id'=>0,'section_id'=>$data['section_id']])->get()->toArray();
            //dd($getCategories);

            return view('admin.categories.append_categories_level')->with(compact('getCategories'));
        }
    }
}
