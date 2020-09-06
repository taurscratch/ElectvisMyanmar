<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->string('id')->unique()->nullable();
            $table->string('uec_id')->nullable();
            $table->string('uec_party_name')->nullable();
            $table->string('reg_req_date')->nullable();
            $table->string('req_req_approved_date')->nullable();
            $table->string('reg_date');
            $table->string('reg_approved_date');
            $table->string('hq_address');
            $table->string('competing_area');
            $table->string('contact_information');
            $table->string('reg_num');
            $table->string('announcement_id');
            $table->string('remark');
            $table->string('original_uec_url');
            $table->string('party_policy_url');
            $table->string('party_flag_url');
            $table->string('party_logo_url');
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
        Schema::dropIfExists('parties');
    }
}
