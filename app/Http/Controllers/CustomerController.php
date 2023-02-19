<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    //insert new customer
    public function customerInsert(Request $request){
        $data = new Customer();
        $data->restaurant_id = $request->restaurant_id;
        $data->customer_id = 'C-' . uniqid();
        $data->name = $request->name;
        $data->phone = $request->phone;
        $data->password = Hash::make($request->password);
        
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
}
