<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DistractorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distractor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('letter')->collation('utf8_unicode_ci')->nullable();
            $table->integer('item_id');
			$table->integer('count_answers')->nullable();
			$table->double('discrimination')->nullable();
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
         Schema::drop('distractor');
    }
}
