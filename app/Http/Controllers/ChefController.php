<?php

namespace App\Http\Controllers;

use App\Models\Chef;
use App\Models\Chef_inventory;
use App\Models\Chef_order;
use App\Models\Inventory\Inventory;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function ChefInventory($emp_id){
        $data = DB::table('chef_inventories')
                ->join('ingredients', 'ingredients.id', '=', 'chef_inventories.ingredient_id')
                ->where('chef_inventories.emp_id',$emp_id)
                ->whereDate('chef_inventories.created_at', date("Y-m-d"))
                ->select('chef_inventories.*', 'ingredients.ingredient')
                ->orderBy('chef_inventories.created_at','DESC')
                ->get();
        return response()->json($data);
    }

    //confirm items from order
    public function ChefOrder($emp_id, $order_id, $item_code, $quantity){
        $data = Chef_inventory::where("emp_id",$emp_id)->get();
        $item = Recipe::where('item_code', $item_code)->get();
        $orders = Order::where('order_id', $order_id)->first();
        $msg = array();
        $status = false;

        for($i=0; $i<count($item); $i++){
            for($j=0; $j<count($data); $j++){
                if($item[$i]->ingredient_id == $data[$j]->ingredient_id){
                    $data[$j]->used_quantity = $item[$i]->ingredient_quantity * $quantity;
                    if($data[$j]->quantity < 0){
                        $ingredient = Ingredient::where('id', $data[$j]->ingredient_id)->first();
                        $msg[] = ' ' . abs($data[$j]->quantity) . $data[$j]->unit . ' ' . $ingredient->ingredient;
                    }
                    
                }
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
            $orders->order_status = "running";
            if($orders->update()){
                $chef = new Chef_order();
                $chef->emp_id = $emp_id;
                $chef->order_id = $order_id;
                $chef->kot = '01'.date('hi');
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
}
