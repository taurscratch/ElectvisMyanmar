<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNationalityResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nationality_results', function (Blueprint $table) {
            $table->string('id')->unique()->nullable();
            $table->string('nationalityhouse_id');
            $table->string('candidate_id');
            $table->string('party_id');
            $table->string('valid_ps_vote')->nullable();
            $table->string('valid_adv_vote')->nullable();
            $table->string('election_date')->nullable();
            $table->string('election_type')->nullable();
            $table->string('is_election_cancelled')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('nationality_results');
    }
}
