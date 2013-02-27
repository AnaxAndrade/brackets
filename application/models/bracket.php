<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bracket extends MY_Model {
	
	/* 
		Number of players in the tournament 
	*/
	private $playerCount = 0;

	/* 
		Number of players per team
	*/
	private $playersPerTeam = 0;
	
	/* 
		Number of teams in the tournament 
	*/
	private $teamCount = 0;
	
	/* 
		Minimum number of matches played for the tournament 
	*/
	private $minMatches = 0;
	
	/* 
		Maximum number of matches to be played. 
	*/
	private $maxMatches = 0;
	
	/* 
		Number of rounds the tournament will play 
	*/
	private $rounds = 0;
	
	/* 
		Players in the tournament. 
	*/
	private $players = array();
	
	/* 
		Teams in the tournament 
	*/
	private $teams = array();
	
	/* 
		Array of matches. 
	*/
	private $matches = array();
	
	/* 
		Array of active matches. 
	*/
	private $currentMatches = array();
	
	/* 
		Number of losses it takes before the team is out of the tournament 
	*/
	private $lossesRequired = 1;
	
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
		
		$this->setTeams();
		$this->defineMatches();
		
	}
	
	/**
	 * setTeams
	 * Initialize teams
	 * 
	 * @return
	 */
	private function setTeams()
	{
		$this->teams = array();
		for($i=0;$i<$this->playerCount;$i+=$this->playersPerTeam)
		{
			$key = $i/$this->playersPerTeam;
			$this->teams[$key] = array($this->players[$i]);
			
			for($k=1;$k<$this->playersPerTeam;$k++)
			{
				if(isset($this->players[$i+$k])){
					$this->teams[$key][] = $this->players[$i+$k];
				}else{
					break;
				}
			}
		}
		
		$this->teamCount = count($this->teams);
	}
	
	/**
	 * initDoubleElim 
	 * Initialize a double elimination tournament
	 * 
	 * @return
	 */
	private function defineMatches()
	{
		$this->maxMatches = ($this->lossesRequired * $this->teamCount) - $this->lossesRequired;
		$this->minMatches = $this->maxMatches - ($this->lossesRequired - 1);
		$this->rounds = ceil(log($this->teamCount, 2));
		
		for($r=1;$r<=$this->rounds;$r++)
		{	
			$roundId = 'round'.str_pad($r,0,STR_PAD_LEFT);
			$matchesThisRound = floor($this->teamCount / ($r * 2));
			
			print $matchesThisRound . ' - ';
			
			$this->matches[$roundId] = array();
			for($i=0;$i<$matchesThisRound;$i++)
			{
				$homeKey = $i*2;
				$awayKey = $homeKey+1;
				$this->matches[$roundId][$i] = array(
					'home' => $this->teams[$homeKey],
					'away' => isset($this->teams[$awayKey]) ? $this->teams[$awayKey] : null
				);
			}
		}
	}

	/* 
		ouputTestData - print all the vars active in the obj. 
	*/
	public function outputTestData()
	{
		print '<pre>';		
		print_r(get_object_vars($this));
		print '</pre>';
		
	}

}

/* End of file bracket.php */
/* Location: ./application/models/bracket.php */