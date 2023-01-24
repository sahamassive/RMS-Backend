<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('discount_id');
            $table->string('restaurant_id');
            $table->string('branch_id')->nullable();
            $table->string('food_id');
            $table->string('discount');
            $table->tinyInteger('status')->default(1);
            $table->date('starting_date');
            $table->time('starting_time');
            $table->date('ending_date');
            $table->time('ending_time');
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
        Schema::dropIfExists('discounts');
    }
}
