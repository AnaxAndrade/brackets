<?php
class Bracket_Controller extends Base_Controller 
{
	public function action_index()
	{
		$bracket = Bracket::find(1);
		$players = Bracket::find($bracket->id)->players;
		$tournament = New Tournament($bracket);

		// Pick Teams : random draw of team partners.
		$tournament->pickTeams();

		// Assign matches / rounds...  This could be held off to the controller.  So could pickTeams.
		$tournament->createBracket();
		
		$tournament->advanceTeam(0, 1);
		$tournament->advanceTeam(1, 0);
		$tournament->advanceTeam(2, 1);


		$tournament->advanceTeam(3, 0);
		$tournament->nextRound();
		$tournament->advanceTeam(0, 1);
		// $tournament->advanceTeam(1, 'home');
		// $tournament->nextRound();
		// $tournament->advanceTeam(0, 'home');

		return View::make('bracket/bracket_v', array('tournament'=>$tournament));
	}
}