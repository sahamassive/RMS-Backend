<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
}
