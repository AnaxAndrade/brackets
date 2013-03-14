<?php
class User_Controller extends Base_Controller
{
	public $restful = true;

	public function get_index()
	{
		$bracket = Bracket::find(Session::get('bracketId'));  // current bracket if there is one.

		// This is the dashboard screen so we'll get some (or all) 
		// of the brackets this user has created in the past.
		$player = Auth::user()->player;
		$createdBrackets = $player->createdBrackets()->take(5)->get();  
		$playedBrackets = $player->brackets()->take(5)->get();

		$v = [
			'createdBrackets' => $createdBrackets,
			'playedBrackets' => $playedBrackets,
			'bracket'=>$bracket
		];

		return View::make('user/dashboard', $v);
	}

	// show account form
	public function get_account()
	{
		$formErrors = Session::get('errors');

		$bracket = Bracket::find(Session::get('bracketId'));
		$user = Auth::user();

		return View::make('user/account', array('bracket'=>$bracket, 'user' => $user, 'formErrors' => $formErrors));
	}

	// process account update
	public function post_account()
	{

	}

	public function get_logout()
	{
		Auth::logout();
		Session::flush();
		return Redirect::home()->with('success', 'You have been logged out');
	}
}