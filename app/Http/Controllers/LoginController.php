<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function loginDashboard(Request $request){

        $request->validate([
            
            'device_name' => 'required',
        ]);

        $type=$request->type;

        $query = "App\Models\\$type";
        $user = $query::where('email', $request->email)->first();
        if($user){
            if (Hash::check($request->password,$user->password)) {
                $token=  $user->createToken($request->device_name)->plainTextToken;
                $response =[
                    'message'=>'Login',
                    'token'=>$token,
                    'type'=>$type
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
}
