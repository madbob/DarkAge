<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSet extends Migration
{
	public function up()
	{
		Schema::table('sets', function(Blueprint $table)
		{
			$table->dropColumn(['year']);
			$table->date('startdate');
			$table->date('enddate');
		});
	}

	public function down()
	{
		Schema::table('sets', function(Blueprint $table)
		{
			$table->dropColumn(['startdate']);
			$table->dropColumn(['enddate']);
			$table->integer('year');
		});
	}
}
