<?php
class Bracket_Controller extends Base_Controller 
{
	public function action_index()
	{
		$bracket = New BracketModel();
		
		
		
		$testUsers = array(	'Fez', 'Stephen Hyde', 'Eric Forman', 'Laura Pinciotti', 'Redd Forman', 'Jackie Berkhart', 'Kitty Forman', 'Bob Pinciotti', 'Kelso', 'Leo', 'Nina', 'Laurie', 'Midge Pinciotti', 'Jimmy Page', 'Mila Kunis'/*, 'Danny Masterson' */);
		//$testUsers = array(	'Fez', 'Stephen Hyde', 'Eric Forman', 'Laura Pinciotti', 'Redd Forman', 'Jackie Berkhart', 'Kitty Forman', 'Bob Pinciotti');

		$playersPerTeam = 2;
		$maxLosses = 1;
		$bracket->initialize($testUsers, $playersPerTeam, $maxLosses);		
		
		$bracket->advanceTeam(0, 'away');
		$bracket->advanceTeam(1, 'home');
		$bracket->advanceTeam(2, 'away');
		$bracket->advanceTeam(3, 'home');
		$bracket->currentRound++;
		$bracket->advanceTeam(0, 'away');
/*
		$bracket->advanceTeam(1, 'home');
		$bracket->currentRound++;
		$bracket->advanceTeam(0, 'away');
*/
		
		
		return View::make('bracket/bracket_v', array('bracket'=>$bracket));
	}
}