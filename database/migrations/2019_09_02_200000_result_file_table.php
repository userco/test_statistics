<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ResultFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_name')->collation('utf8_unicode_ci')->nullable();
            $table->integer('user_id');
			$table->integer('test_id');
			$table->date('result_date');
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
         Schema::drop('result_file');
    }
}
