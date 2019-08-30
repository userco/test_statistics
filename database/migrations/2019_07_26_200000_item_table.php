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
            $table->string('right_answer')->collation('utf8_unicode_ci');
			$table->float('difficulty');
			$table->float('discrimination');
			$table->integer('number');
			$table->integer('number_correct');
			$table->integer('number_incorrect');
			$table->float('mean_correct');
			$table->float('mean_incorrect');
			$table->float('rpbis');
			$table->float('kr20_rem');
			$table->float('test_score_rem');
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
