<?php

class LocationModel extends Model 
{
	public function getAllLocations() 
	{
		$preparedStatement = $this->dbh->prepare('select * from LOCATION_VW');
		$preparedStatement->execute();
		$rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
                                
		return $rows;		
	}  
}

?>