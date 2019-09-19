<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('test_id');
            $table->string('right_answer')->collation('utf8_unicode_ci')->nullable();
			$table->double('difficulty')->nullable();
			$table->double('discrimination')->nullable();
			$table->integer('number')->nullable();
			$table->integer('number_correct')->nullable();
			$table->integer('number_incorrect')->nullable();
			$table->double('mean_correct')->nullable();
			$table->double('mean_incorrect')->nullable();
			$table->double('rpbis')->nullable();
			$table->double('disperse_rem')->nullable();
			$table->double('mean_rem')->nullable();
			$table->double('kr20_rem')->nullable();
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
         Schema::drop('item');
    }
}
