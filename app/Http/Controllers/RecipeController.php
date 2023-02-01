<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Item;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RecipeController extends Controller
{
   public function ingredientInsert(Request $request){
    $data=new Ingredient();
    $data->restaurant_id=$request->restaurant_id;
    $data->ingredient=$request->ingredient;
    $data->save();
    return response()->json([
        'msg'=> 'Ingredient Inserted Successfully']);


   }

   public function ingredientList($id){
    $data=Ingredient::where('restaurant_id',$id)->get();
    return response()->json($data);
   }

   public function recipeInsert(Request $request){
      $ingredient=$request->ingredient_name;
      $qty=$request->ingredient_quantity;

      $data=DB::table('items')->select(DB::raw("COUNT(id) as count"))->first();
      $item_code='10' .$data->count+1;
      for ($i = 0; $i < count($ingredient); $i++) {

        $data = new Recipe();
        $data->restaurant_id=$request->restaurant_id; 
        $data->item_code=$item_code; 
        $data->ingredient_name =$ingredient[$i]['value'];
        $data->ingredient_quantity = $qty[$i]['qty_value'];
        $data->save();
    }

      $item=new Item();
      $item->restaurant_id=$request->restaurant_id; 
      $item->item_name=$request->item;
      $item->item_code=$item_code;
      $item->save();
      
    return response()->json([
        'msg'=> 'Recipe Inserted Successfully']);

   }
   public function itemList($id){
    $data=Item::where('restaurant_id',$id)->get();
    return response()->json($data);
   }

       //ingredient status update
       public function ingredientStatus($id){
        //fetch from database
        $data = Ingredient::where('id',$id)->first();
        //if active status
        if($data->status==1){
            $data->status=0;
            $data->update();
            return response()->json([
              //success response
                'msg'=>'Ingredient Status Updated'
            ]);
        }
        else{
            $data->status=1;
            $data->update();
            return response()->json([
              //error message
                'msg'=>'Ingredient Status Update'
            ]); 
        }
    }

    //edit Ingredient
    public function editIngredient($id){
      //fetch data from database
      $data = Ingredient::where('id', $id)->first();
      return response()->json($data);
    }

    //updatre ingredient information
    public function updateIngredient(Request $request, $id){
      //fetch data from database
      $data = Ingredient::where('id', $id)->first();

      $data->ingredient = $request->ingredient;

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

