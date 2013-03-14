<?php 
class Bracket extends Eloquent {
		
		/*
			Get all rounds for the current bracket.
		*/
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
		 * Get the current round object for the bracket
		 *
		 * @return Round
		 **/
		public function currentRound()
		{
			return $this->belongs_to('Round', 'current_round');
		}

		/**
		 * Get the winner of the bracket if their is one
		 *
		 * @return Round
		 **/
		public function winner()
		{
			return $this->belongs_to('Team', 'winning_team_id');
		}


		/**
		 * undocumented function
		 *
		 * @return void
		 * @author 
		 **/
		public function advanceRound()
		{

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

		/**
		 * Get all brackets a player id has played in.
		 *
		 * @return Bracket
		 **/
		public static function playedIn($pid, $limit = false, $offset = false)
		{
			$r = Player::find($pid)->brackets;

			return $r;
		}
	
	}
