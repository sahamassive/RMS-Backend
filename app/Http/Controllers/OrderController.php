<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    
    public function orderInsert(Request $request){
        $detail=$request->details;
        $id=rand ( 10000 , 99999 );
        $orderId='01'.date('hi').$id;
        $order=new Order();
        $order->order_id=$orderId; 
        $order->restaurant_id=$request->resturant_id;
        $order->branch_id=$request->branch_id;
        $order->customer_id=10;
        $order->item=$request->item;
        $order->total_price=$request->total;
        $order->vat=$request->vat;
        $order->grand_price=$request->grand_price;
        $order->pickup_method=$request->pickup_method;
        $order->save();

        for ($i = 0; $i < count($detail); $i++) {

            $orderDetail = new OrderDetail();
            $orderDetail->order_id=$orderId; 
            $orderDetail->food_id =$detail[$i][0]['food_id'];
            $orderDetail->quantity = $detail[$i][0]['qty'];
            $orderDetail->save();
        }
        return response()->json([
            'msg'=>'Order Submitted'
           ]);
    }
}
