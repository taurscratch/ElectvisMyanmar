<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCensusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('censuses', function (Blueprint $table) {
            $table->string('id')->unique()->nullable();
            $table->string('area_id')->nullable();
            $table->integer('total_population')->nullable();
            $table->integer('male_population')->nullable();
            $table->integer('female_population')->nullable();
            $table->integer('urban_population')->nullable();
            $table->integer('urban_male_population')->nullable();
            $table->integer('urban_female_population')->nullable();
            $table->integer('rural_population')->nullable();
            $table->integer('rural_male_population')->nullable();
            $table->integer('rural_female_population')->nullable();
            $table->integer('population_0_14')->nullable();
            $table->integer('population_15_64')->nullable();
            $table->integer('population_65_above')->nullable();
            $table->integer('population_10_17')->nullable();
            $table->integer('population_18_above')->nullable();
            $table->integer('total_population_15_above')->nullable();
            $table->integer('total_literate_population_15_above')->nullable();
            $table->integer('total_literacy_rate_15_above')->nullable();
            $table->integer('total_illiterate_population_15_above')->nullable();
            $table->integer('male_population_15_above')->nullable();
            $table->integer('male_literate_population_15_above')->nullable();
            $table->integer('male_literacy_rate_15_above')->nullable();
            $table->integer('male_illiterate_population_15_above')->nullable();
            $table->integer('female_population_15_above')->nullable();
            $table->integer('female_literate_population_15_above')->nullable();
            $table->integer('female_literacy_rate_15_above')->nullable();
            $table->integer('female_illiterate_population_15_above')->nullable();
            $table->integer('urban_population_15_above')->nullable();
            $table->integer('urban_literate_population_15_above')->nullable();
            $table->integer('urban_literacy_rate_15_above')->nullable();
            $table->integer('urban_illiterate_population_15_above')->nullable();
            $table->integer('urban_male_population_15_above')->nullable();
            $table->integer('urban_male_literate_population_15_above')->nullable();
            $table->integer('urban_male_literacy_rate_15_above')->nullable();
            $table->integer('urban_male_illiterate_population_15_above')->nullable();
            $table->integer('urban_female_population_15_above')->nullable();
            $table->integer('urban_female_literate_population_15_above')->nullable();
            $table->integer('urban_female_literacy_rate_15_above')->nullable();
            $table->integer('urban_female_illiterate_population_15_above')->nullable();
            $table->integer('rural_population_15_above')->nullable();
            $table->integer('rural_literate_population_15_above')->nullable();
            $table->integer('rural_literacy_rate_15_above')->nullable();
            $table->integer('rural_illiterate_population_15_above')->nullable();
            $table->integer('rural_male_population_15_above')->nullable();
            $table->integer('rural_male_literate_population_15_above')->nullable();
            $table->integer('rural_male_literacy_rate_15_above')->nullable();
            $table->integer('rural_male_illiterate_population_15_above')->nullable();
            $table->integer('rural_female_population_15_above')->nullable();
            $table->integer('rural_female_literate_population_15_above')->nullable();
            $table->integer('rural_female_literacy_rate_15_above')->nullable();
            $table->integer('rural_female_illiterate_population_15_above')->nullable();
            $table->integer('total_population_5_29')->nullable();
            $table->integer('total_currently_school_5_29')->nullable();
            $table->integer('total_previously_school_5_29')->nullable();
            $table->integer('total_never_school_5_29')->nullable();
            $table->integer('male_population_5_29')->nullable();
            $table->integer('male_currently_school_5_29')->nullable();
            $table->integer('male_previously_school_5_29')->nullable();
            $table->integer('male_never_school_5_29')->nullable();
            $table->integer('female_population_5_29')->nullable();
            $table->integer('female_currently_school_5_29')->nullable();
            $table->integer('female_previously_school_5_29')->nullable();
            $table->integer('female_never_school_5_29')->nullable();
            $table->integer('census_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('censuses');
    }
}
