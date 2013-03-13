<?php
class Home_Controller extends Base_Controller 
{
	public $restful = true;

	public function get_index()
	{
		if(Auth::check()) return Redirect::to('user');
		// login or create user
		return View::make('home/index', array());
	}

	public function get_register()
	{
		if(Auth::check()) return Redirect::to('user');
		$formErrors = Session::get('errors');
		
		return View::make('home/register', array('formErrors' => $formErrors));
	}

	// Create a new user
	function post_register()
	{
		// set validation rules for new user content.
		$rules = array(
		    'name'  => 'required|min:3|max:75',
		    'email'   => 'required|unique:users|email',
		    'password' => 'required|min:5|max:64'
		);

		$v = Validator::make(Input::all(), $rules);

		if($v->fails())
		{
			return Redirect::to('user/new')->with_input('except', array('password'))->with_errors($v);
		}

		$nameArr = explode(' ', Input::get('name'), 2);
		$player = new Player();
		$player->first_name = $nameArr[0];
		$player->last_name = (isset($nameArr[1])) ? $nameArr[1] : '';
		$player->save();

		if ($player->save()) {
			$user = new User();
			$user->password = Hash::make(Input::get('password'));
			$user->email = Input::get('email');
			$user->player_id = $player->id;
			if($user->save())
			{
				// log the user in the session
				Auth::login($user->id);

				return Redirect::to('user')->with('welcomeMsg', true);
			}else{
				// oh shit. roll back the player and return an error
				$player->delete();
				return Redirect::to('user/new')->with_input('except', array('password'))->with('error', 'Nah fool... something went real wrong.');
			}
		}else{
			return Redirect::to('user/new')->with_input('except', array('password'))->with('error', 'This user could not be created.');
		}
	}

	// Login page
	public function get_login()
	{
		if(Auth::check()) return Redirect::to('user');

		// login or create user
		return View::make('home/login', array());
	}

	// Login processing
	public function post_login()
	{
		// set validation rules for new user content.
		$rules = array(
		    'email'   => 'required|email',
		    'password' => 'required|min:5|max:64'
		);

		$v = Validator::make(Input::all(), $rules);
		if($v->fails())
		{
			return Redirect::to('home/login')->with_input('except', array('password'))->with_errors($v);
		}

		$userCreds = array(
			'username' => Input::get('email'), // the key has to be username to pass through auth.  even with an email.
			'password' => Input::get('password')
		);

		if(Auth::attempt($userCreds)){
			return Redirect::to('user');
		}else{
			return Redirect::to('home/login')->with_input('except', array('password'))->with('error', 'We could not log you in using those credentials.  Please check and resubmit.');
		}
	}
}