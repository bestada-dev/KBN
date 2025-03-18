<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->integer('zona_id');
            $table->text('property_address');
            $table->text('property_location_link');
            $table->text('block');
            $table->string('type'); //Bonded & General
            $table->string('land_area');
            $table->string('building_area')->nullable();
            $table->string('type_upload'); // link & upload_vidio
            $table->text('url')->nullable();
            $table->text('vidio')->nullable();
            $table->text('desc');
            $table->string('layout');
            $table->string('status'); //Available & NotAvailable
            $table->integer('total_viewer'); //Available & NotAvailable
            $table->timestamps();
        });

        Schema::create('property_facility', function (Blueprint $table) {
            $table->id();
            $table->integer('property_id');
            $table->string('facility');
            $table->timestamps();
        });

        Schema::create('property_attach', function (Blueprint $table) {
            $table->id();
            $table->integer('property_id');
            $table->string('detail_photo');
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
        Schema::dropIfExists('property_attach');
        Schema::dropIfExists('property_facility');
        Schema::dropIfExists('property');
    }
}
