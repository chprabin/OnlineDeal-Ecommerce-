<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unqiue();
            $table->string('code',191);
            $table->enum('type',['percent','amount'])->default('percent');
            $table->integer('amount')->default(0);
            $table->date('sdate');
            $table->date('edate');
            $table->integer('max_usage')->default(0);
            $table->decimal('min_total',10,2)->default(0.00);
            $table->integer('used_count')->default(0);
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('coupons');
    }
}
