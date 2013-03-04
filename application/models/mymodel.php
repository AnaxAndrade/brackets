<?php
class MyModel {

	// public function __get($property) {
 //    	if (property_exists($this, $property)) {
	// 		return $this->$property;
 //    	}
 //   	}

	// public function __set($property, $value) {
	// 	if (property_exists($this, $property)) {
	// 		$this->$property = $value;
	// 	}

	// 	return $this;
	// }

	/* 
		==========================================================================================
		ouputTestData - print all the vars active in the obj. 
	*/
	public function developer($property = false)
	{
		print '<code><pre>';		
		if($property)
		{	
			if(is_array($property))
			{
				foreach($property as $key => $p)
				{
					if(property_exists($this,$p))
					{
						print '<br /><b>'.strtoupper($p).'</b><br />';
						print_r($this->$p);
						for($i=0;$i<30;$i++) print '-';
					}
				}
			}else{
				print '<br /><b>'.strtoupper($property).'</b><br />';
				print_r($this->$property);
				for($i=0;$i<30;$i++) print '-';
			}
		}else{
			print_r(get_object_vars($this));
		}
		print '</pre></code>';
		
	}
}
