<?php

class Create_Bracket {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		/* BRACKET */
		Schema::create('brackets', function($table)
		{
			$table->engine = 'InnoDB';
		    $table->increments('id');
		    $table->string('name')->nullable();
		    $table->integer('players_per_team');
		    $table->integer('losses');
		    $table->integer('current_round')->nullable();
		    $table->integer('winning_team_id')->unsigned()->nullable();
		    $table->timestamps();
		});

		/* ROUNDS */
		Schema::create('rounds', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->string('name');
			$table->integer('index');
			$table->integer('bracket_id')->unsigned()->nullable();
			$table->foreign('bracket_id')->references('id')->on('brackets')->on_delete('cascade');
			$table->timestamps();
		});

		/* TEAMS */
		Schema::create('teams', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->string('name')->nullable();
			$table->integer('bracket_id')->unsigned()->nullable();
			$table->foreign('bracket_id')->references('id')->on('brackets')->on_delete('cascade');
			$table->timestamps();
		});

		/* MATCHES */
		Schema::create('matches', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->string('status');
			$table->timestamp('completed_at')->nullable();
			$table->integer('round_id')->unsigned()->nullable();
			$table->foreign('round_id')->references('id')->on('rounds')->on_delete('cascade');
			$table->integer('winning_team_id')->unsigned()->nullable();
			$table->foreign('winning_team_id')->references('id')->on('teams')->on_delete('set NULL');
			$table->timestamps();
		});

		/* PLAYERS */
		Schema::create('players', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->string('first_name');
			$table->string('last_name')->nullable();
			$table->string('email')->nullable();
			$table->timestamps();
		});


		/*
			CREATE pivot tables.
		*/

		// bracket pivots
		Schema::create('bracket_match', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('bracket_id')->unsigned();
			$table->foreign('bracket_id')->references('id')->on('brackets')->on_delete('cascade');
			$table->integer('match_id')->unsigned();
			$table->foreign('match_id')->references('id')->on('matches')->on_delete('cascade');
			$table->timestamps();
		});
		Schema::create('bracket_player', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('bracket_id')->unsigned();
			$table->foreign('bracket_id')->references('id')->on('brackets')->on_delete('cascade');
			$table->integer('player_id')->unsigned();
			$table->foreign('player_id')->references('id')->on('players')->on_delete('cascade');
			$table->timestamps();
		});

		// team pivots
		Schema::create('match_team', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('match_id')->unsigned();
			$table->foreign('match_id')->references('id')->on('matches')->on_delete('cascade');
			$table->integer('team_id')->unsigned();
			$table->foreign('team_id')->references('id')->on('teams')->on_delete('cascade');
			$table->timestamps();
		});
		Schema::create('player_team', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('player_id')->unsigned();
			$table->foreign('player_id')->references('id')->on('players')->on_delete('cascade');
			$table->integer('team_id')->unsigned();
			$table->foreign('team_id')->references('id')->on('teams')->on_delete('cascade');
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
		Schema::drop('player_team');
		Schema::drop('match_team');
		Schema::drop('bracket_player');
		Schema::drop('bracket_match');

		Schema::drop('players');
		Schema::drop('teams');
		Schema::drop('matches');
		Schema::drop('rounds');
		Schema::drop('brackets');
	}

}