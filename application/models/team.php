<?php 
class Team extends Eloquent {
	 public static $timestamps = false;
	 public static $table = 'teams';


	 /**
	  * Get matches for team.
	  *
	  * @return object
	  **/
	 public function matches()
	 {
		 return $this->has_many_and_belongs_to('Match');
	 }
}