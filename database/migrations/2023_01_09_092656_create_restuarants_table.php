<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestuarantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restuarants', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_name');
            $table->string('phone');
            $table->string('email');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('address');
            $table->string('meta_tag');
            $table->string('meta_description');
            $table->string('meta_keyword');
            $table->string('logo');
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('restuarants');
    }
}
