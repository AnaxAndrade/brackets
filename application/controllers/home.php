<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->model('Bracket','bracket');		
		
		// $testUsers = array(	'Fez', 'Stephen Hyde', 'Eric Forman', 'Laura Pinciotti', 'Redd Forman', 'Jackie Berkhart', 'Kitty Forman', 'Bob Pinciotti', 'Kelso', 'Leo', 'Nina', 'Laurie', 'Midge Pinciotti', 'Jimmy Page', 'Mila Kunis', 'Danny Masterson');
		$testUsers = array(	'Fez', 'Stephen Hyde', 'Eric Forman', 'Laura Pinciotti', 'Redd Forman', 'Jackie Berkhart', 'Kitty Forman', 'Bob Pinciotti');

		$playersPerTeam = 1;
		$maxLosses = 1;
		$this->bracket->initialize($testUsers, $playersPerTeam, $maxLosses);		
		
		$this->bracket->advanceTeam(0, 'away');
		$this->bracket->advanceTeam(1, 'home');
		$this->bracket->advanceTeam(2, 'away');
		$this->bracket->advanceTeam(3, 'home');
		
		$this->load->view('bracket_v');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */