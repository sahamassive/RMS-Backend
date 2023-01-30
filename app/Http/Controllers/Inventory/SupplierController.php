<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Supplier;

class SupplierController extends Controller
{
    //all supplier information
    public function index($id){
        //fetches all supplier information from database
        $data=Supplier::where('restaurant_id',$id)->get();
        return response()->json($data);
    }

    //insert new supplier to the database
    public function supplierInsert(Request $request){
        //create new supplier object
        $data = new Supplier();

        $data->restaurant_id = $request->restaurant_id;
        $data->branch_id = '1';
        $data->supplier_name = $request->supplier_name;
        $data->market_name = $request->market_name;
        $data->email = $request->email;
        $data ->phone = $request->phone;
        $data->address = $request->address;

        //check data has successfully saved or failed
        if($data->save()){
            return response()->json([
                //success message
                'msg'=>'Supplier Inserted Successfully'
            ]);
        }
        else{
            return response()->json([
                //error message
                'msg'=>'Error Occurred'
            ]);
        }
    }

    //Supplier status update
    public function supplierStatus($id){
        //fetch from database
        $data = Supplier::where('id',$id)->first();
        if($data->status==1){
            $data->status=0;
            $data->update();
            return response()->json([
                'msg'=>'Supplier Status Updated'
            ]);
        }
        else{
            $data->status=1;
            $data->update();
            return response()->json([
                'msg'=>'Supplier Status Update'
            ]); 
        }
    }

    //edit branch
    public function editSupplier($id){
        //fetch from database
        $data = Supplier::where('id',$id)->first();
        return response()->json($data);
    }

    //update supplier information
    public function updateSupplier(Request $request, $id){
        //fetch from database
        $data = Supplier::where('id',$id)->first();

        $data->supplier_name = $request->supplier_name;
        $data->market_name = $request->market_name;
        $data->email = $request->email;
        $data ->phone = $request->phone;
        $data->address = $request->address;

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
