<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontEndThemeContentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('front_end_theme_content', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('headers', 65535);
			$table->text('carousels', 65535);
			$table->text('banners', 65535);
			$table->text('footers', 65535);
			$table->text('product_section_order', 65535);
			$table->text('cart', 65535);
			$table->text('news', 65535);
			$table->text('detail', 65535);
			$table->text('shop', 65535);
			$table->text('contact', 65535);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('front_end_theme_content');
	}

}
