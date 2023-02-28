<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_notifications', function(Blueprint $table)
		{
			$table->integer('products_id');
			$table->integer('customers_id');
			$table->dateTime('date_added');
			$table->primary(['products_id','customers_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_notifications');
	}

}
