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
            $table->double('mark')->nullable();
            $table->integer('test_score')->nullable();
			$table->integer('student_id');
			$table->integer('test_id');
			$table->integer('test_score_rem')->nullable();
			$table->engine = 'InnoDB';
			$table->charset = 'utf8';
			$table->collation = 'utf8_unicode_ci';
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
