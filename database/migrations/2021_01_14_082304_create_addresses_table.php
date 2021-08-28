<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('buildingNo');
            $table->string('rowNo');
            $table->string('flatNo');
            $table->string('street');
            $table->string('title');
            $table->string('remark')->nullable();
            $table->boolean('main')->default(false);
            $table->unsignedBigInteger('areaId');
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('phoneId');
            $table->foreign('AreaId')->references('id')->on('areas');
            $table->foreign('userId')->references('id')->on('users');
            $table->foreign('phoneId')->references('id')->on('phones');
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
        Schema::dropIfExists('addresses');
    }
}
