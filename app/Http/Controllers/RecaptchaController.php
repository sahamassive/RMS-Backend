<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class RecaptchaController extends Controller

// const url = `https://www.google.com/recaptcha/api/siteverify?secret=${secret}&response=${response}`;

{
    public function verify(Request $request)
    {
        $response = Http::post('https://www.google.com/recaptcha/api/siteverify?secret=6LcK5pgkAAAAAIa5PIWp49Rf7TLhptyTKCc-iXKJ&response='.$request->recaptchaResponse);

        $responseData = $response->json();
    

        if ($responseData['success']) {
            // reCAPTCHA verification successful, continue with your application logic
            return response()->json(['success' => true]);
        } else {
            // reCAPTCHA verification failed, handle the error
            return response()->json(['success' => false]);
        }
    }
}
