<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileLecturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_lecturers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('lecturers_id');
            $table->string('lecturers_name');
            $table->string('cmnd');
            $table->date('dateOfBirth');
            $table->string('gender');
            $table->string('phoneNumber');
            $table->string('province');
            $table->string('address');
            $table->string('image')->nullable();
            $table->integer('faculty_id');
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
        Schema::dropIfExists('profile_lecturers');
    }
}
