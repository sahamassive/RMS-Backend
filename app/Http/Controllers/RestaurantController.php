<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restuarant;
use Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    //all restaurant list
    public function index(){
        $restaurants= Restuarant::all();
        return response()->json($restaurants);
    }

    //new restaurant insert request
    public function restaurantInsert(Request $request){
        //create new restaurant object
        $data = new Restuarant();

        //check image exists
        $image=$request->file('image');
        if($image){
            //check image extension
            $extension = $image->getClientOriginalExtension();
            if(
                $extension == 'jpeg' || $extension == 'JPEG' ||
                $extension == 'jpg' || $extension == 'JPG' ||
                $extension == 'img' || $extension == 'IMG' ||
                $extension == 'png' || $extension == 'PNG'
            ){
                //change image name
                $imageName = time() . "." . $extension;
                //image path  
                $largeimagePath =public_path('restaurants/large/'.$imageName);
                $mediumimagePath=public_path('restaurants/medium/'.$imageName);
                $smallimagePath =public_path('restaurants/small/'.$imageName);

                Image::make($image)->resize(1000,1000)->save($largeimagePath);
                Image::make($image)->resize(500,500)->save($mediumimagePath);
                Image::make($image)->resize(250,250)->save($smallimagePath);

                
                $data->restaurant_id = uniqid();
                $data->restaurant_name = $request->restaurant_name;
                $data->phone = $request->phone;
                $data->email = $request->email;
                $data->country = $request->country;
                $data->state = $request->state;
                $data->city = $request->city;
                $data->address = $request->address;
                $data->meta_tag = $request->meta_tag;
                $data->meta_description = $request->meta_description;
                $data->meta_keyword = $request->meta_keyword;
                $data->logo = $imageName;

                $data->save();
                return response()->json([
                    'msg'=>'Restaurant Inserted Successfully'
                ]);
            }
            else{
                return response()->json([
                    'msg'=>'Your inserted file is not an image.'
                ]);
            }
        }
    }

    //restaurant status update
    public function restaurantStatus($id){
        $data = Restuarant::where('id',$id)->first();
        if($data->status==1){
            $data->status=0;
            $data->update();
            return response()->json([
                'msg'=>'Section Status Update'
            ]);
        }
        else{
            $data->status=1;
            $data->update();
            return response()->json([
                'msg'=>'Section Status Update'
            ]); 
        }
    }

    //get restaurant data for editing
    public function editRestaurant($id){
        $data = Restuarant::find($id);
        return response()->json($data);
    }

    //update restaurant information
    public function updateRestaurant(Request $request, $id){
        //fetch data from database
        $data = Restuarant::findorfail($id);
        $data->restaurant_name = $request->restaurant_name;
        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->city = $request->city;
        $data->address = $request->address;
        $data->meta_tag = $request->meta_tag;
        $data->meta_description = $request->meta_description;
        $data->meta_keyword = $request->meta_keyword;

        //check image
        $image=$request->file('image');
        if($image){
            $extension = $image->getClientOriginalExtension();
            if(
                $extension == 'jpeg' || $extension == 'JPEG' ||
                $extension == 'jpg' || $extension == 'JPG' ||
                $extension == 'img' || $extension == 'IMG' ||
                $extension == 'png' || $extension == 'PNG'
            ){
                //image path
                $path1 = public_path('restaurants/large/' . $data->logo);
                $path2 = public_path('restaurants/medium/' . $data->logo);
                $path3 = public_path('restaurants/small/' . $data->logo);
                if (File::exists($path1)) {
                    //delete prevoius image
                    @unlink($path1);	
                    @unlink($path2);	
                    @unlink($path3);	
                }
                //change image name
                $imageName = time() . "." . $extension;
                //store image
                $largeimagePath = public_path('restaurants/large/'.$imageName);
                $mediumimagePath = public_path('restaurants/medium/'.$imageName);
                $smallimagePath  = public_path('restaurants/small/'.$imageName);

                //customize image size
                Image::make($image)->resize(1000,1000)->save($largeimagePath);
                Image::make($image)->resize(500,500)->save($mediumimagePath);
                Image::make($image)->resize(250,250)->save($smallimagePath);

                $data->logo = $imageName;
            }
            else{
                return response()->json([
                    //error message
                    'msg'=>'Your inserted file is not an image.'
                ]);
            }
        }
        $data->update();
        return response()->json([
            //success message
            'msg'=>'Updated Successfully'
        ]);
    }

    //all Restaurants Branches
    public function allRestaurantsBranches($restaurant_id){
        //fetch data from database
        $data = DB::table('branches')
                    ->join('restuarants', 'branches.restaurant_id', '=', 'restuarants.restaurant_id')
                    ->select('branches.*', 'restuarants.restaurant_name')
                    ->where('branches.restaurant_id', $restaurant_id)
                    ->get();
        return response()->json($data);
    }
}
