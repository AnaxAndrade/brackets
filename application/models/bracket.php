<?php 
class Bracket extends Eloquent {

		public static $timestamps = false;
		public static $table = 'brackets';

		public function rounds()
		{
			return $this->has_many('Round');
		}

		public static function matches($id)
		{
			return  DB::table(self::$table)->join('rounds', self::$table.'.id','=','rounds.bracket_id')->where(self::$table.'.id','=',$id)->get();
		}

		/**
		 * Get all teams for the provided bracket id
		 *
		 * @return array
		 **/
		public static function teams($id)
		{
			return DB::table(self::$table)->join('teams', self::$table.'.id','=','teams.bracket_id')->where(self::$table.'.id','=',$id)->get();
		}

		/**
		 * Get all players for a bracket from bracket ID.
		 *
		 * @return array
		 **/
		public function players()
		{
			return $this->has_many_and_belongs_to('Player');

			// $players = array();
			// foreach(self::teams($id) as $t)
			// {
			// 	$playerArr = DB::table('teams_players')
			// 				->join('players', 'teams_players.player_id','=','players.id')
			// 				->where('teams_players.team_id','=',$t->id)
			// 				->get();
			// 	$players = array_merge($players, $playerArr);
			// }
			// return  $players;

		}
	
	}
