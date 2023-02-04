<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id');
            $table->integer('category_id');
            $table->integer('recipe_id');
            $table->string('restaurant_id');
            $table->integer('brand_id')->nullable();
            $table->integer('food_review_id');
            $table->integer('item_code');
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->bigText('description')->nullable();
            $table->string('speciality')->nullable();
            $table->double('basic_price');
            $table->string('price');
            $table->string('discount')->default(0);
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keywords');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('food');
    }
}
