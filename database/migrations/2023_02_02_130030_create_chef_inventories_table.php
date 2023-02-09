<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChefInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chef_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('ingredient_id');
            $table->float('quantity');
            $table->float('used_quantity');
            $table->string('unit');
            $table->date('date');
            $table->string('return_quantity')->nullable();
            $table->string('return_unit')->nullable();
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
        Schema::dropIfExists('chef_inventories');
    }
}
