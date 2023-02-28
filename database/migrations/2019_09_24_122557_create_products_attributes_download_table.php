<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsAttributesDownloadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_attributes_download', function(Blueprint $table)
		{
			$table->integer('products_attributes_id')->primary();
			$table->string('products_attributes_filename', 191)->default('');
			$table->integer('products_attributes_maxdays')->nullable()->default(0);
			$table->integer('products_attributes_maxcount')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_attributes_download');
	}

}
