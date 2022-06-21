<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamV4sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_v4s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('exam_title');
            $table->integer('subject_id');
            $table->integer('total_question');
            $table->dateTime('time_start');
            $table->dateTime('time_finish');
            $table->integer('numDifficult');
            $table->integer('numNormal');
            $table->integer('numEasy');
            $table->boolean('status');
            $table->integer('limitTime');
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
        Schema::dropIfExists('exam_v4s');
    }
}
