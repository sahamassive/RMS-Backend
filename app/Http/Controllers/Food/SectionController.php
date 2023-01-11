<?php

// namespace App\Http\Controllers\Food;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

// class SectionController extends Controller
// {
//     //
// }


namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Session;

class SectionController extends Controller
{
    public function sections(){
        //Session::put('page','section');
        $sections = Section::get()->toArray();
         return response()->json($sections);
    }

    public function updateSectionStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data);die;
            if ($data['status'] == 'Active') {
                $status = 0;
            }
            else{
                $status = 1;
            }
            Section::where('id',$data['section_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'section_id'=> $data['section_id']]);
        }
    }

    public function deleteSection($id){
        Section::where('id',$id)->delete();
        $message  = "Section Delete Successfully Done";
        return redirect()->back()->with("success",$message);
    }

    public function add_edit_section(Request $request,$id = null ){
        //Session::put('page','section');
        if($id==''){
            $title = "Add Section";
            $section = new Section();
            $message = "Section Added Successfully";
        }
        else{
            $title = "Edit Section";
            $section = Section::findorFail($id);
            $message = "Section Updated Successfully";
        }
        if($request->isMethod('post')){
            $data = $request->all();

             $rules = [
                'section_name'=>'required|regex:/^[\pL\s\-]+$/u',
                ];
                $this->validate($request,$rules);

                $section->name = $request->section_name;
                $section->status = 1;
                $section->save();

            return redirect('admin/sections')->with('success',$message);
        }

        
        
        return view('admin.sections.add-edit-section')->with(compact('title','section'));
    }
    public function sectionInsert(Request $request){

  
           $section=new Section();
           $section->name = $request->section_name;
           $section->status = 0;
           $section->description = 1;
           $section->save();

       return response()->json([
        'msg'=>'Section Inserted Successfully'
       ]);
    }
    public function sectionEdit($id){
        $data=Section::findOrfail($id);
        return response()->json($data);
    }
    public function sectionUpdate(Request $request,$id){
        $section=Section::findOrfail($id);
        $section->name=$request->section_name;
        $section->update();
        return response()->json([
            'msg'=>'Section Updated Successfully'
           ]);
    }
    public function sectionStatus($id){
        $data=Section::where('id',$id)->first();
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

