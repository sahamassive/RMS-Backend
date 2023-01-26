<?php

namespace App\Http\Controllers;

use App\Models\Ingrdeint;
use App\Models\Item;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RecipeController extends Controller
{
   public function ingredientInsert(Request $request){
    $data=new Ingrdeint();
    $data->resturant_id=$request->resturant_id;
    $data->ingredient=$request->ingredient;
    $data->unit=$request->unit;
    $data->unit_price=$request->unit_price;
    $data->save();
    return response()->json([
        'msg'=> 'Ingredient Inserted Successfully']);


   }
   public function ingredientList($id){
    $data=Ingrdeint::where('resturant_id',$id)->get();
    return response()->json($data);
   }

   public function recipeInsert(Request $request){
      $ingredient=$request->ingredient_name;
      $qty=$request->ingredient_quantity;

      $data=DB::table('items')->select(DB::raw("COUNT(id) as count"))->first();
      $item_code='10' .$data->count+1;
      for ($i = 0; $i < count($ingredient); $i++) {

        $data = new Recipe();
        $data->resturant_id=$request->resturant_id; 
        $data->item_code=$item_code; 
        $data->ingredient_name =$ingredient[$i]['value'];
        $data->ingredient_quantity = $qty[$i]['qty_value'];
        $data->save();
    }

      $item=new Item();
      $item->item_name=$request->item;
      $item->item_code=$item_code;
      $item->save();
      
    return response()->json([
        'msg'=> 'Recipe Inserted Successfully']);

   }
   }

