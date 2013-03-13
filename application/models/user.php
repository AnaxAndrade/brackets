<?php 
class User extends Eloquent {
	
	public static $hidden = array('password');

	// associate the player info
	public function player()
	{
		return $this->belongs_to('Player');
	}
}