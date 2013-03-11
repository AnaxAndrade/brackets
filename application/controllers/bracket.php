<?php
class Bracket_Controller extends Base_Controller 
{
	public $restful = true;

	// this is the main bracket/tournament view.
	public function get_index()
	{
		// get the bracket.
		$bracket = Bracket::find(Session::get('bracketId'));

		// get the tournament object.
		$tournament = New Tournament($bracket);

		return View::make('bracket/bracket_v', array('tournament'=>$tournament,'bracket'=>$bracket));
	}

	// Create a new bracket 
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

			return Redirect::to('bracket/players');
		}else{
			return Redirect::to('/');
		}
	}

	// Add players to the bracket.
	public function get_players()
	{
		$bracket = Bracket::find(Session::get('bracketId'));
		if( ! $bracket){ return Redirect::home(); }

		return View::make('bracket/add_players', array('bracket'=>$bracket));
	}

	// Create a new player via POST and associate it with the current bracket.
	public function post_create_player()
	{
		$failUri = 'bracket/players';
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

	// Pick players for the bracket
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
			return Redirect::to('bracket/players')->with('error','You do not have enough players to make a bracket.');
		}

		// Create the tournament object from the bracket.
		$tournament = new Tournament($bracket);

		// Pick teams for the tournament.
		if($teamCount = $tournament->pickTeams())
		{
			return Redirect::to('bracket/teams');
		}else{
			return Redirect::to('bracket/players')->with('error','You do not have enough players to make a bracket.');
		}
	}

	// Show the teams in the bracket 
	public function get_teams()
	{
		$bracket = Bracket::find(Session::get('bracketId'));

		// If the bracket doesn't exist
		// redirect back on home
		if( ! $bracket){ return Redirect::home(); }

		// $this->layout->nest('content', 'bracket/teams_v', array('bracket'=>$bracket));
		return View::make('bracket/teams', array('bracket'=>$bracket));
	}

	// Generate the tournament bracket for a bracket with teams in place 
	public function get_generate_tournament()
	{
		$bracket = Bracket::find(Session::get('bracketId'));
	
		// If the bracket doesn't exist
		// redirect back on home
		if( ! $bracket){ return Redirect::home(); }

		// if there are no players 
		// redirect to add players
		if(! $bracket->players){
			return Redirect::to('bracket/players')->with('error','You do not have enough players to make a bracket.');;
		}
		
		// if there are no players 
		// redirect to add players
		if(! $bracket->teams){
			return Redirect::to('bracket/players')->with('error','Pick teams to generate a new tournament bracket.');;
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

		// If the bracket does not have any rounds then 
		// let's send them to the players screen.
		if( ! $bracket->current_round){ return Redirect::to('bracket/players')->with('error','Add players first then pick teams.'); }

		// generate the tournament object from the current bracket object.
		$tournament = new Tournament($bracket);

		return View::make('bracket/bracket', array('bracket' => $bracket, 'tournament' => $tournament));
	}

	// Set match winner
	public function post_set_match_winner($matchId)
	{
		$bracket = Bracket::find(Session::get('bracketId'));
		$match = Match::find($matchId);

		// If the bracket doesn't exist
		// redirect back on home
		if( ! $bracket){ return Redirect::home(); }
		if( ! $match){ return Redirect::to('bracket/tournament'); }

		$homeScore = Input::get('teamScoreHome');
		$awayScore = Input::get('teamScoreAway');

		if($homeScore < 5 && $awayScore < 5)
		{
			return Redirect::to('bracket/tournament')->with('error', 'Neither team has a high enough score to conclude the match.');
		}

		// Create the tournament object
		$tournament = new Tournament($bracket);

		$team = ($homeScore > $awayScore ? $match->teams[0] : $match->teams[1]);

		// advance this team to the next round or declare them the champ.
		$tournament->advanceTeam($match, $team);

		return Redirect::to('bracket/tournament');
	}

	// reset a bracket session.
	public function get_reset()
	{
		Session::flush(); 							// reset the session.
		return Redirect::home();
	}
}