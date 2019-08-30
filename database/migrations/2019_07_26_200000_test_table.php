<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('count_distractors');
            $table->integer('students_count');
			$table->integer('items_count');
			$table->integer('mean');
			$table->integer('sd');
			$table->integer('disperse');
			$table->integer('min_bal');
			$table->integer('max_bal');
			$table->integer('kr20');
			$table->integer('sem');
			$table->integer('median');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('test');
    }
}
