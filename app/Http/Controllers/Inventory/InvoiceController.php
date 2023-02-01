<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Invoice;
use App\Models\Inventory\InvoiceDetail;
use App\Models\Inventory\Inventory;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    //all invoice List
    public function index(){
        //fetch data from database
        $data = DB::table('invoices')
                    ->join('suppliers', 'invoices.supplier_id', '=', 'suppliers.id')
                    ->select('invoices.*', 'suppliers.supplier_name')
                    ->orderBy("invoices.created_at", "DESC")
                    ->get();
        return response()->json($data);
    }

    //insert a new invoice
    public function invoiceInsert(Request $request){
        $ingredient_id = $request->ingredient_id;
        $data = new Invoice();
        $data->restaurant_id = $request->restaurant_id;
        $data->branch_id = '1';
        $data->supplier_id = $request->supplier_id;
        $data->total_price = $request->total_price;
        $data->date = $request->date;
        $data->invoice_id = $request->invoice_id;
        
        //check data has successfully saved or failed
        if($data->save()){
            for($i = 0; $i < count($request->amount); $i++){
                if($request->unit[$i]['value'] == 'Kg'){
                    $unit = $request->amount[$i]['value']*1000;
                    $unit_unit = 'Gm';
                }
                else if($request->unit[$i]['value'] == 'L'){
                    $unit = $request->amount[$i]['value']*900;
                    $unit_unit = 'Gm';
                }
                else{
                    $unit = $request->amount[$i]['value'];
                    $unit_unit = $request->unit[$i]['value'];
                }
                $details = new InvoiceDetail();
                $details->invoice_id = $request->invoice_id;
                $details->ingredient_id = $ingredient_id[$i]['value'];

                $inventory = Inventory::where('ingredient_id', $ingredient_id[$i]['value'])->first();
                if($inventory){
                    $inventory->previous_quantity = $inventory->current_quantity;
                    $inventory->previous_unit_price = $inventory->current_unit_price;
                    $inventory->current_quantity = $inventory->current_quantity + $unit;
                    $inventory->current_unit_price =  $request->price[$i]['value'] / $unit;
                    $details->amount = $unit;
                    $details->unit = $unit_unit;
                    $details->unit_price = $request->price[$i]['value'] / $unit;
                    $inventory->update();
                }
                else{
                    $inventory = new Inventory();
                    $inventory->restaurant_id = $request->restaurant_id;
                    $inventory->ingredient_id = $ingredient_id[$i]['value'];
                    $inventory->previous_quantity = $inventory->current_quantity;
                    $inventory->previous_unit_price = $inventory->current_unit_price;
                    $inventory->previous_quantity = $inventory->current_quantity;
                    $inventory->previous_unit_price = $inventory->current_unit_price;
                    $inventory->current_quantity = $inventory->current_quantity + $unit;
                    $inventory->current_unit_price =  $request->price[$i]['value'] / $unit;
                    $details->amount = $unit;
                    $details->unit = $unit_unit;
                    $details->unit_price = $request->price[$i]['value'] / $unit;
                    $inventory->save();
                }
                $details->price = $request->price[$i]['value'];
                //check data has successfully saved or failed
                $details->save();

            }
            return response()->json([
                //error message
                'msg'=>'Successfully Inserted'
            ]);
        }
        else{
            return response()->json([
                //error message
                'msg'=>'Error Occurred'
            ]);
        }
    }

    //invoice details
    public function invoiceDetails($invoice_id){
        $data = DB::table('invoice_details')
                    ->join('ingrdeints', 'invoice_details.ingredient_id', '=', 'ingrdeints.id')
                    ->select('invoice_details.*', 'ingrdeints.ingredient')
                    ->where('invoice_details.invoice_id', $invoice_id)
                    ->get();
        $data2 = DB::table('invoices')
                    ->join('suppliers', 'invoices.supplier_id', '=', 'suppliers.id')
                    ->select( 'invoices.*','suppliers.*')
                    ->where('invoices.invoice_id', $invoice_id)
                    ->first();

        return response()->json([
            'details' => $data,
            'supplier' => $data2,
        ]);
    }
}
