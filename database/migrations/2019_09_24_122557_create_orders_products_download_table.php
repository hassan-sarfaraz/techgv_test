<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersProductsDownloadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders_products_download', function(Blueprint $table)
		{
			$table->integer('orders_products_download_id', true);
			$table->integer('orders_id')->default(0)->index('idx_orders_products_download_orders_id');
			$table->integer('orders_products_id')->default(0);
			$table->string('orders_products_filename', 191)->default('');
			$table->integer('download_maxdays')->default(0);
			$table->integer('download_count')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders_products_download');
	}

}
