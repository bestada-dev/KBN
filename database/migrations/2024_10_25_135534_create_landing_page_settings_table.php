<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandingPageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landing_page_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('section');
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
            $table->string('photo')->nullable();
            $table->string('video')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('page_type'); // e.g., 'home', 'about'
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
        Schema::dropIfExists('landing_page_settings');
    }
}
