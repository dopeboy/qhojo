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
        
        public function sendEmail($from, $to, $replyto, $subject, $message)
        {
            $headers = 'From: ' . $from . "\r\n" .
                       'Reply-To: ' . $replyto . "\r\n" .
                       'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();     
            
            $message = "<html><body>" . $message . "</body></html>";
            return mail($to, $subject, $message, $headers);            
        }

	public function __destruct() 
	{
		$this->dbh = null;
	}
}

?>
