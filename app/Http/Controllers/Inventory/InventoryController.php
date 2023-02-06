<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Inventory;
use App\Models\Inventory\TransferInventory;
use App\Models\Inventory\TransferInventoryDetails;
use App\Models\Chef_inventory;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    //all inventory List
    public function index(){
        //fetch data from database
        $data = DB::table('inventories')
                    ->join('ingredients', 'inventories.ingredient_id', '=', 'ingredients.id')
                    ->select('inventories.*', 'ingredients.ingredient')
                    ->get();
        return response()->json($data);
    }

    //inventory Distribution between chefs
    public function inventoryDistribution(Request $request){
        for($i=0; $i<count($request->inventoryQueue); $i++){
            if($request->inventoryQueue[$i][0]['unit'] == 'Kg'){
                $unit_unit = 'Gm';
            }
            else if($request->inventoryQueue[$i][0]['unit'] == 'L'){
                $unit_unit = 'Gm';
            }
            else{
                $unit_unit = $request->inventoryQueue[$i][0]['unit'];
            }
            $data = new Chef_inventory();
            if($request->chefId == null){
                return response()->json([
                    //error message
                    'msg'=>'Please Select Chef First'
                ]);
            }
            $data->emp_id = $request->chefId;
            $data->ingredient_id = $request->inventoryQueue[$i][0]['ingredient_id'];
            $data->quantity = $request->inventoryQueue[$i][0]['askQuantity'];
            $data->unit = $unit_unit;
            $data->date = date("Y-m-d");
            $data->save();

            $newInventory = Inventory::where('ingredient_id', $request->inventoryQueue[$i][0]['ingredient_id'])->first();
            $newInventory->current_quantity = $newInventory->current_quantity - $request->inventoryQueue[$i][0]['askQuantity'];
            if($newInventory->previous_quantity > 0){
                $newInventory->previous_quantity = $newInventory->previous_quantity - $request->inventoryQueue[$i][0]['askQuantity'];
            }
            $newInventory->update();
        }
        return response()->json([
            //success message
            'msg'=>'Successfully assigned'
        ]);
    }

    public function inventoryTransfer(Request $request){
        $data = new TransferInventory;
        if($request->branchId == null){
            return response()->json([
                //error message
                'msg'=>'Please Select Branch First'
            ]);
        }
        $data->restaurant_id = $request->restaurant_id;
        $data->transfer_id = uniqid();
        $data->sending_date = date("Y-m-d");
        $data->from = 1;
        $data->to = $request->branchId;
        $data->save();
        for($i=0; $i<count($request->inventoryQueue); $i++){
            if($request->inventoryQueue[$i][0]['unit'] == 'Kg'){
                $unit_unit = 'Gm';
            }
            else if($request->inventoryQueue[$i][0]['unit'] == 'L'){
                $unit_unit = 'Gm';
            }
            else{
                $unit_unit = $request->inventoryQueue[$i][0]['unit'];
            }

            $details = new TransferInventoryDetails();
            $details->

            $newInventory = Inventory::where('ingredient_id', $request->inventoryQueue[$i][0]['ingredient_id'])->first();
            $newInventory->current_quantity = $newInventory->current_quantity - $request->inventoryQueue[$i][0]['askQuantity'];
            if($newInventory->previous_quantity > 0){
                $newInventory->previous_quantity = $newInventory->previous_quantity - $request->inventoryQueue[$i][0]['askQuantity'];
            }
            $newInventory->update();
        }
        return response()->json([
            //success message
            'msg'=>'Successfully assigned'
        ]);
    }
}
