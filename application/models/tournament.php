<?php 
class Tournament extends MyModel {

	/* 
		Bracket object from the database
	*/
	public $bracket;
	
	/* 
		======================================================================== 
	*/
	
	/**
	 * Initialize the tournament bracket by insterting the bracket master object into the "bracket" property.
	 * 
	 * @param Bracket $bracket A bracket object
	 * @return
	 */
	public function __construct(Bracket $bracket)
	{
		// Set a few local vars.
		$this->bracket = $bracket;
	}

	/**
	 * pickTeams
	 * Take all players and assign teams as necessary. This is step one.
	 * Players array gets shuffled to assign random players.
	 * Does not define matches.  Only initial team assignment.
	 *
	 * 
	 * @return void
	 */
	public function pickTeams($random = true)
	{
		$players = $this->bracket->players;

		// Are there enough teams for a tournament?
		if(ceil(count($players) / $this->bracket->players_per_team) < 2)
		{
			return null;
		}

		// Setting the var random to true will shuffle the array of players.  
		// Otherwise it will be a first second, third fourth, in order. 
		if($random) shuffle($players); 	// put the players in a random order to assign them.

		// DELETE any existing teams in the bracket.  We're drawing new teams heeeaaahhhhhh
		$this->bracket->teams()->delete();

		for($i=0;$i<count($players);$i+=$this->bracket->players_per_team)
		{
			/*
				CREATE the new team.  You could name it if you want... 
			*/
			$team = Team::create(array('name'=>date('F j, Y, g:i a') ));

			/*
				ATTACH players to the newly created team.
				Every team has to have at least one.
			*/
			for($k=0;$k<$this->bracket->players_per_team;$k++) { 
				if(current($players) === false) continue;
				$team->players()->attach(current($players));
				next($players);
			}

			/*
				ASSOCIATE the new team with the bracket.
				This team now has all the players assigned
			*/
			$this->bracket->teams()->insert($team);
		}

		return count($this->bracket->teams);
	}
	
	/* BRACKET */
	
	/**
	 * buildBracket 
	 * Create the bracket for the tournament.
	 * This will add and associate all other fields / tables
	 * 
	 */
	public function buildBracket()
	{
		/*
			Calculate the number of matches for the first round.
			All we need is the first round, every round after that is half the amount of matches as the previous. (for single elim)
		*/
		$matchesThisRound = $this->firstRoundMatches();
		$teams = $this->bracket->teams;
		
		// assign matches for all rounds.	
		for($r=1;$r<=$this->getRoundCount();$r++)
		{	

			// INSERT the round data into the database and associate it with the bracket.
			$round = Round::create(array(
					'name' => $this->getRoundName($r),
					'index' => $r
				)
			);

			// associate the new round with the existing bracket.
			$this->bracket->rounds()->insert($round);

			// If this is the first round let's make it the "current_round", in the bracket table.
			if($r === 1){
				$this->bracket->current_round = $round->id;
				$this->bracket->save();
			}

			for($i=0;$i<$matchesThisRound;$i++)
			{
				// Create the match.
				$match = Match::create(array(
						'status' => ($r === 1) ? 'Ready to play' : 'Waiting for team assignment'
					)
				);

				// Associate the new match with the round.
				$round->matches()->insert($match);

				// Also associate the match with the current bracket.
				$this->bracket->matches()->attach($match);
				
				// If this is the first round then we can assign teams to the match.
				if($r===1)
				{
					// Add two teams to the match.
					for($c=0;$c<2;$c++)
					{
						if(current($teams)) {
							$match->teams()->attach(current($teams));
							next($teams);
						}
					}
				}
			}
			
			$matchesThisRound = $matchesThisRound / 2;	//  Half the people lost...
		}

		return count($this->bracket->matches);
	}

	/* ! TEAMS */

	/**
	 * advanceTeam 
	 * Advance a team to the next round.
	 * If the team is in the final roudn and should be declared the winner then we'll mark the winner.
	 * 
	 * @param Team index for the current round
	 * @return Match
	 */
	public function advanceTeam(Match $match, Team $team)
	{
		/*
			Thought process...

			The match passed was won by the team passed.
			- Mark this team as the winner of the match.
			- Change the status to complete.
			- Does the round need to auto advance to the next round? (are all matches for this round complete?)
			- What is the next game that this team should play?
				-- Is there a next game?  If not, is this the champion?  We could be dealing with a champion here people.  A champion.
		*/

		$rounds = $this->bracket->rounds;		// Easy access bruh
		$currentRound = $match->round;			// 2Easy2Sleazy
		$nextMatch = false;						// we may not need a next match at all if it's a champ.

		/*
			UPDATE completed_at and insert the winning_team_id
		*/
		$this->setMatchWinner($match, $team);
		$this->setMatchStatus($match, 'Complete');

		/* 
			Is this the last round?
		*/
		if($currentRound->index == count($rounds)) 
		{
			/*
				It is the last round so let's declare this team the winner.
			*/
			$this->bracket->winning_team_id = $team->id;
			$this->bracket->save();
		}else{
			/*
				Should we shift the bracket's current round to the next round
			*/
			$this->advanceRound($currentRound);

			/*
				Get the next match.
			*/
			$nextMatch = $this->nextMatch($match);

			// is this team already up for the next match?
			foreach($nextMatch->teams as $t)
			{
				if($t->id == $team->id) return $nextMatch;
			}

			// add the winning team to the next match and see how many teams are now assigned to the next match.
			$nextMatch->teams()->attach($team);

			// is there already a team assigned to the next match?
			$status = ($nextMatch->teams()->count() == $this->bracket->players_per_team) ? 'Ready to play' : 'Waiting for opponent';
			$this->setMatchStatus($nextMatch, $status);
		}

		return $nextMatch;
	}

