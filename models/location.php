<?php

class LocationModel extends Model 
{
	public function getAllLocations() 
	{
            $preparedStatement = $this->dbh->prepare('select * from LOCATION_VW ORDER BY BOROUGH_ID,NEIGHBORHOOD');
            $preparedStatement->execute();
            $rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

            return $rows;		
	}  
}

?>