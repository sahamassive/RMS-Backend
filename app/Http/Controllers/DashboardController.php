<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function trending($id){
        $trending = DB::table('order_details')
        ->join('food', 'order_details.item_code', 'food.item_code')
        ->select('food.*',DB::raw("COUNT(order_details.item_code) as order_count"))
         ->where('food.restaurant_id',$id)
        ->whereBetween('order_details.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->groupBy('order_details.item_code')
        ->orderByRaw('count(*) DESC')
        ->limit(4)
        ->get();
        return response()->json($trending);
    }
    public function todayData($id){
        $today_sell =DB::table('orders')->select(DB::raw("Sum(grand_price) as total"),DB::raw("Sum(item) as items"), DB::raw("COUNT(order_id) as order_count"))
         ->whereDate('created_at',  Carbon::today())
        ->where('order_status','pending')
        ->where('restaurant_id',$id)
 
        ->first();

        $today_item =DB::table('order_details')
        ->join('food', 'order_details.item_code', 'food.item_code')
        ->select(DB::raw("Count(order_details.item_code) as item_count"),DB::raw("Sum(order_details.quantity) as quantity"),'food.name','food.image','food.price')
        ->whereDate('order_details.created_at',  Carbon::today())
        ->groupBy('order_details.item_code')
        ->where('order_details.status','pending')
        ->where('food.restaurant_id',$id)

       ->get();

       $today_ingredient =DB::table('chef_inventories')
       ->join('ingredients', 'chef_inventories.ingredient_id', 'ingredients.id')
       ->select(DB::raw("Sum(chef_inventories.quantity) as quantity"),
        DB::raw("Sum(chef_inventories.used_quantity ) as used_quantity"),
        DB::raw("Sum(chef_inventories.return_quantity ) as return_quantity"),'chef_inventories.unit','ingredients.ingredient')
       ->whereDate('chef_inventories.created_at',  Carbon::today())
       ->groupBy('chef_inventories.ingredient_id')
       ->where('ingredients.restaurant_id',$id)
      ->get();

      $month_ingredient =DB::table('chef_inventories')
      ->join('ingredients', 'chef_inventories.ingredient_id', 'ingredients.id')
      ->select(DB::raw("Sum(chef_inventories.quantity) as quantity"),
      DB::raw("Sum(chef_inventories.used_quantity ) as used_quantity"),
      DB::raw("Sum(chef_inventories.return_quantity ) as return_quantity"),'chef_inventories.unit','ingredients.ingredient')

      ->whereRaw('MONTH(chef_inventories.created_at) = ?', [date('m')])
      ->whereRaw('YEAR(chef_inventories.created_at) = ?', [date('Y')])
      ->groupBy('chef_inventories.ingredient_id')
      ->where('ingredients.restaurant_id',$id)
     ->get();

        return response()->json([
            'today_sell'=>$today_sell,
            'today_items'=>$today_item,
            'today_ingredient'=>$today_ingredient,
            'month_ingredient'=>$month_ingredient
        ]);

    }

    public function filterData($start,$end,$id){
        $data =DB::table('chef_inventories')
        ->join('ingredients', 'chef_inventories.ingredient_id', 'ingredients.id')
        ->select(DB::raw("Sum(chef_inventories.quantity) as quantity"),
        DB::raw("Sum(chef_inventories.used_quantity ) as used_quantity"),
        DB::raw("Sum(chef_inventories.return_quantity ) as return_quantity"),'chef_inventories.unit','ingredients.ingredient')
  
        ->whereBetween('chef_inventories.created_at', [$start, $end])
        ->groupBy('chef_inventories.ingredient_id')
        ->where('ingredients.restaurant_id',$id)
       ->get();
       return response()->json($data);
    }
    public function filterDataChef($chefId,$id){
        $data =DB::table('chef_inventories')
        ->join('ingredients', 'chef_inventories.ingredient_id', 'ingredients.id')
        ->select(DB::raw("Sum(chef_inventories.quantity) as quantity"),
         DB::raw("Sum(chef_inventories.used_quantity ) as used_quantity"),
         DB::raw("Sum(chef_inventories.return_quantity ) as return_quantity"),'chef_inventories.unit','ingredients.ingredient')
        ->whereDate('chef_inventories.created_at',  Carbon::today())
        ->groupBy('chef_inventories.ingredient_id')
        ->where('chef_inventories.emp_id',$chefId)
        ->where('ingredients.restaurant_id',$id)
       ->get(); 
       return response()->json($data);
    } 
    public function filterDataSell($start,$end,$id){
        $data =DB::table('order_details')
        ->join('food', 'order_details.item_code', 'food.item_code')
        ->select(DB::raw("Count(order_details.item_code) as item_count"),DB::raw("Sum(order_details.quantity) as quantity"),'food.name','food.image','food.price')
       
        ->whereBetween('order_details.created_at', [$start, $end])
        ->groupBy('order_details.item_code')
        ->where('order_details.status','pending')
        ->where('food.restaurant_id',$id)

       ->get();
       return response()->json($data);
    }
    
    public function totalSalesMonthWise($id){
        $sales =DB::table('orders')->select(DB::raw("Sum(grand_price) as total"), DB::raw("MONTHNAME(created_at) as month_name"))
        ->whereYear('created_at', date('Y'))
        ->where('order_status','pending')
        ->where('restaurant_id',$id)
        ->groupBy(DB::raw("Month(created_at)"))
        ->pluck('total', 'month_name');
            
        $sales_label = $sales->keys();
        $sales_data = $sales->values();
        return response()->json([
            'data'=>$sales_data,
            'data_label'=>$sales_label
        ]);
    }
    public function totalSalesDaykWise($id){
        $sales =DB::table('orders')->select(DB::raw("Sum(grand_price) as total"),  DB::raw("DAYNAME(created_at) as day_name"))
        // ->whereYear('created_at', date('Y'))
        ->where('order_status','pending')
        ->where('restaurant_id',$id)
        ->groupBy(DB::raw("Day(created_at)"))
        ->pluck('total', 'day_name');
            
        $sales_label = $sales->keys();
        $sales_data = $sales->values();
        return response()->json([
            'data'=>$sales_data,
            'data_label'=>$sales_label
        ]);
    }
    public function yearWiseComparison($id){
        $sales =DB::table('orders')->select(DB::raw("Sum(grand_price) as total"),  DB::raw("Year(created_at) as year"))
        // ->whereYear('created_at', date('Y'))
        ->where('order_status','pending')
        ->where('restaurant_id',$id)
        ->groupBy(DB::raw("Year(created_at)"))
        ->pluck('total', 'year');
            
        $sales_label = $sales->keys();
        $sales_data = $sales->values();
        return response()->json([
            'data'=>$sales_data,
            'data_label'=>$sales_label
        ]);
    }
}
