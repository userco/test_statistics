<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TestStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_student', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('mark');
            $table->integer('test_score');
			$table->integer('student_id');
			$table->integer('test_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('test_student');
    }
}
