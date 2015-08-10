<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
	public function up()
	{
		Schema::create('photos', function (Blueprint $table) {
			$table->increments('id');
			$table->string('originalid');
			$table->integer('set_id');
			$table->string('url');
			$table->string('preview');
			$table->datetime('date');
			$table->integer('votes');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('photos');
	}
}
