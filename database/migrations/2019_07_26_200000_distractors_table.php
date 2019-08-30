<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DistractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distractors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('letter')->collation('utf8_unicode_ci');
            $table->integer('item_id');
			$table->integer('count_answers');
			$table->float('discrimination');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('distractors');
    }
}
