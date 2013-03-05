<?php 
class Round extends Eloquent {
	
	public function matches()
	{
		return $this->has_many('Match');
	}

	public function bracket()
	{
		return $this->belongs_to('Bracket');
	}
}