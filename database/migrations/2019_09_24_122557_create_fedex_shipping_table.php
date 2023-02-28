<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFedexShippingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fedex_shipping', function(Blueprint $table)
		{
			$table->integer('fedex_id', true);
			$table->string('title', 100);
			$table->string('user_name', 100);
			$table->string('password', 100);
			$table->string('parcel_height', 100);
			$table->string('parcel_width', 100);
			$table->string('person_name', 100);
			$table->string('company_name', 100);
			$table->string('phone_number', 100);
			$table->string('address_line_1', 100);
			$table->string('address_line_2', 100);
			$table->string('country', 100);
			$table->string('state', 100);
			$table->string('post_code', 100);
			$table->string('city', 100);
			$table->string('no_of_package', 100);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fedex_shipping');
	}

}
