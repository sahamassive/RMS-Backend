<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\DeliveryAddress;
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
    public function customerOrder($customer_id){
        $id = DB::table('orders')
                    ->where('customer_id',$customer_id)
                    ->where('order_status','pending')
                    ->orWhere('order_status', 'running')
                    ->select('order_id')
                    ->get();
        $data = DB::table('orders')
                    ->join('order_details', 'orders.order_id', '=', 'order_details.order_id')
                    ->join('food', 'food.item_code', '=', 'order_details.item_code')
                    ->select('food.name', 'food.image', 'order_details.*', 'orders.*')
                    ->where('orders.customer_id', $customer_id)
                    ->get();
        return response()->json([
            'id' => $id,
            'data' => $data
        ]);
    }

    public function customerDeliveryAddress($customer_id){
        $data=DeliveryAddress::where('customer_id',$customer_id)->first();
        return response($data);
    }

    public function changeDeliveryAddress(Request $request, $customer_id){
        $data=DeliveryAddress::where('customer_id',$customer_id)->first();
        if(!$data){
            $data = new DeliveryAddress();
            $data->customer_id = $customer_id;
            $data->city = $request->city;
            $data->indication = $request->indication;
            $data->address = $request->address;
            if($data->save()){
                return response()->json([
                    'msg'=>'Successfull'
                ]);
            }
            else{
                return response()->json([
                    'msg'=>'Error Occurred'
                ]);
            }
        }
        else{
            $data->city = $request->city;
            $data->indication = $request->indication;
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
    }

}
