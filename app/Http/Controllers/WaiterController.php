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
}
