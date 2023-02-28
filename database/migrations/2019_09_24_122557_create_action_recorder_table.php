<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActionRecorderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('action_recorder', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('module', 191)->index('idx_action_recorder_module');
			$table->integer('user_id')->nullable()->index('idx_action_recorder_user_id');
			$table->string('user_name', 191)->nullable();
			$table->string('identifier', 191)->index('idx_action_recorder_identifier');
			$table->char('success', 1)->nullable();
			$table->dateTime('date_added')->index('idx_action_recorder_date_added');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('action_recorder');
	}

}
