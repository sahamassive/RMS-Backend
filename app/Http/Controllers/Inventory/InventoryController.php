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

            $previousData = Chef_inventory::where('ingredient_id', '=', $request->inventoryQueue[$i][0]['ingredient_id'])
                                            ->whereDate('created_at', date("Y-m-d"))
                                            ->where('emp_id', $request->chefId)
                                            ->first();
            if($previousData){
                $previousData->quantity = $previousData->quantity + $request->inventoryQueue[$i][0]['askQuantity'];
                $previousData->update();
            }
            else{
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
                    if($newInventory->previous_quantity > $request->inventoryQueue[$i][0]['askQuantity']){
                        $newInventory->previous_quantity = $newInventory->previous_quantity - $request->inventoryQueue[$i][0]['askQuantity'];
                        
                    }
                    else{
                        $newInventory->previous_quantity = 0;
                    }
                }
                $newInventory->update();
            }
        }
        return response()->json([
            //success message
            'msg'=>'Successfully assigned'
        ]);
    }

    public function inventoryTransfer(Request $request){
        $id = uniqid();
        $data = new TransferInventory;
        if($request->branchId == null){
            return response()->json([
                //error message
                'msg'=>'Please Select Branch First'
            ]);
        }
        $data->restaurant_id = $request->restaurant_id;
        $data->transfer_id = $id;
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
            $details->transfer_id = $id;
            $details->ingredient_id = $request->inventoryQueue[$i][0]['ingredient_id'];
            $details->quantity = $request->inventoryQueue[$i][0]['askQuantity'];
            $details->unit = $unit_unit;
            $details->save();

            $newInventory = Inventory::where('ingredient_id', $request->inventoryQueue[$i][0]['ingredient_id'])->first();
            $newInventory->current_quantity = $newInventory->current_quantity - $request->inventoryQueue[$i][0]['askQuantity'];
            if($newInventory->previous_quantity > 0){
                if($newInventory->previous_quantity > $request->inventoryQueue[$i][0]['askQuantity']){
                    $newInventory->previous_quantity = $newInventory->previous_quantity - $request->inventoryQueue[$i][0]['askQuantity'];
                    
                }
                else{
                    $newInventory->previous_quantity = 0;
                }
            }
            $newInventory->update();
        }
        return response()->json([
            //success message
            'msg'=>'Successfully assigned'
        ]);
    }
}
