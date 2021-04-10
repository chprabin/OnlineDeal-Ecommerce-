<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',300);
            $table->bigInteger('brandId')->unsigned();
            $table->foreign('brandId')->references('id')->on('brands')->
            onDelete('cascade');
            $table->decimal('price',10,2)->default(0.00);
            $table->integer('sold_count')->default(0);
            $table->integer('stock')->default(1);
            $table->text('desc');
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
        Schema::dropIfExists('products');
    }
}
