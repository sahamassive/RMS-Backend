<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->uniqid();
            $table->string('restaurant_id');
            $table->string('branch_id')->nullable();
            $table->string('customer_id');
            $table->integer('item');
            $table->integer('total_price');
            $table->integer('vat');
            $table->integer('grand_price');
            $table->string('pickup_method');
            $table->string('order_status')->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
