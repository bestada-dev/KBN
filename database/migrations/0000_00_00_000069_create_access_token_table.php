<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessTokenTable extends Migration
{

    public function up()
    {
        Schema::create('access_token', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('access_token');
    }
}
