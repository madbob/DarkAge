<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribersTable extends Migration
{
	public function up()
	{
		Schema::create('subscribers', function (Blueprint $table) {
			$table->increments('id');
			$table->string('username');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('subscribers');
	}
}
