<?php 
class Player extends Eloquent {


	/**
	 * Get all brackets for player
	 *
	 **/
	public function brackets()
	{
		return $this->has_many_and_belongs_to('Bracket');
	}

	/**
	 * Get all brackets created by a player
	 *		
	 **/
	public function createdBrackets()
	{
		return $this->has_many('Bracket', 'created_by');
	}
}