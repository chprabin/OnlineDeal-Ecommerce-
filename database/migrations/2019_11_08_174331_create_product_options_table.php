<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('productId')->unsigned();
            $table->foreign('productId')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('optionId')->unsigned();
            $table->foreign('optionId')->references('id')->on('options')->onDelete('cascade');
            $table->unique(['productId','optionId']);
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
        Schema::dropIfExists('product_options');
    }
}
