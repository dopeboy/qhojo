<?php

require('lib/httpful/bootstrap.php');
require('lib/restful/bootstrap.php');
require('lib/balanced/bootstrap.php');

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
        
    public function reverseGeocode($zipcode, $method)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://maps.googleapis.com/maps/api/geocode/json?address=" . $zipcode . "&sensor=false"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = json_decode(curl_exec($ch));
        curl_close($ch);  

        if ($output->status != "OK")
            throw new InvalidZipcodeException($method);

        $address_components = $output->results[0]->address_components;

        foreach ($address_components as $component)
        {
            // City
            if (in_array('locality',$component->types))
                $info["CITY"] = $component->long_name;

            // State
            if (in_array('administrative_area_level_1',$component->types))
                $info["STATE"] = $component->short_name;

            // Country--has to be inside the US
            if (in_array('country',$component->types) && $component->short_name != 'US')
                throw new InvalidZipcodeException($method);       
        }         

        return $info;
    }        
    
    public function sendText($target_phone, $source_phone, $message, $method, $user_id)
    {
        global $TwilioAccountSid;   
        global $TwilioAuthToken;
        $client = new Services_Twilio($TwilioAccountSid, $TwilioAuthToken);
        
        try
        {
            $sms = $client->account->sms_messages->create($source_phone, $target_phone,$message);     
        }
        
        catch (Services_Twilio_RestException $e)
        {
            throw new TwilioSendTextMessageException($method, $user_id, $e);
        }
    }
    
    public function insertNotification($sender_user_id, $receipient_user_id, $transaction_id, $notification_type_id, $method)
    {
        $notification_id = getRandomID();
        
        $sqlParameters[":notification_id"] =  $notification_id;
        $sqlParameters[":sender_user_id"] =  $sender_user_id;
        $sqlParameters[":receipient_user_id"] =  $receipient_user_id;
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":notification_type_id"] =  $notification_type_id;
        $sqlParameters[":unread"] =  1;
        $sqlParameters[":date"] =  date("Y-m-d H:i:s");
        
        $preparedStatement = $this->dbh->prepare('INSERT INTO NOTIFICATION (ID, SENDER_USER_ID, RECEIPIENT_USER_ID, TRANSACTION_ID, TYPE_ID, UNREAD, DATE) VALUES (:notification_id, :sender_user_id, :receipient_user_id, :transaction_id, :notification_type_id, :unread, :date)');
        try { $preparedStatement->execute($sqlParameters); } 
        catch (PDOException $e) { throw new DatabaseException($e->getMessage(), $method, $sender_user_id, $e); } 
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
            global $paypal_environment, $paypal_username, $paypal_password, $paypal_signature;
            $environment = $paypal_environment;

            // Set up your API credentials, PayPal end point, and API version.
            $API_UserName = urlencode($paypal_username);
            $API_Password = urlencode($paypal_password);
            $API_Signature = urlencode($paypal_signature);
            $API_Endpoint = "https://api-3t.paypal.com/nvp";

            if("sandbox" === $environment || "beta-sandbox" === $environment) 
            {
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
