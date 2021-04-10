<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email',300);
            $table->string('firstname',300);
            $table->string('lastname',300);
            $table->bigInteger('userId')->unsigned();
            $table->bigInteger('countryId')->unsigned();
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('countryId')->references('id')->on('countries')->onDelete('cascade');
            $table->string('city',300);
            $table->string('street',300);
            $table->decimal('total',10,2);
            $table->bigInteger('stateId')->unsigned();
            $table->foreign('stateId')->references('id')->on('order_states')->onDelete('cascade');
            $table->string('payment_batch_number')->unique();
            $table->string('payeer_account',191);
            $table->string('payee_account',191);
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
        Schema::dropIfExists('orders');
    }
}
