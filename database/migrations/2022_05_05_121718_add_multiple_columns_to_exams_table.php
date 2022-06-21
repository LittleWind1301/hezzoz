<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleColumnsToExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->integer('subject_id');
            $table->dateTime('exam_time_start');
            $table->integer('exam_total_question');
            $table->dateTime('exam_result_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->integer('subject_id');
            $table->dateTime('exam_time_start');
            $table->integer('exam_total_question');
            $table->dateTime('exam_result_date');
        });
    }
}
