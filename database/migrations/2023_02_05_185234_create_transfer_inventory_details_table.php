<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferInventoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_inventory_details', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_id');
            $table->string('ingredient_id');
            $table->string('quantity');
            $table->string('unit');
            $table->string('receive_quantity')->nullable();
            $table->string('receive_unit')->nullable();
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
        Schema::dropIfExists('transfer_inventory_details');
    }
}
