<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoning', function (Blueprint $table) {
            $table->id();
            $table->string('zone_name');
            $table->text('address');
            $table->text('link_map');
            $table->timestamps();
        });

        Schema::create('zoning_strategic_location', function (Blueprint $table) {
            $table->id();
            $table->integer('zoning_id');
            $table->string('strategic_location');
            $table->integer('distance');
            $table->string('distance_type');  //KM, M, CM dll
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
        Schema::dropIfExists('zoning_strategic_location');
        Schema::dropIfExists('zoning');
    }
}
