<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDetails;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function getBooking(){
        $data = Booking::get()->toArray();
        return response()->json($data);

    }
    public function bookingInsert(Request $request){
        $id=rand ( 10000 , 99999 );
        $bookingId=date('hi').$id;
        $data=new Booking();
        $data->booking_id=$bookingId;
        $data->booking_date=$request->booking_date;
        $data->people=$request->people;
        $data->table=$request->table;
        $data->type=$request->type;
        $data->start_time=$request->start_time;
        $data->end_time=$request->end_time;
        $data->name=$request->name;  
        $data->email=$request->email;
        $data->phone=$request->phone;
        $data->note=$request->note;
        $data->save();
        return response()->json([
            'msg'=>'Booking Request Successfully Inserted'
           ]);



    }
}
