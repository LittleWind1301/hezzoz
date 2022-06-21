<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamV3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_v3s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('exam_title');
            $table->integer('class_id');
            $table->integer('subject_id');
            $table->integer('exam_duration');
            $table->integer('total_question');
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
        Schema::dropIfExists('exam_v3s');
    }
}
