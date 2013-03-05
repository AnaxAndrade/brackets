<?php
class Home_Controller extends Base_Controller 
{
	public function action_index()
	{
		$bracket = Bracket::find(1);
		$tournament = New Tournament($bracket);

		return View::make('bracket/bracket_v', array('tournament'=>$tournament));
	}

	public function action_demo()
	{
		$matches = Bracket::find(1)->matches();
	}
}