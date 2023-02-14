<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Whoops\Run;
use Illuminate\Support\Facades\DB;
use App\Models\Table\TableType;
use App\Models\Table\Table;

class TableController extends Controller
{
    //table type insert
    public function typeInsert(Request $request){
        $data = new TableType();
        $data->type = $request->type;
        $data->restaurant_id = $request->restaurant_id;
        if($data->save()){
            return response()->json([
                'msg'=>'Successfully Inserted'
            ]);
        }
        else{
            return response()->json([
                'msg'=>'Error Occured'
            ]);
        }
    }

    //table type all list
    public function typeList($id){
        $data = TableType::where('restaurant_id',$id)->get();
        return response()->json($data);
    }

    //table type status update
    public function tableTypeStatus($id){
        $data = TableType::where('id',$id)->first();
        if($data->status==1){
            $data->status=0;
            $data->update();
            return response()->json([
                'msg'=>'Status Updated Successfully'
            ]);
        }
        else{
            $data->status=1;
            $data->update();
            return response()->json([
                'msg'=>'Status Updated Successfully'
            ]); 
        }
    }

    //get table type information 
    public function editTableType($id){
        $data = TableType::where('id',$id)->first();
        return response()->json($data);
    }

    //update table type information
    public function updateTableType(Request $request,$id){
        $data = TableType::where('id',$id)->first();
        $data->type = $request->type;

        if($data->update()){
            return response()->json([
                'msg'=>'Successfully Updated'
            ]);
        }
        else{
            return response()->json([
                'msg'=>'Error Occured'
            ]);
        }
    }

    //insert a new table
    public function tableInsert(Request $request){
        $data = new Table();
        $data->table_id = 'T-' . uniqid();
        $data->status = "free";
        $data->restaurant_id = $request->restaurant_id;
        $data->branch_id = $request->branch_id;
        $data->table_name = $request->table_name;
        $data->table_type = $request->table_type;
        $data->seat = $request->seat;

        if($data->save()){
            return response()->json([
                'msg'=>'Successfully Inserted'
            ]);
        }
        else{
            return response()->json([
                'msg'=>'Error Occured'
            ]);
        }
    }

    //fetach all tables from the database
    public function tableList($id){
        $data = Table::where('restaurant_id',$id)->get();
        return response()->json($data);
    }

    //get table data from the database for editing
    public function editTable($id){
        $data = Table::where('table_id',$id)->get();
        return response()->json($data);
    }

    //update table data
    public function updateTable(Request $request, $id){
        $data = Table::where('table_id',$id)->first();
        $data->table_name = $request->table_name;
        $data->table_type = $request->table_type;
        $data->seat = $request->seat;

        if($data->update()){
            return response()->json([
                'msg'=>'Successfully Updated'
            ]);
        }
        else{
            return response()->json([
                'msg'=>'Error Occured'
            ]);
        }
    }
}
