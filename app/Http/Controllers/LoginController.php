<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    //Employee login method
    public function loginDashboard(Request $request){

        $request->validate([
            'device_name' => 'required',
        ]);

        $type=$request->type;
        if($type =='Super-Admin' || $type =='Admin'){
            $query = "App\Models\\Admin";
            $user = $query::where('email', $request->email)->first();

        }else{
            $query = "App\Models\\$type";
            $user = $query::where('email', $request->email)->first();
        }

       
        if($user){
            if (Hash::check($request->password,$user->password)) {
                $token=  $user->createToken($request->device_name)->plainTextToken;
                $response =[
                    'message'=>'Login',
                    'token'=>$token,
                    'type'=>$type,
                    'emp_id' => $user->emp_id
                ];
                return response($response,201);
            }else{
                return response()->json([
                    'message'=>'Failed'
                ]);
            }
        }else{
            return response()->json([
                'message'=>'Failed'
            ]);   
        }
    }

    //customer login method
    public function CustomerloginDashboard(Request $request){
        $request->validate([
            'device_name' => 'required',
        ]);

        $user = Customer::where('phone', $request->setEmailOrPhone)
                        ->orWhere('email', $request->setEmailOrPhone)
                        ->first();
        if($user){
            if(Hash::check($request->password,$user->password)){
                $token=  $user->createToken($request->device_name)->plainTextToken;
                $response =[
                    'message'=>'success',
                    'token'=>$token,
                    'type'=> 'customer',
                    'customer_id' => $user->customer_id
                ];
                return response($response,201);
            }
            else{
                return response()->json([
                    'message'=>'Failed'
                ]);
            }
        }
        else{
            return response()->json([
                'message'=>'Failed'
            ]);
        }
    }
}
