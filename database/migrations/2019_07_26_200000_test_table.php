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
            $table->integer('count_distractors')->nullable();
            $table->integer('students_count')->nullable();
			$table->integer('items_count')->nullable();
			$table->double('mean')->nullable();
			$table->double('sd')->nullable();
			$table->double('disperse')->nullable();
			$table->integer('min_bal')->nullable();
			$table->integer('max_bal')->nullable();
			$table->double('kr20')->nullable();
			$table->double('sem')->nullable();
			$table->double('median')->nullable();
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
         Schema::drop('test');
    }
}
