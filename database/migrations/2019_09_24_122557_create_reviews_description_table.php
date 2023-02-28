<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReviewsDescriptionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reviews_description', function(Blueprint $table)
		{
			$table->integer('reviews_id');
			$table->integer('languages_id');
			$table->text('reviews_text', 65535);
			$table->primary(['reviews_id','languages_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reviews_description');
	}

}
