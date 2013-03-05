<?php 
class Team extends Eloquent {

	/*
		Matp to matches
	*/
	public function matches()
	{
		return $this->has_many_and_belongs_to('Match');
	}

	 /*
	 	Map to players
	 */
	 public function players()
	 {
	 	return $this->has_many_and_belongs_to('Player');
	 }

	/*
		Get a string of player names.
	*/
	public function playerNames($glue = ', ')
	{
		$playerNames = array();
		foreach($this->players as $p){
			$playerNames[] = implode(' ', array_filter(array($p->first_name, $p->last_name)));
		}
		return implode($glue, $playerNames);
	}
}