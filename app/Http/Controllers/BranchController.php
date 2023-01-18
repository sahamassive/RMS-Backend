<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\BranchFood;
use App\Models\Restuarant;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function index(){
        $data = DB::table('branches')
            ->join('restuarants', 'branches.restaurant_id', '=', 'restuarants.restaurant_id')
            ->select('branches.*', 'restuarants.restaurant_name')
            ->get();
        return response()->json($data);
    }

    //insert new branch
    public function branchInsert(Request $request){
        $data = new Branch();

        $data->restaurant_id = $request->restaurant_id;
        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->city = $request->city;
        $data->address = $request->address;

        $data->save();

        if($data->save()){
            return response()->json([
                'msg'=>'Branch Inserted Successfully'
            ]);
        }
        else{
            return response()->json([
                'msg'=>'Error Occurred'
            ]);
        }
    }

    //branch status update
    public function branchStatus($id){
        $data = Branch::where('id',$id)->first();
        if($data->status==1){
            $data->status=0;
            $data->update();
            return response()->json([
                'msg'=>'Branch Status Updated'
            ]);
        }
        else{
            $data->status=1;
            $data->update();
            return response()->json([
                'msg'=>'Section Status Update'
            ]); 
        }
    }

    //edit branch
    public function editBranch($id){
        $branch = Branch::where('id',$id)->first();
        return response()->json($branch);
    }

    //update branch information
    public function updateBranch(Request $request, $id){
        $data = Branch::findorfail($id);

        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->city = $request->city;
        $data->address = $request->address;

        if($data->update()){
            return response()->json([
                //success message
                'msg'=>'Updated Successfully'
            ]);
        }
        else{
            return response()->json([
                //error message
                'msg'=>'Error Occurred'
            ]);
        }
    }

    public function branchFoodAdd(Request $request){
        $detail=$request->details;

        for ($i = 0; $i < count($detail); $i++) {
            $data=new BranchFood();
            $data->restaurant_id=$request->restaurant_id;
            $data->branch_id=$request->branch_id;

            $data->food_id=$detail[$i][0]['food_id'];
            $data->save();

        }
        return response()->json([
            //error message
            'msg'=>'Inserted successfully'
        ]);
    }
}
