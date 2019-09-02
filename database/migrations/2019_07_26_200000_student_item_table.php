<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StudentItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_item', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('item_score')->nullable();
            $table->string('answer')->collation('utf8_unicode_ci')->nullable();
            $table->integer('item_id');
            $table->integer('student_id');
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
         Schema::drop('student_item');
    }
}
