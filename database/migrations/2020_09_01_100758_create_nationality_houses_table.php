<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNationalityHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nationality_houses', function (Blueprint $table) {
            $table->string('id')->unique()->nullable();
            $table->string('id_uec_order')->nullable();
            $table->string('seat_name_mm')->nullable();
            $table->string('seat_name_eng')->nullable();
            $table->string('hluttaw_type_mm')->nullable();
            $table->string('hluttaw_type_eng')->nullable();
            $table->string('election_year')->nullable();
            $table->string('area_id')->nullable();
            $table->string('region_id')->nullable();
            $table->timestamps('');
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
        Schema::dropIfExists('nationality_houses');
    }
}
