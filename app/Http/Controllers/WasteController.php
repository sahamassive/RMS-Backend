<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Waste;
use Illuminate\Support\Facades\DB;

class WasteController extends Controller
{
    //all waste
    public function allWaste(){
        //fetch data from database
        $data = DB::table('wastes')
                    ->join('food', 'wastes.food_id', '=', 'food.id')
                    ->select('wastes.*', 'food.name','food.image')
                    ->orderBy("wastes.created_at", "DESC")
                    ->get();
        return response()->json($data);
    }

    //insert new waste
    public function wasteInsert(Request $request){
        $data = new Waste();
        
        $data->waste_id = uniqid();
        $data->food_id = $request->food_id;
        $data->reason = $request->reason;
        $data->amount = $request->amount;
        $data->price = $request->price;
        $data->employee_type = $request->type;
        $data->employee_id = $request->employee_id;

        if($data->save()){
            return response()->json([
                'msg'=>'Waste Inserted Successfully'
            ]);
        }
        else{
            return response()->json([
                'msg'=>'Error Occurred'
            ]);
        }
    }

    //details function for waste
    public function wasteDetails($employee_id){
        //take sub string from employee id
        $check = substr($employee_id, 0, 2);
        //check employee type
        if($check == 'W-'){
            $table = "waiters";
        }
        else if($check == 'C-'){
            $table = "chefs";
        }
        else if($check == 'D-'){
            $table = "delivery_men";
        }
        else if($check == 'M-'){
            $table = "managers";
        }
        else if(substr($employee_id, 0, 3) == 'Cl-'){
            $table = "cleaners";
        }
        //db query
        $data = DB::table('wastes')
                    ->join('food', 'wastes.food_id', '=', 'food.id')
                    ->join($table, $table . '.emp_id', '=', 'wastes.employee_id')
                    ->select('wastes.*', 'food.name','food.image as food_image', $table . '.first_name', $table . '.last_name', $table . '.image')
                    ->where('wastes.employee_id', $employee_id)
                    ->orderBy("wastes.created_at", "DESC")	
                    ->get();
        return response()->json($data);
    }

    //get waste info for editing waste
    public function editWaste($id){
        //fetch data from database
        $waste = Waste::where('id',$id)->first();
        return response()->json($waste);
    }

    //update waste info after editing waste
    public function updateWaste(Request $request, $id){
        $data = Waste::findorfail($id);

        $data->food_id = $request->food_id;
        $data->reason = $request->reason;
        $data->amount = $request->amount;
        $data->price = $request->price;
        $data->employee_type = $request->type;
        $data->employee_id = $request->employee_id;

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
}
