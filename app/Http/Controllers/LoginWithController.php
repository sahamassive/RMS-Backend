<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Oauth2;

class LoginWithController extends Controller
{
    
public function handleGoogleLogin(Request $request)
{
    $client = new Google_Client(['3317537055-gn0cvsnkdusjari6lu0r4281a85g8j37.apps.googleusercontent.com
    ']);
    $payload = $client->verifyIdToken($request->input('id_token'));
    $google_user_id = $payload['sub'];

    // Check if the user is already registered in your database

    $service = new Google_Service_Oauth2($client);
    $google_user = $service->userinfo->get();
   
    $newUser = Customer::create([
        'restaurant_id'=>1,
        'name' => $google_user->name,
        'email' => $google_user->email,
        'customer_id'=>1,
        'phone'=>1,
        'password'=>1,
        'status'=>1
       
    ]);
    return response()->json($newUser);
    // $existingUser = Customer::where('google_user_id', $google_user->phone)->first();

    // if ($existingUser) {
    //     // Log the user in
    //   return response()->json($existingUser)
    // } else {
    //     // Get the user data from the Google API
     
    //     // Create a new user
    //     $newUser = Customer::create([
    //         'name' => $google_user->name,
    //         'phone' => $google_user->phone,
    //         'google_user_id' => $google_user_id,
    //         'password' => bcrypt(Str::random(20)),
    //     ]);

    //     // Log the user in
    //     auth()->login($newUser);
    // }

    // // Redirect the user to the desired page
    // return redirect('/dashboard');
}
}
