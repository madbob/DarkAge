<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetsTable extends Migration
{
	public function up()
	{
		Schema::create('sets', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('subscriber_id');
			$table->integer('year');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('sets');
	}
}
