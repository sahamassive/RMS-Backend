<?php

namespace App\Http\Controllers;

use App\Models\Waiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WaiterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Waiter  $waiter
     * @return \Illuminate\Http\Response
     */
    public function show(Waiter $waiter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Waiter  $waiter
     * @return \Illuminate\Http\Response
     */
    public function edit(Waiter $waiter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Waiter  $waiter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Waiter $waiter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Waiter  $waiter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Waiter $waiter)
    {
        //
    }

    //get waiiter name and count attended orders
    public function getWaiter(){
        $waiters = Waiter::select('first_name','last_name', 'emp_id')->get();
        $attendOrder = DB::table('waiters')
                    ->join('pos_orders', 'waiters.emp_id', 'pos_orders.waiter_id')
                    ->whereDate('pos_orders.created_at', date("Y-m-d"))
                    ->where('pos_orders.status', 'pending')
                    ->orWhere('pos_orders.status', 'running')
                    ->select('pos_orders.waiter_id',DB::Raw(
                        "COUNT(*) AS count"
                    ))
                    ->groupBy('pos_orders.waiter_id')
                    ->get();
        return response()->json([
            'waiters' => $waiters,
            'attendOrder' => $attendOrder
        ]);
    }

    //attended order for waiter
    public function getDetails($emp_id){
        //get order ids
        $id = DB::table('pos_orders')
                    ->where('waiter_id', $emp_id)
                    ->Where('status', 'running')
                    ->orWhere('status','pending')
                    ->select('order_id')
                    ->orderBy('created_at', 'DESC')
                    ->get();

        //get orders details
        $data = DB::table('pos_orders')
                    ->join('order_details', 'pos_orders.order_id', 'order_details.order_id')
                    ->join('food', 'order_details.item_code', 'food.item_code')
                    ->leftJoin('tables', 'tables.table_id', 'pos_orders.table_id')
                    ->where('pos_orders.waiter_id', $emp_id)
                    ->Where('pos_orders.status', 'running')
                    ->orWhere('pos_orders.status', 'pending')
                    ->select('pos_orders.*', 'food.name', 'food.image', 'tables.table_name', 'tables.table_type')
                    ->orderBy('pos_orders.created_at','DESC')
                    ->get();
        return response()->json([
            'id' => $id,
            'data' => $data
        ]);
    }

    //
    public function getOrdersCount($emp_id){
        $pending = DB::table('pos_orders')
                        ->Where('status', 'pending')
                        ->Where('waiter_id', $emp_id)
                        ->select(DB::Raw(
                            "COUNT(*) AS count"
                        ))
                        ->get();
        $running = DB::table('pos_orders')
                        ->Where('status', 'running')
                        ->Where('waiter_id', $emp_id)
                        ->select(DB::Raw(
                            "COUNT(*) AS count"
                        ))
                        ->get();
        $completed = DB::table('pos_orders')
                        ->Where('status', 'completed')
                        ->Where('waiter_id', $emp_id)
                        ->select(DB::Raw(
                            "COUNT(*) AS count"
                        ))
                        ->get();
        return response()->json([
            'pending' => $pending,
            'running' => $running,
            'completed' => $completed
        ]);
    }
}
