<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chef;
use App\Models\Cleaner;
use App\Models\Manager;
use App\Models\data;
use App\Models\Delivery_man;
use App\Models\Department;
use App\Models\Waiter;

use Image;

class HrController extends Controller
{
    public function employeeInsert(Request $request)
    {       
            $type=$request->type;
            $id=rand ( 10000 , 99999 );
            if($type == 'waiter'){
                $data=new Waiter();
                $data->waiter_id='W-'.'01'.date('hi').$id;
            
            
            } else if($type=='chef'){
                $data=new Chef();
                $data->chef_id='C-'.'02'.date('hi').$id;
            
              }
              else if($type=='delivery-men'){
                $data=new Delivery_man();
                $data->delivery_man_id='D-'.'03'.date('hi').$id;;
            
              }
              else if($type=='manager'){ 
                $data=new Manager();
                $data->manager_id='M-'.'04'.date('hi').$id;;
            
              }
              else if($type=='cleaner'){
                $data=new Cleaner();
                $data->cleaner_id='Cl-'.'05'.date('hi').$id;;
            
              }
        $image=$request->file('image');
        if($image){
        
            $extension = $image->getClientOriginalExtension();
    
            $imageName = rand(111,99999).'.'.$extension;
            $mediumimagePath=public_path('employee/medium/'.$imageName);
            $smallimagePath =public_path('employee/small/'.$imageName);

        Image::make($image)->resize(500,500)->save($mediumimagePath);
        Image::make($image)->resize(250,250)->save($smallimagePath);
        $data->image = $imageName;


        }
            $data->first_name=$request->first_name;
            $data->last_name=$request->last_name;
            $data->email=$request->email;
            $data->phone=$request->phone;
            $data->nid=$request->nid;
            $data->gender=$request->gender;
            $data->birth_date=$request->dob;
            $data->joining_date=$request->joining;
            $data->country=$request->country;
            $data->address=$request->address1;
            $data->city=$request->city;
            $data->state=$request->state;
            $data->zip_code=$request->zipCode;
            $data->salary=$request->salary;
            $data->save();
            return response()->json([
                'msg'=>'Employee Inserted Successfully'
               ]);
    

        
    }

    public function getEmployee($filter){
      if($filter=='waiter'){
        $data = Waiter::get()->toArray();
      }else if($filter =='chef'){
        $data = Chef::get()->toArray();
      }else if($filter =='manager'){
        $data = Manager::get()->toArray();
      }else if($filter =='delivery_men'){
        $data = Delivery_man::get()->toArray();
      }else if($filter =='cleaner'){
        $data = Cleaner::get()->toArray();
      }
      return response()->json($data);
    }

    public function departmentInsert(Request $request){
      $data=new Department();
      $data->name=$request->name;
      $data->description=$request->description;
      $data->save();
      return response()->json([
        'msg'=>'Department Inserted Successfully'
       ]);

    }
    public function getDepartment(){
      $data = Department::get()->toArray();
      return response()->json($data);
    }

}
