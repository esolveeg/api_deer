<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('groupName')->unique();
            $table->string('icon')->nullable();
            $table->string('groupImage')->nullable();
            $table->unsignedBigInteger('groupId')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('featured')->default(false);
            $table->boolean('home')->default(false);
            $table->foreign('groupId')->references('id')->on('groups');
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
        Schema::dropIfExists('groups');
    }
}
