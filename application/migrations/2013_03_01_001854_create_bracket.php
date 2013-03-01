<?php

class Create_Bracket {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('brackets', function($table) {
			 // auto incremental id (PK)
			$table->increments('id');
			 // varchar 32
			$table->boolean('active');
			 // created_at | updated_at DATETIME
			$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('brackets');
	}

}