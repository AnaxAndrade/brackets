<?php

class Add_Bracket_Created_By {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// crated the created_by col on the brackets table and foreign key it to the players ID col.
		Schema::table('brackets', function($table) {
		    $table->integer('created_by')->unsigned()->nullable();
			$table->foreign('created_by')->references('id')->on('players')->on_delete('set null');
		});

	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		// drop the created_by col on the brackets table.
		Schema::table('players', function($table) {
		    $table->drop_column('email');
		});
	}

}