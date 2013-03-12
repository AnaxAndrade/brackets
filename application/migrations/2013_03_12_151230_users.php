<?php

class Users {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// create the users table
		Schema::create('users', function($table) {
			$table->engine = 'InnoDB';
		    $table->increments('id');
		    $table->string('email', 255)->unique();
		    $table->string('password', 100);
		    $table->integer('player_id')->unsigned()->nullable();
			$table->foreign('player_id')->references('id')->on('players')->on_delete('set NULL');
		    $table->timestamps();
		});

		// drop the email col on the players table.
		Schema::table('players', function($table) {
		    $table->drop_column('email');
		});


	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('players', function($table) {
		    $table->string('email', 255)->unique();
		});
		Schema::drop('users');
	}

}