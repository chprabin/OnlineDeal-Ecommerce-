<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRsMatrixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_matrix', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('itemId1')->unsigned();
            $table->foreign('itemId1')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('itemId2')->unsigned();
            $table->foreign('itemId2')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('sum')->default(0);
            $table->bigInteger('count')->default(0);
            $table->unique(['itemId1','itemId2']);
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
        Schema::dropIfExists('rs_matrix');
    }
}
