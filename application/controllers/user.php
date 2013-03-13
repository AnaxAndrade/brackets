<?php
class User_Controller extends Base_Controller
{
	public $restful = true;

	public function get_index()
	{
		$bracket = Bracket::find(Session::get('bracketId'));

		return View::make('user/dashboard', array('bracket'=>$bracket));
	}

	// show account form
	public function get_account()
	{
		$formErrors = Session::get('errors');

		$bracket = Bracket::find(Session::get('bracketId'));
		$user = User::find(Auth::user()->id);

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