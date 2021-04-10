<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('promoId')->unsigned();
            $table->foreign('promoId')->references('id')->on('promos')->onDelete('cascade');
            $table->bigInteger('productId')->unsigned();
            $table->foreign('productId')->references('id')->on('products')->onDelete('cascade');
            $table->unique(['promoId','productId']);
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
        Schema::dropIfExists('promo_products');
    }
}
