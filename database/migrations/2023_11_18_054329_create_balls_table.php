<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBallsTable extends Migration
{

    public function up()
    {
        Schema::create('balls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->float('volume');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('balls');
    }
}
