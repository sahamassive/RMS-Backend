<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\Chef;
use App\Models\Cleaner;
use App\Models\Customer;
use App\Models\Manager;
use App\Models\data;
use App\Models\Delivery_man;
use App\Models\Department;
use App\Models\Leave;
use App\Models\Waiter;
use Illuminate\Support\Facades\Hash;
use Image;

class HrController extends Controller
{
    public function employeeInsert(Request $request)
    {       
            $type=$request->type;
            $id=rand ( 10000 , 99999 );
            if($type == 'waiter'){
                $data=new Waiter();
                $data->emp_id='W-'.'01'.date('hi').$id;
            
            
            } else if($type=='chef'){
                $data=new Chef();
                $data->emp_id='C-'.'02'.date('hi').$id;
            
              }
              else if($type=='delivery-men'){
                $data=new Delivery_man();
                $data->emp_id='D-'.'03'.date('hi').$id;;
            
              }
              else if($type=='manager'){ 
                $data=new Manager();
                $data->emp_id='M-'.'04'.date('hi').$id;;
            
              }
              else if($type=='cleaner'){
                $data=new Cleaner();
                $data->emp_id='Cl-'.'05'.date('hi').$id;;
            
              }else if($type=="supr-admin" ||$type=='admin' || $type=='sub-admin'){
                $data=new Admin();
                $data->admin_type=$type;
                $data->emp_id='Ad-'.'11'.date('hi').$id;;
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
            $data->password=Hash::make($request->password);
            $data->address=$request->address1;
            $data->city=$request->city;
            $data->state=$request->state;
            $data->zip_code=$request->zipCode;
            $data->salary=$request->salary;
            $data->save();
            
            return response()->json([
                'msg'=> ucwords($type).' Inserted Successfully'
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

    public function leaveInsert(Request $request){
        $data=new Leave();
        $data->restaurant_id=$request->restaurant_id;
        $data->emp_id=$request->emp_id;
        $data->reason=$request->reason;
        $data->start_time=$request->start_time;
        $data->end_time=$request->end_time;

        $data->save();
        return response()->json([
          'msg'=>'Leave Inserted'
        ]);

    }

    //get user profile information 
    public function profileInfo($type, $emp_id){
      if($type=='waiter'){
        $data = Waiter::where('emp_id', $emp_id)->first();
      }
      else if($type =='chef'){
        $data = Chef::where('emp_id', $emp_id)->first();
      }
      else if($type =='manager'){
        $data = Manager::where('emp_id', $emp_id)->first();
      }
      else if($type =='delivery_men'){
        $data = Delivery_man::where('emp_id', $emp_id)->first();
      }
      else if($type =='cleaner'){
        $data = Cleaner::where('emp_id', $emp_id)->first();
      }
      else if($type =='customer'){
        $data = Customer::where('customer_id', $emp_id)->first();
      }
      return response()->json($data);
    }

}
