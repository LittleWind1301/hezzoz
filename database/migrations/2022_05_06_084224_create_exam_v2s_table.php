<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamV2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_v2s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('exam_title');
            $table->integer('class_id');
            $table->integer('subject_id');
            $table->integer('exam_duration');
            $table->dateTime('exam_time_start');
            $table->integer('total_question');
            $table->dateTime('exam_result_date');
            $table->string('exam_status');
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
        Schema::dropIfExists('exam_v2s');
    }
}
