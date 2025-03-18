<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number');
            $table->string('email', 191)->unique();
            $table->string('password');
            $table->string('user_status_id')->nullable()->default(1); // 1 Active 0 non active
            $table->boolean('is_new')->default(true);
            $table->string('token')->nullable();
            $table->boolean('is_admin')->default(true);
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

