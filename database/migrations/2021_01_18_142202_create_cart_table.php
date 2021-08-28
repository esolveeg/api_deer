<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId')->nullable();
            $table->foreign('userId')->references('id')->on('users');
            $table->unsignedBigInteger('addressId')->nullable();
            $table->enum('status' , ['pending' , 'preparing', 'shipping' , 'shipped'])->default('pending');
            $table->foreign('addressId')->references('id')->on('addresses');
            $table->unsignedFloat('shipping')->nullable();
            $table->string('discountCode')->nullable();
            $table->dateTime('closedAt')->nullable();
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
        Schema::dropIfExists('cart');
    }
}
