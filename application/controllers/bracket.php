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

	/**
	 * Create a new bracket.
	 * This bracket has no players. It is the shell bracket with tournament settings.
	 *
	 * @return void
	 **/
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

	/**
	 * Add players to the bracket.
	 *
	 * @return void
	 **/
	public function get_add_players()
	{
		$bracket = Bracket::find(Session::get('bracketId'));
		if(is_null($bracket)) return Redirect::to('/');

		return View::make('bracket/add_players', array('bracket'=>$bracket));
	}

	/** Create a new player via POST and associate it with the current bracket. */
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

			return Redirect::to($successUri);
		}else{
			return Redirect::to($failUri);
		}		
	}

	/* Pick players for the bracket */
	public function get_pick_teams()
	{
		$bracket = Bracket::find(Session::get('bracketId'));

		// If the bracket doesn't exist
		// redirect back on home
		if( ! $bracket){ return Redirect::home(); }
		
		// if there are no players 
		// redirect to add players
		if(! $bracket->players){
			return Redirect::to('bracket/add_players');
		}

		// Create the tournament object from the bracket.
		$tournament = new Tournament($bracket);

		// Pick teams for the tournament.
		$teamCount = $tournament->pickTeams();

		if($teamCount > 1)
		{
			return Redirect::to('bracket/teams');
		}else{
			return Redirect::to('bracket/add_players');
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
}