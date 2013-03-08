<?php
class Bracket_Controller extends Base_Controller 
{
	public $restful = true;

	public function get_index()
	{
		// $testUsers = array(  'Fez', 'Stephen Hyde', 'Eric Forman', 'Laura Pinciotti', 'Redd Forman', 'Jackie Berkhart', 'Kitty Forman', 'Bob Pinciotti', 'Kelso', 'Leo', 'Nina', 'Laurie', 'Midge Pinciotti', 'Jimmy Page', 'Mila Kunis'/*, 'Danny Masterson' */);

		$bracket = Bracket::find(1);
		$tournament = New Tournament($bracket);

		return View::make('bracket/bracket_v', array('tournament'=>$tournament));
	}

	public function get_reset()
	{
		Session::flush(); 							// reset the session.
		return Redirect::home();
	}

	/*  Create a new bracket */
	public function post_create()
	{
		$bracket = new Bracket;
		$bracket->name = Input::get('bracketName', date('F j, Y, g:i a'));
		$bracket->losses = 1; 												// It's one for now until double elimination is ready.
		$bracket->players_per_team = Input::get('bracketType', 1);			// Default to singles
		$bracket->access_str = strtoupper(Str::random(8, 'alpha'));

		// save the new bracket
		if($bracket->save())
		{
			
			Session::flush(); 							// reset the session.
			Session::put('bracketId', $bracket->id);	// Store the bracket ID in the session.

			return Redirect::to('bracket/add_players');
		}else{
			return Redirect::to('/');
		}
	}

	/* Add players to the bracket. */
	public function get_add_players()
	{
		$bracket = Bracket::find(Session::get('bracketId'));
		if(is_null($bracket)) return Redirect::to('/');

		return View::make('bracket/add_players', array('bracket'=>$bracket));
	}

	/* Create a new player via POST and associate it with the current bracket. */
	public function post_create_player()
	{
		$failUri = 'bracket/add_players';
		$successUri = $failUri;

		// Bracket to add to.
		$bracket = Bracket::find(Session::get('bracketId'));
		// fail if bracket does not exist.
		if(! $bracket) return Redirect::home();

		// Make sure they gave us a player name
		$name = Input::get('playerName');
		if(! $name) return Redirect::to($failUri);

		// Create the new player.
		$name = explode(' ', $name);
		$player = new Player;
		$player->first_name = $name[0];
		$player->last_name = isset($name[1]) ? $name[1] : false;

		// save the player
		if($player->save())
		{
			// associate this new person with the bracket
			$bracket->players()->attach($player);

			return json_encode($player);
		}else{
			return json_endcode(array());
		}		
	}

	/* Pick players for the bracket */
	public function get_pick_teams()
	{
		$bracket = Bracket::find(Session::get('bracketId'));

		// If the bracket doesn't exist
		// redirect back on home
		if( ! $bracket)
		{
			return Redirect::home(); 
		}
		
		// if there are no players 
		// redirect to add players
		if(! $bracket->players)
		{
			return Redirect::to('bracket/add_players')->with('error','You do not have enough players to make a bracket.');;
		}

		// Create the tournament object from the bracket.
		$tournament = new Tournament($bracket);

		// Pick teams for the tournament.
		if($teamCount = $tournament->pickTeams())
		{
			return Redirect::to('bracket/teams');
		}else{
			return Redirect::to('bracket/add_players')->with('error','You do not have enough players to make a bracket.');
		}
	}

	/* Show the teams in the bracket */
	public function get_teams()
	{
		$bracket = Bracket::find(Session::get('bracketId'));

		// If the bracket doesn't exist
		// redirect back on home
		if( ! $bracket){ return Redirect::home(); }

		// $this->layout->nest('content', 'bracket/teams_v', array('bracket'=>$bracket));
		return View::make('bracket/teams', array('bracket'=>$bracket));
	}

	/* Generate the tournament bracket for a bracket with teams in place */
	public function get_generate_tournament()
	{
		$bracket = Bracket::find(Session::get('bracketId'));
	
		// If the bracket doesn't exist
		// redirect back on home
		if( ! $bracket){ return Redirect::home(); }

		// if there are no players 
		// redirect to add players
		if(! $bracket->players){
			return Redirect::to('bracket/add_players')->with('error','You do not have enough players to make a bracket.');;
		}
		
		// if there are no players 
		// redirect to add players
		if(! $bracket->teams){
			return Redirect::to('bracket/add_players')->with('error','Pick teams to generate a new tournament bracket.');;
		}

		// create the tournament object
		$tournament = new Tournament($bracket);

		// build the bracket and update the DB.
		$tournament->buildBracket();

		return Redirect::to('bracket/tournament');
	}

	// View the tournament bracket for an existing tournament.
	public function get_tournament()
	{
		$bracket = Bracket::find(Session::get('bracketId'));
		
		// If the bracket doesn't exist
		// redirect back on home
		if( ! $bracket){ return Redirect::home(); }

		$tournament = new Tournament($bracket);

		return View::make('bracket/bracket', array('bracket' => $bracket, 'tournament' => $tournament));
	}

	// Set match winner
	public function get_set_match_winner($matchId, $teamId)
	{
		$bracket = Bracket::find(Session::get('bracketId'));
		$match = Match::find($matchId);
		$team = Team::find($teamId);

		// If the bracket doesn't exist
		// redirect back on home
		if( ! $bracket){ return Redirect::home(); }
		if( ! $match or ! $team){ return Redirect::to('bracket/tournament'); }

		// Create the tournament object
		$tournament = new Tournament($bracket);

		// advance this team to the next round or declare them the champ.
		$tournament->advanceTeam($match, $team);

		return Redirect::to('bracket/tournament');
	}

}