<?php

namespace App\Http\Controllers;

use App\Models\Chef;
use App\Models\Chef_inventory;
use App\Models\Chef_order;
use App\Models\Inventory\Inventory;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\VarDumper\Dumper\esc;

class ChefController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //all chef list
        $data = Chef::where('restaurant_id',$id)->get();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chef  $chef
     * @return \Illuminate\Http\Response
     */
    public function show(Chef $chef)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chef  $chef
     * @return \Illuminate\Http\Response
     */
    public function edit(Chef $chef)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chef  $chef
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chef $chef)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chef  $chef
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chef $chef)
    {
        //
    }

    //take inventory items from inventory list
    public function ChefInventory($emp_id, $filter){
        $res = date("Y-m-d");
        if($filter == 'yesterday'){
            $res1 = date('Y-m-d', strtotime('-1 days'));
        }
        elseif($filter == 'week'){
            $res1 = date('Y-m-d', strtotime('-7 days'));
        }
        elseif($filter == 'month'){
            $res1 = date('Y-m-d', strtotime('-30 days'));
        }

        if($filter == 'today'){
            $data = DB::table('chef_inventories')
                        ->join('ingredients', 'ingredients.id', '=', 'chef_inventories.ingredient_id')
                        ->where('chef_inventories.emp_id',$emp_id)
                        ->whereDate('chef_inventories.created_at', $res)
                        ->select('chef_inventories.*', 'ingredients.ingredient')
                        ->orderBy('chef_inventories.created_at','DESC')
                        ->get();
        }
        else{
            $data = DB::table('chef_inventories')
                        ->join('ingredients', 'ingredients.id', '=', 'chef_inventories.ingredient_id')
                        ->where('chef_inventories.emp_id',$emp_id)
                        ->whereBetween('chef_inventories.created_at', [$res1, $res])
                        ->select(DB::raw("Sum(chef_inventories.quantity) as quantity"),
                                 DB::raw("Sum(chef_inventories.used_quantity ) as used_quantity"),
                                 DB::raw("Sum(chef_inventories.return_quantity ) as return_quantity"),
                                 'chef_inventories.ingredient_id', 'chef_inventories.unit', 'ingredients.ingredient')
                        ->groupBy('chef_inventories.ingredient_id')
                        ->get();
        }
        return response()->json($data);
    }

    //confirm items from order
    public function ChefOrder($emp_id, $order_id, $item_code, $quantity){
        $data = Chef_inventory::where("emp_id",$emp_id)->whereDate('created_at', date("Y-m-d"))->get();
        $item = Recipe::where('item_code', $item_code)->get();
        $order_details = OrderDetail::where('order_id', $order_id)->where('item_code', $item_code)->first();
        $msg = array();
        $status = false;
        $check = false;

        for($i=0; $i<count($item); $i++){
            for($j=0; $j<count($data); $j++){
                if($item[$i]->ingredient_id == $data[$j]->ingredient_id){
                    if(($item[$i]->ingredient_quantity * $quantity) > ($data[$j]->quantity - $data[$j]->used_quantity)){
                        $ingredient = Ingredient::where('id', $data[$j]->ingredient_id)->first();
                        $msg[] = ' ' . abs(($item[$i]->ingredient_quantity * $quantity) - ($data[$j]->quantity - $data[$j]->used_quantity)) . $data[$j]->unit . ' ' . $ingredient->ingredient;
                        $check = true;
                        break;
                    }
                    else{
                        $data[$j]->used_quantity = $data[$j]->used_quantity + ($item[$i]->ingredient_quantity * $quantity);
                        $check = true;
                        break;
                    }
                }
                $check = false;
            }
            if(!$check){
                $ingredient = Ingredient::where('id', $item[$i]->ingredient_id)->first();
                $msg[] = ' ' . abs($item[$i]->ingredient_quantity * $quantity) . ' ' . $ingredient->ingredient;
            }
        }

        if($msg){
            return response()->json($msg);
        }
        for($j=0; $j<count($data); $j++){
            if($data[$j]->update()){
                $status = true;
            }
            else{
                $status = false;
            }
        }

        if($status){
            $order_details->status = "running";
            if($order_details->update()){
                $chef = new Chef_order();
                $chef->emp_id = $emp_id;
                $chef->order_id = $order_id;
                $chef->kot = 'K-'.'01'.date('hi');
                $chef->status = 'running';
                $chef->quantity = $quantity;
                $chef->item_code = $item_code;

                if($chef->save()){
                    return response()->json([
                        'msg' => 'Successfully Assigned to Chef'
                    ]);
                }
                else{
                    return response()->json([
                        'msg' => 'Error Ouucred'
                    ]);
                }
            }
            else{
                return response()->json([
                    'msg' => 'Error Ouucred'
                ]);
            }
        }
    }

    //how many orders chef attend
    public function ChefAttendOrder($emp_id, $filter){
        $res = date("Y-m-d");
        if($filter == 'yesterday'){
            $res1 = date('Y-m-d', strtotime('-1 days'));
        }
        elseif($filter == 'week'){
            $res1 = date('Y-m-d', strtotime('-7 days'));
        }
        elseif($filter == 'month'){
            $res1 = date('Y-m-d', strtotime('-30 days'));
        }

        if($filter == 'today'){
            $data = DB::table('chef_orders')
                        ->join('food', 'food.item_code', '=', 'chef_orders.item_code')
                        ->where('chef_orders.emp_id',$emp_id)
                        ->where('chef_orders.status','running')
                        ->whereDate('chef_orders.created_at', $res)
                        ->select('chef_orders.*', 'food.name', 'food.image')
                        ->orderBy('chef_orders.updated_at','DESC')
                        ->get();
        }
        else{
            $data = DB::table('chef_orders')
                        ->join('food', 'food.item_code', '=', 'chef_orders.item_code')
                        ->where('chef_orders.emp_id',$emp_id)
                        ->whereBetween('chef_orders.created_at', [$res1, $res])
                        ->select('chef_orders.*', 'food.name', 'food.image')
                        ->orderBy('chef_orders.updated_at','DESC')
                        ->get();
        }
        return response()->json($data);
    }

    //chef orders status update
    public function ChefAttendOrderStatus($order_id, $item_code){
        $data = Chef_order::where('order_id', $order_id)->where('item_code', $item_code)->first();
        $data2 = OrderDetail::where('order_id', $order_id)->where('item_code', $item_code)->first();

        $data->status = "completed";
        $data2->status = "completed";

        if($data->update() &  $data2->update()){
            return response()->json([
                'msg' => 'Status Updated'
            ]);
        }
    }

    //chef ruturn inventory 
    public function ChefReturnInventory($emp_id, $ingredient_id, $inHand){
        $chef = Chef_inventory::where('emp_id', $emp_id)
                                ->where('ingredient_id', $ingredient_id)
                                ->whereDate('created_at', date("Y-m-d"))
                                ->first();

        $chef->return_quantity = $inHand;

        if($chef->update()){
            $data = Inventory::where('ingredient_id', $ingredient_id)->first();

            $data->current_quantity = $data->current_quantity + $inHand;

            if($data->update()){
                return response()->json([
                    'msg' => 'Status Updated'
                ]);
            }
            else{
                return response()->json([
                    'msg' => 'Error Occured'
                ]);
            }
        }
        else{
            return response()->json([
                'msg' => 'Error'
            ]);
        }
    }
}
