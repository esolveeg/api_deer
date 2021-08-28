<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('isbn')->unique();
            $table->string('productName');
            $table->string('productImage');
            $table->text('productDesc')->nullable();
            $table->boolean('latest')->default(false);
            $table->boolean('featured')->default(false);
            $table->boolean('bestseller')->default(false);
            $table->unsignedFloat('price');
            $table->boolean('acitve')->default(true);
            $table->boolean('inStock')->default(true);
            $table->unsignedBigInteger('groupId');
            $table->foreign('groupId')->references('id')->on('groups');
            $table->unsignedBigInteger('authorId');
            $table->foreign('authorId')->references('id')->on('authors');
            $table->timestamps();
            $table->softDeletes();
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
