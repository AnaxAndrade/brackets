<?php 
class Tournament extends MyModel {
	
	/* 
		Number of players in the tournament 
	*/
	protected $playerCount = 0;
	
	/* 
		Number of teams in the tournament 
	*/
	protected $teamCount = 0;

	/* 
		Number of players per team
	*/
	protected $playersPerTeam = 0;
	
	/* 
		Minimum number of matches played for the tournament 
	*/
	protected $minMatches = 0;
	
	/* 
		Maximum number of matches to be played. 
	*/
	protected $maxMatches = 0;
	
	/* 
		Number of rounds the tournament will play 
	*/
	protected $rounds = 0;
	
	/* 
		Players in the tournament. 
	*/
	protected $players = array();
	
	/* 
		Teams that started the tournament 
	*/
	protected $teams = array();
	
	/* 
		Array of matches. 
	*/
	protected $matches = array();
	
	/* 
		Array of active matches. 
	*/
	protected $currentRound = 1;
	
	/* 
		Number of losses it takes before the team is out of the tournament 
	*/
	protected $lossesRequired = 1;

	/**
	 * 	A result object of winners.  (work this out for double elim)
	 */
	public $results;
	
	/* 
		======================================================================== 
	*/
	
	/**
	 * __construct 
	 * Initialize the bracket and draw teams.
	 * 
	 * @param array $playersArray An array of players by name string. Ex: array('Person Namerson', 'Harrison Namerson')
	 * @param int $playersPerTeam Number of players per team
	 * @param string $type Type of bracket.  singles or doubles.
	 * @return
	 */
	public function __construct(Bracket $bracket)
	{

		// assign the players array. (ex. Bracket::find($id)->players)
		$this->players = Bracket::find($bracket->id)->players;

		// assign the number of players ()
		$this->playerCount = count($this->players);
		$this->playersPerTeam = $bracket->players_per_team;
		$this->lossesRequired = $bracket->losses;		
		$this->results = new TournamentResults();
	}
	
	/**
	 * createBracket 
	 * Create the bracket for the tournament.
	 * 
	 */
	public function createBracket()
	{
		$this->calculateMatches();		
		$this->setRoundCount();
		$this->currentRound = 1;	// reset the current round to 1.  if needed...  doesn't hurt.

		$matchesThisRound = $this->matchesForRound($this->currentRound); 	// number of matches for the first round.
		
		// assign matches for all rounds.
		for($r=1;$r<=$this->rounds;$r++)
		{	
			for($i=0;$i<$matchesThisRound;$i++)
			{
				if($r===1)	// we can only assign the first round
				{
					$match = array(
						'teams' => array(
							current($this->teams), 
							next($this->teams) ? current($this->teams) : null
						),
						'winner' => false,
						'status' => 'pending'
					);
				}else{
					$match = array('status' => 'waiting team assignment', 'winner' => false, 'teams' => array());
				}
				$this->matches[$this->getRoundId($r)][$i] = $match;
				next($this->teams);
			}
			
			$matchesThisRound = $matchesThisRound / 2;	//  Half the people lost...
		}
	}

	/* ! TEAMS */

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
		$this->teams = array();
		/* 
			Setting the var random to true will shuffle the array of players.  
			Otherwise it will be a first second, third fourth, in order. 
		*/
		if($random) shuffle($this->players); 	// put the players in a random order to assign them.

		/* 
			Iterate through all players increasing by the total number of players per team. 
		*/
		for($i=0;$i<$this->playerCount;$i+=$this->playersPerTeam)
		{
			$team = array();	// temporary team array
			array_push($team, current($this->players));

			/*
				If there are more than 1 players per team let's cycle through as many as needed and append them to the temporary team array.  
				After the temporary team is created, add it to the object property for teams.
			*/
			for($k=1;$k<$this->playersPerTeam;$k++) { 
				array_push($team, next($this->players));
			}
			array_push($this->teams, array_filter($team)); // filter false results due to remainders in team assignment. (too few players)
			next($this->players);
		}

