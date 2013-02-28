<?php
class Bracket_Controller extends Base_Controller 
{
	public function action_index()
	{
		
		
		
		$testUsers = array(	'Fez', 'Stephen Hyde', 'Eric Forman', 'Laura Pinciotti', 'Redd Forman', 'Jackie Berkhart', 'Kitty Forman', 'Bob Pinciotti', 'Kelso', 'Leo', 'Nina', 'Laurie', 'Midge Pinciotti', 'Jimmy Page', 'Mila Kunis'/*, 'Danny Masterson' */);
		//$testUsers = array(	'Fez', 'Stephen Hyde', 'Eric Forman', 'Laura Pinciotti', 'Redd Forman', 'Jackie Berkhart', 'Kitty Forman', 'Bob Pinciotti');

		$playersPerTeam = 2;
		$maxLosses = 1;
		$bracket = New Bracket\BracketModel($testUsers, $playersPerTeam, $maxLosses);
		
		// Pick Teams : random draw of team partners.
		$bracket->pickTeams();
		
		// Assign matches / rounds...  This could be held off to the controller.  So could pickTeams.
		$bracket->createBracket();
		
		$bracket->advanceTeam(0, 'away');
		$bracket->advanceTeam(1, 'home');
		$bracket->advanceTeam(2, 'away');
		$bracket->advanceTeam(3, 'home');
		$bracket->nextRound();
		$bracket->advanceTeam(0, 'away');
		$bracket->advanceTeam(1, 'home');
		$bracket->nextRound();
		$bracket->advanceTeam(0, 'home');

/*
		$bracket->advanceTeam(1, 'home');
		$bracket->nextRound();
		$bracket->advanceTeam(0, 'away');
*/
		
		
		return View::make('bracket/bracket_v', array('bracket'=>$bracket));
	}
}