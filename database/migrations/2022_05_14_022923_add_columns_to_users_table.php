<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cmnd');
            $table->date('dateOfBirth')->nullable();
            $table->string('gender');
            $table->string('phoneNum');
            $table->string('country');
            $table->string('address');
            $table->string('religion');
            $table->string('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cmnd');
            $table->date('dateOfBirth')->nullable();
            $table->string('gender');
            $table->string('phoneNum');
            $table->string('country');
            $table->string('address');
            $table->string('religion');
            $table->string('image');
        });
    }
}
