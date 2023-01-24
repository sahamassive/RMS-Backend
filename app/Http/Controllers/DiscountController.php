<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    //view all discount
    public function index(){
        //fetch data from database
        $data = DB::table('discounts')
                    ->join('food', 'discounts.food_id', '=', 'food.id')
                    ->select('discounts.*', 'food.name','food.image')
                    ->orderBy("discounts.created_at", "DESC")
                    ->get();
        return response()->json($data);
    }
    //insert new discount
    public function discountInsert(Request $request){
        //create new discount object
        $data = new Discount();

        $data->discount_id = uniqid();
        $data->food_id = $request->food_id;
        $data->restaurant_id = $request->restaurant_id;
        $data->branch_id = $request->branch_id;
        $data->discount = $request->discount;
        $data->starting_date = $request->starting_date;
        $data->starting_time = $request->starting_time;
        $data->ending_date = $request->ending_date;
        $data->ending_time = $request->ending_time;

        if($data->save()){
            return response()->json([
                //success response
                'msg' => 'Discount activated successfully'
            ]);
        }
        else{
            return response()->json([
                //error response
                'msg' => 'Error Ouucued'
            ]);
        }
    }

    //status update
    public function discountStatus($id){
        $data = Discount::where('id',$id)->first();
        if($data->status==1){
            $data->status=0;
            $data->update();
            return response()->json([
                'msg'=>'Discoused Paused'
            ]);
        }
        else{
            $data->status=1;
            $data->update();
            return response()->json([
                'msg'=>'Discount Activated Successfully'
            ]); 
        }
    }

    //edit discount info
    public function editDiscount($id){
        $discount = Discount::where('id',$id)->first();
        return response()->json($discount);
    }

    //update discount info
    public function updateDiscount(Request $request, $id){
        $data = Discount::findorfail($id);

        $data->food_id = $request->food_id;
        $data->discount = $request->discount;
        $data->starting_date = $request->starting_date;
        $data->starting_time = $request->starting_time;
        $data->ending_date = $request->ending_date;
        $data->ending_time = $request->ending_time;

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
