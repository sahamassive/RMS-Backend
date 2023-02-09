<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChefOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chef_orders', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('order_id');
            $table->integer('item_code');
            $table->integer('quantity');
            $table->string('kot')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('chef_orders');
    }
}
