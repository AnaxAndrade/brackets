<?php  if (!defined("BASEPATH")) exit("No direct script access allowed");

class MY_Model extends CI_Model {

	public function __construct(){ 
		parent::__construct();
	}

	public function __get($property) {
    	if (property_exists($this, $property)) {
			return $this->$property;
    	}
   	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}

		return $this;
	}

}

/* End of file mY_Model.php */
/* Location: ./application/models/mY_Model.php */