<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function view(){
        $brands = Brand::get()->toArray();
        return($brands);
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
}
