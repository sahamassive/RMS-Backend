<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    //get all coupons
    public function index(){
        //fetch from db
        $coupon = Coupon::all();
        return response()->json($coupon);
    }
    //insert new coupon
    public function couponInsert(Request $request){
        //create new coupon object
        $data = new Coupon();

        $data->coupon_id = uniqid();
        $data->restaurant_id = $request->restaurant_id;
        $data->branch_id = $request->branch_id;
        $data->coupon_code = $request->coupon_code;
        $data->discount_amount = $request->discount_amount;
        $data->quantity = $request->quantity;
        $data->starting_date = $request->starting_date;
        $data->starting_time = $request->starting_time;
        $data->ending_date = $request->ending_date;
        $data->ending_time = $request->ending_time;

        if($data->save()){
            return response()->json([
                //success response
                'msg' => 'Coupon Added successfully'
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
    public function couponStatus($id){
        $data = Coupon::where('id',$id)->first();
        if($data->status==1){
            $data->status=0;
            $data->update();
            return response()->json([
                'msg'=>'Coupon Paused Successfully'
            ]);
        }
        else{
            $data->status=1;
            $data->update();
            return response()->json([
                'msg'=>'Coupon Activated Successfully'
            ]); 
        }
    }

    //edit coupon info
    public function editCoupon($id){
        //fetach from db
        $data = Coupon::where('id',$id)->first();
        return response()->json($data);
    }

    //update coupon info
    public function updateCoupon(Request $request ,$id){
        //fetach from db
        $data = Coupon::where('id',$id)->first();

        $data->coupon_code = $request->coupon_code;
        $data->discount_amount = $request->discount_amount;
        $data->quantity = $request->quantity;
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
