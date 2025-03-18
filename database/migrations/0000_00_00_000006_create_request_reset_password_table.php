<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestResetPasswordTable extends Migration
{

    public function up()
    {
        Schema::create('request_reset_password', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('token');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('request_reset_password');
    }
}
