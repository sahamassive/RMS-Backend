<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_id');
            $table->integer('branch_id')->nullable();
            $table->string('ingredient_id');
            $table->string('unit');
            $table->double('previous_quantity')->nullable();
            $table->double('previous_unit_price')->nullable();
            $table->double('current_quantity')->nullable();
            $table->double('current_unit_price')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('inventories');
    }
}