	/* ! ROUNDS */

	/**
	 * getRoundName 
	 * Quick way to get the "name" of the round based on current round param.
	 * 
	 */
	public function getRoundName($r)
	{
		$rounds = $this->getRoundCount();

		if($r == $rounds){
			return 'Finals';
		}elseif($r == ($rounds - 1)){
			return 'Semi Finals';			
		}elseif($r === 1){
			return 'First Round';
		}
		return 'Round '.str_pad($r,2,'0',STR_PAD_LEFT);
	}
	
	/**
	 * getRoundCount 
	 * Get the rounds for the tournament given the number of teams.
	 * Rounds ^ 2 = teamCount
	 * 
	 */
	private function getRoundCount()
	{
		return ceil(log(count($this->bracket->teams), 2));
	}
	
	/**
	 * nextRound 
	 * Returns false if there is no next round (you're in the championship round.)
	 * Get the round that is one index higher than the current round.
	 * 
	 * @param Round $round Current round.
	 * @return Round
	 */
	public function nextRound(Round $round)
	{
		return Round::where('bracket_id','=',$round->bracket()->first()->id)->where('index','=',($round->index + 1))->first();
	}

	/**
	 * Move the bracket's current_round marker to the next round.
	 *
	 * @return Round $nextRound The next (now current) round object.
	 **/
	public function advanceRound(Round $currentRound)
	{
		$roundInProgress = false;
		$nextRound = false;

		/*
			Are all the matches for this round completed?
		*/
		foreach($currentRound->matches as $m)
		{
			if( ! $m->completed_at) $roundInProgress = true;
		}

		if( ! $roundInProgress){
			$nextRound = $this->nextRound($currentRound);
			if($nextRound)
			{		
				$this->bracket->current_round = $nextRound->id;
				$this->bracket->save();
			}
		}

		return $nextRound;
	}
	/* ! Matches */

	/**
	 * getMinMatches 
	 * Set the minimum number of matches possible for the tournament
	 * 
	 */
	private function getMinMatches()
	{
		return (count($this->bracket->teams) * $this->bracket->losses) - $this->bracket->losses;
	}
	
	/**
	 * getMaxMatches 
	 * Set the maximum number of matches possible for the tournament
	 * 
	 */
	private function getMaxMatches()
	{
		return (count($this->bracket->teams) * $this->bracket->losses) - 1;
	}	

	/**
	 * firstRoundMatches
	 * Get the number of matches for a given round.
	 */
	public function firstRoundMatches()
	{
		return ceil((count($this->bracket->players)/$this->bracket->players_per_team)/pow(2,1));
	}

	/**
	 * Get the next match based off of the match that was just completed.
	 *
	 * @param Match $currentMatch
	 * @return Match $nextMatch THe next match object.	
	 **/
	public function nextMatch(Match $currentMatch)
	{
		// Current Round
		$round = $currentMatch->round;
		$index = false;

		// Baerf
		foreach($round->matches as $i => $m)
		{
			if($m->id == $currentMatch->id) 
			{
				$index = $i;
			}
		}

		// if it doesn't have an index for some reason then error out.
		if($index === false){ return false; }
		
		// the next match index in the next round of the winners bracket is the index / 2;
		$index = floor($index / 2);
		
		// Get the next round matches
		$nextRoundMatches = $this->nextRound($round)->matches;

		return isset($nextRoundMatches[$index]) ? $nextRoundMatches[$index] : false;
	}

	/**
	 * Set a status for a match.
	 *
	 * @return void
	 **/
	public function setMatchStatus(Match $match, $status)
	{
		$match->status = $status;
		return $match->save();
	}

	/**
	 * Set a winner for a match.
	 *
	 * @return void
	 **/
	public function setMatchWinner(Match $match, Team $team)
	{
		$match->completed_at = date('Y-m-d H:i:s', time());
		$match->winning_team_id = $team->id;
		return $match->save();
	}
}