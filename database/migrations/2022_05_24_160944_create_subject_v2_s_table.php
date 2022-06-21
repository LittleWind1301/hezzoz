<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectV2STable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_v2_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subject_name');
            $table->string('subject_code');
            $table->boolean('subject_status');
            $table->integer('numCredit');
            $table->integer('course_id');
            $table->softDeletes();
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
        Schema::dropIfExists('subject_v2_s');
    }
}
