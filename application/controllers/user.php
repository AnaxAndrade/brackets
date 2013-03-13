<?php
class User_Controller extends Base_Controller
{
	public $restful = true;

	public function get_index()
	{
		$bracket = Bracket::find(Session::get('bracketId'));

		return View::make('user/dashboard', array('bracket'=>$bracket));
	}

	public function get_logout()
	{
		Auth::logout();
		Session::flush();
		return Redirect::home()->with('success', 'You have been logged out');
	}
}