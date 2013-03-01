<?php 
namespace Bracket;

/**
 * Methods for bracket results.  Must exted the Bracket class
 */
 class BracketResultsModel
 {
    private $winner = false;
    private $standings = array();

    function __construct()
    {

    }

    /**
     * Get the winner of the bracket if one exists
     *
     * @return array
     **/
    public function winner()
    {
      return $this->winner;
    }

    /**
     * Set the winner of the bracket
     *
     * @return void
     **/
    public function setWinner($winnerArr)
    {
      $this->winner = $winnerArr;
      $this->standings[0] = $winnerArr;
    }

    /**
     * Set the winner standings.
     *
     * @return void
     **/
    public function setStandings($standingsArr)
    {
      $this->standings = $standingsArr;
    }

    /**
     * Get the standings for the current bracket
     *
     * @return array
     **/
    public function standings()
    {
        return $this->standings;
    }
 } 


 ?>