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
            $table->string('category_id');
            $table->string('recipe_id');
            $table->string('food_review_id');
            $table->string('name');
            $table->string('image');
            $table->string('description')->nullable();
            $table->string('speicality')->nullable();
            $table->integer('price');
            $table->string('meta-tag');
            $table->string('meta-description');
            $table->string('meta-keyword');
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
        Schema::dropIfExists('food');
    }
}
