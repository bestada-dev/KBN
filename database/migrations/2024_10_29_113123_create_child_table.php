<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_setting_id')->constrained('landing_page_settings')->onDelete('cascade');
            $table->string('title1')->nullable();
            $table->string('title1_id')->nullable();
            $table->string('title2_id')->nullable();
            $table->string('title1_en')->nullable();
            $table->string('title2_en')->nullable();
            $table->text('description1')->nullable();
            $table->text('description1_id')->nullable();
            $table->text('description2_id')->nullable();
            $table->text('description1_en')->nullable();
            $table->text('description2_en')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('points');
    }
}
