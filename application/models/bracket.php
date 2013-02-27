<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bracket extends MY_Model {
	
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
		Teams that are still in the tournament 
	*/
	protected $activeTeams = array();
	
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
	
	/* 
		======================================================================== 
	*/

	public function __construct()
	{ 
		parent::__construct();
	} 
	
	/**
	 * initialize 
	 * Initialize the bracket and draw teams.
	 * 
	 * @param array $playersArray An array of players by name string. Ex: array('Person Namerson', 'Harrison Namerson')
	 * @param int $playersPerTeam Number of players per team
	 * @param string $type Type of bracket.  singles or doubles.
	 * @return
	 */
	public function initialize($playersArray, $playersPerTeam = 2, $lossesRequired = 2)
	{
		$this->players = $playersArray;
		$this->playerCount = count($this->players);
		$this->playersPerTeam = $playersPerTeam;
		$this->lossesRequired = $lossesRequired;
		
		// Pick Teams
		$this->pickTeams();
		
		// Assign matches / rounds...  This could be held off to the controller.  So could pickTeams.
		$this->createBracket();
		
	}
	
	/* ! TEAMS */

	/**
	 * pickTeams
	 * Take all players and assign teams as necessary. This is step one.
	 * Players array gets shuffled to assign random players.
	 * 
	 * @return
	 */
	public function pickTeams($random = true)
	{
		$this->teams = array();
		/* 
			Setting the var random to true will shuffle the array of players.  
			Otherwise it will be a first second, third fourth, in order. 
		*/
		if($random)
		{	
			shuffle($this->players); 	// put the players in a random order to assign them.
		}

		/* 
			Iterate through all players increasing by the total number of players per team. 
		*/
		for($i=0;$i<$this->playerCount;$i+=$this->playersPerTeam)
		{
			$team = array(current($this->players));
			for($k=1;$k<$this->playersPerTeam;$k++) { // add as many players as needed.  notice index starts at 1.
				$team[] = next($this->players);
			}
			$this->teams[] = $team;
			next($this->players);
		}
		
		// set active teams to all teams because this is the beginning of the tournament
		$this->activeTeams = $this->teams;
		// update team count.
		$this->teamCount = count($this->teams);
	}
	
	public function getTeamPlayerNames($teamIndex)
	{
		return isset($this->teams[$teamIndex]) ? $this->teams[$teamIndex] : array();
	}
	
	/**
	 * unsetTeam 
	 * Unset a team from the activeTeams array
	 * 
	 * @param array $team Team to unset.
	 */
	public function teamIsOut($team)
	{
		if(!is_array($team)) return;
		
		$unsetId = array_search($team, $this->activeTeams);
		unset($this->activeTeams[$unsetId]);
	}
	
	/**
	 * advanceTeam 
	 * Advance a team to the next round.
	 * 
	 * @param Team index for the current round
	 */
	public function advanceTeam($matchIndex, $teamIndex)
	{
		// get the match and the
		if(isset($this->matches[$this->getRoundId($this->currentRound)][$matchIndex]['teams'][$teamIndex]))
		{
			$previousMatch = $this->matches[$this->getRoundId($this->currentRound)][$matchIndex];
			
			$team = $previousMatch['teams'][$teamIndex];
			$previousMatch['status'] = 'complete';
			$previousMatch['winner'] = $teamIndex;
			
			$this->matches[$this->getRoundId($this->currentRound)][$matchIndex] = $previousMatch;
			
		}else{
			return false;
		}
		
		$nextMatchIndex = floor($matchIndex / 2);
		
		/* 
			if the current round is the last round then this team has won the championship... 
			UNLESS
			Unless it's double elimination.  A team with one loss could lose in the championship round.   
		*/
		if($this->currentRound === $this->rounds) 
		{
			
		}else{
			// will need to set "home" or "away"
			if(isset($this->matches[$this->getRoundId($this->currentRound+1)][$nextMatchIndex]))
			{
				$nextMatch = $this->matches[$this->getRoundId($this->currentRound+1)][$nextMatchIndex];
			}else{
				return false;
			}
			
			$nextMatchTeamIndex = 'home';
			$nextMatch['status'] = 'waiting for opponent';
			if(isset($nextMatch['teams'][$nextMatchTeamIndex]))
			{
				$nextMatchTeamIndex = 'away';
				$nextMatch['status'] = 'ready to play';
			}
			
			$nextMatch['teams'][$nextMatchTeamIndex] = $team;
			$this->matches[$this->getRoundId($this->currentRound+1)][$nextMatchIndex] = $nextMatch;
		}
	}

	/* ! ROUNDS */
	
	/**
	 * getRoundId 
	 * Quick way to get the "name" of the round based on current round param.
	 * 
	 */
	public function getRoundId($r = false)
	{
		$r = $r ? $r : $this->currentRound;
		if($r === 1){
			return 'First Round';
		}elseif($r == ($this->rounds - 1)){
			return 'Semi Finals';			
		}elseif($r == $this->rounds){
			return 'Finals';
		}
		return 'Round '.str_pad($r,2,'0',STR_PAD_LEFT);
	}
	
	
	/**
	 * setRounds 
	 * Set the rounds for the tournament given the number of teams.
	 * Rounds ^ 2 = teamCount
	 * 
	 */
	private function setRounds()
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
	 * createBracket 
	 * Create the bracket for the tournament.
	 * 
	 */
	private function createBracket()
	{
		$this->calculateMatches();		
		$this->setRounds();
		$this->currentRound = 1;
		
		$matchesThisRound = ceil(($this->playerCount/$this->playersPerTeam)/2);
		
		for($r=1;$r<=$this->rounds;$r++)
		{	
			$this->matches[$this->getRoundId($r)] = array();

			for($i=0;$i<$matchesThisRound;$i++)
			{
				$homeKey = $i*2;
				$awayKey = $homeKey+1;
				
				if($this->currentRound === $r)
				{
					/* 
						Define the teams based on current teams. 
					*/
					$match = array(
						'teams' => array(
							'home' => $this->activeTeams[$homeKey],
							'away' => isset($this->activeTeams[$awayKey]) ? $this->activeTeams[$awayKey] : null
						),
						'winner' => false,
						'status' => 'pending'
					);
				}else{
					/* 
						Teams cannot be set because we don't know who is playing who yet. 
					*/
					$match = array('status' => 'waiting team assignment', 'winner' => false);
				}
				$this->matches[$this->getRoundId($r)][$i] = $match;
			}
			
			$matchesThisRound = $matchesThisRound / 2;	//  Half the people lost...
		}
	}


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
}

/* End of file bracket.php */
/* Location: ./application/models/bracket.php */