<?php 
class Bracket extends Eloquent {

		public static $timestamps = false;
		public static $table = 'brackets';

		public function rounds()
		{
			return $this->has_many('Round');
		}

		/*
			Get all matches for bracket.
		*/
		public function matches()
		{
			return  $this->has_many_and_belongs_to('Match');
		}

		/**
		 * Get all teams for the provided bracket id
		 *
		 * @return array
		 **/
		public function teams()
		{
			return $this->has_many('Team');
		}

		/**
		 * Get all players for a bracket from bracket ID.
		 *
		 * @return array
		 **/
		public function players()
		{
			return $this->has_many_and_belongs_to('Player');
		}
	
	}
