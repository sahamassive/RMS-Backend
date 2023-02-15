<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PosOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    //all order list
    public function index(){
        //fetch data from database
        $id = Order::select('order_id')
                    ->orderByDesc('id')
                    ->get();
        $data = DB::table('orders')
                    ->join('order_details', 'orders.order_id', '=', 'order_details.order_id')
                    ->join('food', 'order_details.food_id', '=', 'food.id')
                    ->select('orders.*', 'food.*', 'order_details.*')
                    ->get();
        return response()->json([
            'data' => $data,
            'id' => $id
        ]);
    }

    //insert new order
    public function orderInsert(Request $request){
        $detail=$request->details;
        $extension = '0';
        for($i = 0; $i < $request->item - 1; $i++){
            $extension .= "0";
        }
        $id=rand ( 10000 , 99999 );
        if($request->pickup_method=='pos'){
            $orderId='Pos-'.date('hi').$id.'-'.$extension;
            $customerId='pos-order';
            $pos=new PosOrder();
            $pos->restaurant_id=$request->restaurant_id;
            $pos->order_id=$orderId;
            $pos->customer_name=$request->customer_name;
            $pos->customer_phone=$request->customer_phone;
            $pos->table_id=$request->table_id;
            $pos->waiter_id=$request->waiter_id;
            $pos->save();
        }else{
            $orderId='Cus-'.date('hi').$id.'-'.$extension;
            $customerId=10;
        }
        
        $order=new Order();
        $order->order_id=$orderId; 
        $order->restaurant_id=$request->restaurant_id;
        $order->branch_id=$request->branch_id;
        $order->customer_id=$customerId;
        $order->order_status = "pending";
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
            $orderDetail->item_code =$detail[$i][0]['item_code'];
            $orderDetail->quantity = $detail[$i][0]['qty'];
            $orderDetail->status = 'pending';
            $orderDetail->save();
        }
        return response()->json([
            'msg'=> 'Order Submitted',
        ]);
    }

    //get recent orders
    public function recentOrder(){
        //fetch data from database
        $id = OrderDetail::select('order_id')
                    ->where('status', 'pending')
                    ->whereDate('created_at', date("Y-m-d"))
                    ->get();
        $id=$id->unique('order_id');
        $data = DB::table('orders')
                    ->join('order_details','order_details.order_id', '=', 'orders.order_id')
                    ->join('food','order_details.food_id', '=', 'food.id')
                    ->where('order_details.status', "pending")
                    ->whereDate('orders.created_at', date("Y-m-d"))
                    ->select('orders.*', 'order_details.*', 'food.name', 'food.image', 'food.item_code')
                    ->get();
        return response()->json([
            'data' => $data,
            'id' => $id
        ]);
    }

    public function getMsp($id){
        $response= Http::get('https://mirpur-club.bein-mcl.com/api/get-msp/'.$id);
        $data=$response->json();
        if($data){
            return response()->json($data);
        }else{
            return response()->json('not a member');
        }
        }
}
