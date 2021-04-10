<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_filters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('categoryId')->unsigned();
            $table->foreign('categoryId')->references('id')->on('categories')->onDelete('cascade');
            $table->bigInteger('filterId')->unsigned();
            $table->foreign('filterId')->references('id')->on('filters')->onDelete('cascade');
            $table->unique(['categoryId','filterId']);
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
        Schema::dropIfExists('category_filters');
    }
}