		// update team count.
		$this->teamCount = count($this->teams);
	}
	
	/**
	 * getTeamPlayerNames 
	 * Get the players from a given team
	 THIS DOESNT WORK YET
	 * 
	 * @param int $winningTeamIndex Index of the team from 'teams' property
	 * @return
	 */
	public function getTeamPlayerNames($winningTeamIndex)
	{
		return isset($this->teams[$winningTeamIndex]) ? $this->teams[$winningTeamIndex] : array();
	}
	
	/**
	 * advanceTeam 
	 * Advance a team to the next round.
	 * If the team is in the final roudn and should be declared the winner then we'll mark the winner.
	 * 
	 * @param Team index for the current round
	 */
	public function advanceTeam($matchIndex, $winningTeamIndex)
	{
		// if the team you're looking for doesn't exist let's return false.
		if(!isset($this->matches[$this->getRoundId($this->currentRound)][$matchIndex]['teams'][$winningTeamIndex])) return false;

		/*
			Advance a team to the next round.  
			If the current round is the last round then we'll mark them the winner.
		*/

		// the team that is advancing.
		$winningTeam = $this->match($matchIndex)['teams'][$winningTeamIndex];

		// update the match data to completed
		// set the status to complete and assign the 
		// winner to the index of the team that won...
		$this->setMatchStatus($matchIndex, 'complete');
		$this->setMatchWinner($matchIndex, $winningTeamIndex);
		
		/*
			The next round has half the number of matches as the current round.  
			To decide which match this round goes to we will divide and the current match index by 2 and toss the remainder.
		*/
		$nextMatchIndex = floor($matchIndex / 2);  
		
		/* 
			If the current round is the last round 
				dthen this team has won the championship... 
			UNLESS
			Unless it's double elimination.  
			- A team with no losses could lose in the championship round and still be in.   
			- A team witn one loss could win the first championship game and need to proceed to the next.
		*/
		if($this->currentRound == $this->rounds) 
		{
			// set the winner of the bracket. 
			// Does not set the winner of the match...
			$this->results->setWinner($winningTeam);
		}else{
			// Does this new match exist?  It should... 
			// If it didn't it would mean that the previous game was the last game.  
			// We would have detected that when comparing the current round with the total number of rounds.
			if( ! $this->match($nextMatchIndex, $this->currentRound+1)) { return false; }
			
			// add the winning team to the next match and see how many teams are now assigned to the next match.
			$teamCount = $this->addTeamToMatch($nextMatchIndex, $this->currentRound + 1, $winningTeam);

			// is there already a team assigned to the next match?
			if($teamCount == $this->playersPerTeam)
			{ 
				// There is already a team assigned to this match and the curent team is now playing them. 
				// Set the status to "ready to play" because both teams are here.
				$this->setMatchStatus($nextMatchIndex, 'ready to play', $this->currentRound+1);
			}else{
				// This is the first team assigned to the match.  
				// Waiting for another team to win in the previous round.
				$this->setMatchStatus($nextMatchIndex, 'waiting for opponent', $this->currentRound+1);
			}
		}
	}

	/* ! ROUNDS */

	/**
	 * Get the number of rounds for the current bracket.
	 *
	 * @return int
	 **/
	public function rounds()
	{
		return $this->rounds;
	}
	
	/**
	 * getRoundId 
	 * Quick way to get the "name" of the round based on current round param.
	 * 
	 */
	public function getRoundId($r = false)
	{
		$r = $r ? $r : $this->currentRound;
		
		if($r == $this->rounds){
			return 'Finals';
		}elseif($r == ($this->rounds - 1)){
			return 'Semi Finals';			
		}elseif($r === 1){
			return 'First Round';
		}
		return 'Round '.str_pad($r,2,'0',STR_PAD_LEFT);
	}
	
	
	/**
	 * setRoundCount 
	 * Set the rounds for the tournament given the number of teams.
	 * Rounds ^ 2 = teamCount
	 * 
	 */
	private function setRoundCount()
	{
		$this->rounds = ceil(log($this->teamCount, 2));
	}
	
	/**
	 * nextRound 
	 * Set the round to ... the next one.
	 * 
	 * @return
	 */
	public function nextRound()
	{
		$this->currentRound++;
	}
	
	/* ! Matches */

	/**
	 * Get all matches for the bracket
	 *
	 * @return array
	 **/
	public function matches()
	{	
		return $this->matches;
	}

	/**
	 * calculateMatches 
	 * Calculate the max and min number of matches for the whole tournament.
	 */
	private function calculateMatches()
	{
		$this->setMinMatches();
		$this->setMaxMatches();
	}
	/**
	 * setMinMatches 
	 * Set the minimum number of matches possible for the tournament
	 * 
	 */
	private function setMinMatches()
	{
		$this->minMatches = ($this->teamCount * $this->lossesRequired) - $this->lossesRequired;		
	}
	
	/**
	 * setMaxMatches 
	 * Set the maximum number of matches possible for the tournament
	 * 
	 */
	private function setMaxMatches()
	{
		$this->maxMatches = ($this->teamCount * $this->lossesRequired) - 1;
	}	

	/**
	 * matchesForRound
	 * Get the number of matches for a given round.
	 */
	public function matchesForRound($round)
	{
		return ceil(($this->playerCount/$this->playersPerTeam)/pow(2,$round));
	}

	/**
	 * Get a single match array from the bracket array.
	 *
	 * @param int $matchIndex
	 * @param int $round (optional : defaults to current round)
	 * @return array	
	 **/
	public function match($matchIndex, $round = false)
	{
		if( ! $round) 
		{
			$round = $this->currentRound;
		}
		return $this->matches[$this->getRoundId($round)][$matchIndex];
	}

	/**
	 * Set a status for a match.
	 *
	 * @return void
	 **/
	public function setMatchStatus($matchIndex, $status, $round = false)
	{
		if( ! $round) 
		{
			$round = $this->currentRound;
		}

		$this->matches[$this->getRoundId($round)][$matchIndex]['status'] = $status;
	}

	/**
	 * Set a winner for a match.
	 *
	 * @return void
	 **/
	public function setMatchWinner($matchIndex, $winnerIndex, $round = false)
	{
		if( ! $round) 
		{
			$round = $this->currentRound;
		}
		$this->matches[$this->getRoundId($round)][$matchIndex]['winner'] = $winnerIndex;
	}

	/**
	 * Add a team to a match.  
	 *
	 * @param int $matchIndex
	 * @param int $round
	 * @param array $team
	 * @return int New number of teams
	 **/
	public function addTeamToMatch($matchIndex, $round, $team)
	{
		return array_push($this->matches[$this->getRoundId($round)][$matchIndex]['teams'], $team);
	}
}