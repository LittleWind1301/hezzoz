<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionOfCodeExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_of_code_exams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('questionOfCodeExamId');
            $table->integer('optionNumber');
            $table->boolean('isCorrect');
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
        Schema::dropIfExists('option_of_code_exams');
    }
}
