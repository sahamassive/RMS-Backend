<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    //insert new customer
    public function customerInsert(Request $request){
        $data = new Customer();
        $data->restaurant_id = $request->restaurant_id;
        $data->customer_id = 'C-' . uniqid();
        $data->name = $request->name;
        $data->phone = $request->phone;
        $data->password = Hash::make($request->password);
        
        if($data->save()){
            return response()->json([
                'msg'=>'Successfully Inserted'
            ]);
        }
        else{
            return response()->json([
                'msg'=>'Error Occured'
            ]);
        }
    }

    //view customer order information
    public function customerOrder($emp_id){
        $id = DB::table('orders')
                    ->where('customer_id',$emp_id)
                    ->where('order_status','pending')
                    ->orWhere('order_status', 'running')
                    ->select('order_id')
                    ->get();
        $data = DB::table('order_details')
                    ->join('orders', 'orders.order_id', '=', 'order_details.order_id')
                    ->join('food', 'food.item_code', '=', 'order_details.item_code')
                    ->where('orders.customer_id', $emp_id)
                    ->where('order_details.status', 'pending')
                    ->orWhere('order_details.status', 'running')
                    ->select('food.name', 'food.image', 'order_details.*', 'orders.*')
                    ->get();
        return response()->json([
            'id' => $id,
            'data' => $data
        ]);
    }
}
