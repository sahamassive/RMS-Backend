<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\File;


class BrandController extends Controller
{
    public function brand(){
        $brands = Brand::get()->toArray();
    
       
        return response()->json($brands);
    }

    public function updateBrandStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data);die;
            if ($data['status']== 'Active') {
                $status = 0;
            }
            else{
                $status = 1;
            }
            Brand::where('id',$data['brand_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'brand_id'=> $data['brand_id']]);
        }
    }

    public function deleteBrand($id){
        Brand::where('id',$id)->delete();
        $message  = "Brand Delete Successfully Done";
        return ($message);
    }
    
    public function add_edit_brand(Request $request,$id = null ){
        Session::put('page','brand');
        if($id==''){
            $title = "Add brand";
            $brand = new Brand();
            $message = "Brand Added Successfully";
        }
        else{
            $title = "Edit Brand";
            $brand = Brand::findorFail($id);
            $message = "Brand Updated Successfully";
        }
        if($request->isMethod('post')){
            $data = $request->all();

             $rules = [
                'brand_name'=>'required|regex:/^[\pL\s\-]+$/u',
                ];
                $this->validate($request,$rules);

                $brand->name = $request->brand_name;
                $brand->status = 1;
                $brand->save();

            return ($message);
        }
        
        return (compact('title','brand'));
    }
    public function brandInsert(Request $request){
            $brand=new Brand();
            $brand->name = $request->name;
            $brand->status = 0;
            $image=$request->file('image');
            if($image){
                $name_gen = hexdec(uniqid());
                $img_ext = strtolower($image->getClientOriginalExtension());
                $img_name = $name_gen . "." . $img_ext;
                $up_location = 'brand/';
                $image_up = $up_location . $img_name;
                $image->move($up_location, $img_name);
                $brand->logo=$image_up;

            }
            $brand->save();
            return response()->json([
                'msg'=>'Brand Inserted Successfully'
               ]);
           
    }
    public function brandEdit($id){
        $data=Brand::findOrfail($id);
        return response()->json($data);
    }
    public function brandUpdate(Request $request,$id){
        $brand=Brand::findOrfail($id);
        $brand->name=$request->name;
        $image=$request->file('image');
        if($image){
            $name_gen = hexdec(uniqid());
            $img_ext = strtolower($image->getClientOriginalExtension());
            $img_name = $name_gen . "." . $img_ext;
            $up_location = 'brand/';
            $image_up = $up_location . $img_name;
            $image->move($up_location, $img_name);
            $path = public_path('/' . $brand->logo);
            if (File::exists($path)) {
                @unlink($path);
            }
            $brand->logo=$image_up;
        }
        $brand->update();
        return response()->json([
            'msg'=>'Brand Updated Successfully'
           ]);
    }
    public function brandStatus($id){
        $data=Brand::where('id',$id)->first();
        if($data->status==1){
            $data->status=0;
            $data->update();
            return response()->json([
                'msg'=>'Section Status Update'
               ]);
        }else{
            $data->status=1;
            $data->update();
            return response()->json([
                'msg'=>'Section Status Update'
               ]); 
        }
    }

}
