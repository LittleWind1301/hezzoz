<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('student_id');
            $table->string('cmnd');
            $table->date('dateOfBirth');
            $table->string('gender');
            $table->string('phoneNumber');
            $table->string('province');
            $table->string('address');
            $table->integer('yearOfAdmission');
            $table->string('image')->nullable();
            $table->integer('faculty_id');
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
        Schema::dropIfExists('profile_students');
    }
}
