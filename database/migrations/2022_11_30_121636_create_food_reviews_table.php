<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('food_id');
            $table->string('customer_id')->nullable();
            $table->string('type')->nullable();//good or bad
            $table->text('review')->nullable();;
            $table->string('rating')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('food_reviews');
    }
}
