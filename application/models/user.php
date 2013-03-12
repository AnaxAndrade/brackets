<?php 
class User extends Eloquent {
	
	public function player()
	{
		return $this->belongs_to('Player');
	}
}