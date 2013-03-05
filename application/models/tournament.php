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

		/* 
			Setting the var random to true will shuffle the array of players.  
			Otherwise it will be a first second, third fourth, in order. 
		*/
		if($random) shuffle($players); 	// put the players in a random order to assign them.

		/*
			DELETE any existing teams in the bracket.  We're drawing new teams heeeaaahhhhhh
		*/
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

		return count($this->teams);
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
		
		// assign matches for all rounds.
		for($r=1;$r<=$this->getRoundCount();$r++)
		{	
			/*
				INSERT the round data into the database and associate it with the bracket.
			*/
			$round = Round::create(array(
					'name' => $this->getRoundId($r),
					'index' => $r
				)
			);
			// associate the new round with the existing bracket.
			$this->bracket->rounds()->insert($round);

			/* If this is the first round let's make it the "current_round", in the bracket table */
			if($r === 1){
				$this->bracket->current_round = $round->id;
				$this->bracket->save();
				$status = 'Ready to play';
			}else{
				$status = 'Waiting for team assignment';
			}

			for($i=0;$i<$matchesThisRound;$i++)
			{
				/*
					Create the inital match.
				*/
				$match = Match::create(array('status' => $status));

				/*
					Associate the new match with the round.
				*/
				$round->matches()->insert($match);

				/*
					Associate this match with the current bracket...
				*/
				$this->bracket->matches()->attach($match);
				
				/*
					We can assign matches for the first round.  
					Here we are assigning teams by index.  
					Example
					team 1 vs team 2
					team 3 vs team 4

					Teams can be created at random using the pickTeams(true) method.
				*/
				if($r===1)
				{
					$teams = $this->bracket->teams;
					/*
						Add two teams to the match.
					*/
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
	public function advanceTeam($matchId, $teamId)
	{
		/*
			Bracket variables
		*/
		$match = Match::find($matchId);
		$team = Team::find($teamId);
		$rounds = $this->bracket->rounds()->get();

		/*
			UPDATE completed_at and insert the winning_team_id
		*/
		$this->setMatchWinner($match, $team);
		$this->setMatchStatus($match, 'complete');

		/* 
			If the current round is the last round 
				dthen this team has won the championship... 
			UNLESS
			Unless it's double elimination.  
			- A team with no losses could lose in the championship round and still be in.   
			- A team witn one loss could win the first championship game and need to proceed to the next.
		*/
		if($this->bracket->current_round == count(rounds)) 
		{
				$this->bracket->winning_team_id = $team->id;
				$this->bracket->save();
		}else{
			$nextMatch = $this->nextMatch($match);

			// add the winning team to the next match and see how many teams are now assigned to the next match.
			$nextMatch->teams()->attach($team);

			// is there already a team assigned to the next match?
			$status = ($nextMatch->teams()->count() == $this->bracket->players_per_team) ? 'ready to play' : 'waiting for opponent';
			$this->setMatchStatus($nextMatch, $status);
		}

		return $nextMatch;
	}

	/* ! ROUNDS */

	/**
	 * getRoundId 
	 * Quick way to get the "name" of the round based on current round param.
	 * 
	 */
	public function getRoundId($r)
	{
		$rounds = count($this->bracket->rounds);

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
	 * 
	 * @param Round $round Current round.
	 * @return Round
	 */
	public function nextRound(Round $round)
	{
		return Round::where('bracket_id','=',$round->bracket()->first()->id)->where('index','=',($round->index + 1))->first();
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
	 * @param Match $completedMatch
	 * @return Match $nextMatch THe next match object.	
	 **/
	public function nextMatch(Match $completedMatch)
	{
		// Current Round
		$round = $completedMatch->round;
		$index = false;
		$roundComplete = true;

		if(!$index = array_search($completedMatch, $round->matches))
		{
			return false;
		}
		/*
			If all matches in this round are complete then let's set the bracket's current round to the new round.
		*/
		if($roundComplete)
		{

		}

		$index = floor($index / 2);
		/*
			Get the next round
		*/
		$nextRound = $this->nextRound($round);
		$nextRoundMatches = $nextRound->matches();

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