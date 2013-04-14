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
 
        /** SetExpressCheckout NVP example; last modified 08MAY23.
         *
         *  Initiate an Express Checkout transaction. 
        */
        /**
         * Send HTTP POST Request
         *
         * @param	string	The API method name
         * @param	string	The POST Message fields in &name=value pair format
         * @return	array	Parsed HTTP Response body
         */
        function PPHttpPost($methodName_, $nvpStr_) 
        {
                global $paypal_environment;
                $environment = $paypal_environment;
                
                // Set up your API credentials, PayPal end point, and API version.
                $API_UserName = urlencode('manish-facilitator_api1.qhojo.com');
                $API_Password = urlencode('1365794178');
                $API_Signature = urlencode('AFcWxV21C7fd0v3bYYYRCpSSRl31AnCVEEsukrPp-owAJXlPJnskrwj2');
                $API_Endpoint = "https://api-3t.paypal.com/nvp";
                if("sandbox" === $environment || "beta-sandbox" === $environment) {
                        $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
                }
                $version = urlencode('86');

                // Set the curl parameters.
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
                curl_setopt($ch, CURLOPT_VERBOSE, 1);

                // Turn off the server and peer verification (TrustManager Concept).
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);

                // Set the API operation, version, and API signature in the request.
                $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

                // Set the request as a POST FIELD for curl.
                curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

                // Get response from the server.
                $httpResponse = curl_exec($ch);

                if(!$httpResponse) {
                        exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
                }

                // Extract the response details.
                $httpResponseAr = explode("&", $httpResponse);

                $httpParsedResponseAr = array();
                foreach ($httpResponseAr as $i => $value) {
                        $tmpAr = explode("=", $value);
                        if(sizeof($tmpAr) > 1) {
                                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
                        }
                }
 
                if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
                        exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
                }

                return $httpParsedResponseAr;
        }

	public function __destruct() 
	{
		$this->dbh = null;
	}
}

?>
