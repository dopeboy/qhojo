<?php

class Location extends Controller 
{
	protected function getAllLocations() 
	{
		$viewmodel = new LocationModel();
		$this->returnView($viewmodel->getAllLocations(), true,false);
	}   
}

?>
