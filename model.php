<?php



abstract class Model 
{
	protected $dbh;
	
	public function __construct() 
	{
		global $dbhost, $dbuser, $dbpass, $dbname;
	
		try 
		{
			$this->dbh = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpass);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch (PDOException $e) 
		{
			$this->dbh = null;
	    		print "Error!: " . $e->getMessage() . "<br/>";
	   	 	die();
	   	}	
	}

	public function __destruct() 
	{
		$this->dbh = null;
	}
}

?>
