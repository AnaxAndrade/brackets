<?php 
class Match extends Eloquent {

	public function teams()
	{
		return $this->has_many_and_belongs_to('Team');
	}

	public function round()
	{
		return $this->belongs_to('Round');
	}
}