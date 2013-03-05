<?php
class Bracket_Controller extends Base_Controller 
{
	public function action_index()
	{
		// $testUsers = array(  'Fez', 'Stephen Hyde', 'Eric Forman', 'Laura Pinciotti', 'Redd Forman', 'Jackie Berkhart', 'Kitty Forman', 'Bob Pinciotti', 'Kelso', 'Leo', 'Nina', 'Laurie', 'Midge Pinciotti', 'Jimmy Page', 'Mila Kunis'/*, 'Danny Masterson' */);

		// $bracket = new Bracket();
		// $bracket->name = 'This is the name';
		// $bracket->players_per_team = 2;
		// $bracket->losses = 1;
		// $bracket->save();

		// foreach($testUsers as $u)
		// {
		// 	$name = explode(' ', $u);
		// 	$p = new Player();
		// 	$p->first_name = $name[0];
		// 	$p->last_name = isset($name[1]) ? $name[1] : null;
		// 	$p->save();

		// 	$bracket->players()->attach($p);
		// }

		$bracket = Bracket::find(1);
		$tournament = New Tournament($bracket);

		// Pick Teams : random draw of team partners.
		// $tournament->pickTeams();

		// // Assign matches / rounds...  This could be held off to the controller.  So could pickTeams.
		// $tournament->buildBracket();

		// die();

		// $tournament->advanceTeam(0, 1);
		// $tournament->advanceTeam(1, 0);
		// $tournament->advanceTeam(2, 1);

		// $tournament->advanceTeam(3, 0);
		// $tournament->nextRound();
		// $tournament->advanceTeam(0, 1);

		return View::make('bracket/bracket_v', array('tournament'=>$tournament));
	}
}