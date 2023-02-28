<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewslettersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('newsletters', function(Blueprint $table)
		{
			$table->integer('newsletters_id', true);
			$table->string('title', 191);
			$table->text('content', 65535);
			$table->string('module', 191);
			$table->dateTime('date_added');
			$table->dateTime('date_sent')->nullable();
			$table->integer('status')->nullable();
			$table->integer('locked')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('newsletters');
	}

}
