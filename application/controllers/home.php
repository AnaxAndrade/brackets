<?php
class Home_Controller extends Base_Controller 
{
	public function action_index()
	{
		// does the session already exist?
		$bracket = Bracket::find(Session::get('bracketId'));

		return View::make('home/index', array('bracket'=>$bracket, 'hideMenuBar' => true));
	}

	public function action_make()
	{
	}
}