<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Item;
use App\Models\Food;
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
      $ingredient=$request->ingredient_id;
      $qty=$request->ingredient_quantity;

      $data=DB::table('items')->select(DB::raw("COUNT(id) as count"))->first();
      $item_code='10' .$data->count+1;
      for ($i = 0; $i < count($ingredient); $i++) {

        $data = new Recipe();
        $data->restaurant_id=$request->restaurant_id; 
        $data->item_code=$item_code; 
        $data->ingredient_id =$ingredient[$i]['value'];
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
   public function getItem($id){
  

            $data = Item::where('restaurant_id',$id)->whereNotExists(function ($query) {
              $query->select(DB::raw(1))
                    ->from('food')
                    ->whereRaw('items.item_code = food.item_code');
          })->get();
    // $data=Item::where('restaurant_id',$id)->get();
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

    public function basicPrice($id){
         $data=DB::table('recipes')
                   ->join('inventories','inventories.ingredient_id','recipes.ingredient_id')
                   ->join('items','items.item_code','recipes.item_code')  
                   ->selectRaw("SUM(inventories.current_unit_price * recipes.ingredient_quantity) as basic_price
                   ,items.item_name,items.item_code")
                   ->groupBy('recipes.item_code') 
                   ->where('recipes.restaurant_id',$id)
                   ->get(); 

     return response()->json($data);

    }

    public function priceUpdate(Request $request,$id){
      $data=Item::where('item_code',$id)->first();
      $data->basic_price=$request->basic_price;
      $data->selling_price=$request->selling_price;
      $data->update();
      return response()->json([
        'msg'=>'Price set'
      ]);

    }
    public function editItem($id){
      $data=Item::findOrfail($id);
      return response($data);
    }
    public function updateItem(Request $request,$id){

      $data=Item::findOrfail($id);
      $food=Food::where('item_code',$data->item_code)->first();
      if($food){
        $food->name=$request->name;
        $food->update();
        $data->item_name=$request->name;
        $data->update();
      }else{
        $data->item_name=$request->name;
        $data->update();
      }
      
      return response()->json([
        'msg'=>'Item Name Updated'
      ]);
    }
}

