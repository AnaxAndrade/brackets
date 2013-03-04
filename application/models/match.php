<?php 
class Match extends Eloquent {

	public function teams()
	{
		return $this->has_many_and_belongs_to('Team')->with('type');
	}
}