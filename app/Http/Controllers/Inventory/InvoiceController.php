<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Invoice;
use App\Models\Inventory\InvoiceDetail;
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
                $details = new InvoiceDetail();
                $details->invoice_id = $request->invoice_id;
                $details->ingredient_id = $request->ingredient_id[$i]['value'];
                $details->amount = $request->amount[$i]['value'];
                $details->unit = $request->unit[$i]['value'];
                $details->price = $request->price[$i]['value'];
                //check data has successfully saved or failed
                if($details->save()){
                    return response()->json([
                        //success message
                        'msg'=>'Invoice Inserted Successfully'
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
