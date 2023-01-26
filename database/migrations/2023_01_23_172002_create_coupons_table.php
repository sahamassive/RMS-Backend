<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_id');
            $table->string('restaurant_id');
            $table->string('branch_id')->nullable();
            $table->string('coupon_code');
            $table->string('discount_amount');
            $table->integer('quantity');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('coupons');
    }
}
