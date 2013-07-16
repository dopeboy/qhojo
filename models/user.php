<?php

require('lib/adaptiveaccounts-sdk-php/samples/PPBootStrap.php');

class UserModel extends Model 
{
	public function verify($emailaddress, $password, &$userid, &$firstname, &$lastname) 
	{
            $sqlParameters[":email"] =  $emailaddress;
            $preparedStatement = $this->dbh->prepare('SELECT FIRST_NAME, LAST_NAME, ID, PASSWORD FROM USER WHERE EMAIL_ADDRESS=:email LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            if ($row == null || !$this->comparePasswords($password, $row['PASSWORD']) )
                return -1;

            $userid = $row['ID'];
            $firstname = $row['FIRST_NAME'];
            $lastname = $row['LAST_NAME'];

            return 0;
	}
        
        public function index($userid)
        {
            $row["USER"] = $this->getUserDetails($userid);
            $row["LENDER_FEEDBACK"] = $this->getFeedbackAsLender($userid);
            $row["BORROWER_FEEDBACK"] = $this->getFeedbackAsBorrower($userid);
            $row["COMMENTS_AS_LENDER"] = $this->getCommentsAsLender($userid);
            $row["COMMENTS_AS_BORROWER"] = $this->getCommentsAsBorrower($userid);
            $row["NETWORKS"] = $this->getNetworksForUser($userid);
            
            return $row;
        }
        
        public function getUserDetails($userid)
        {
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM USER_VW WHERE ID=:userid');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            return $row;	            
        }
        
        public function getDashboardData($userid, $item_model)
        {
            $row["loans"]["current"] = $item_model->getCurrentLoans($userid);
            $row["loans"]["past"] = $item_model->getPastLoans($userid);
            
            $row["borrows"]["current"] = $item_model->getCurrentBorrows($userid);
            $row["borrows"]["past"] = $item_model->getPastBorrows($userid);
            
            $row["requests"] = $item_model->getRequests($userid);

            return $row;
        }
        
        public function getFeedbackAsLender($userid)
        {
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('SELECT AVG(BORROWER_TO_LENDER_STARS) as LENDER_FEEDBACK FROM ITEM_VW WHERE LENDER_ID=:userid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row['LENDER_FEEDBACK'];	            
        }
        
        public function getFeedbackAsBorrower($userid)
        {
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('SELECT AVG(LENDER_TO_BORROWER_STARS) as BORROWER_FEEDBACK FROM ITEM_VW WHERE BORROWER_ID=:userid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row['BORROWER_FEEDBACK'];	            
        }  
        
        public function getCommentsAsBorrower($userid)
        {
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE BORROWER_ID=:userid and ITEM_STATE_ID=3 and LENDER_TO_BORROWER_COMMENTS is not null');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

		return $row;	            
        }  
        
        public function getCommentsAsLender($userid)
        {
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE LENDER_ID=:userid and ITEM_STATE_ID=3 and BORROWER_TO_LENDER_STARS is not null');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

		return $row;	                
        }          
        
        public function signup($location_model)
        {   
            $row[] = $location_model->getAllLocations();
            return $row;
        }
        
        public function signupAction($emailaddress, $password, $first_name, $locationid)
        {
            // Does this email address exist?
            $sqlParameters[":email_address"] =  $emailaddress;
            $preparedStatement = $this->dbh->prepare('SELECT 1 FROM USER WHERE EMAIL_ADDRESS=:email_address LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
            
            if ($row != null)
                return -1;
                        
            $sqlParameters[":email_address"] =  $emailaddress;
            $sqlParameters[":password"] =  $this->hashPassword($emailaddress, $password);
            
            $sqlParameters[":userid"] =  $userid = getRandomID();
            $sqlParameters[":first_name"] =  $first_name;
            $sqlParameters[":join_date"] =  date("Y-m-d H:i:s");
            $sqlParameters[":location_id"] =  $locationid;
            $preparedStatement = $this->dbh->prepare('insert into USER (ID,FIRST_NAME,EMAIL_ADDRESS,LOCATION_ID, PASSWORD, JOIN_DATE, ACTIVE_FLAG) VALUES (:userid,:first_name, :email_address, :location_id, :password, :join_date, 1)');
            $preparedStatement->execute($sqlParameters);

            return $preparedStatement->rowCount() == 1 ? $userid : -1;
        }
        
        public function isAdmin($userid)
        {
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('select 1 from USER where ID=:userid and ADMIN_FLAG=1 LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            return $row == null ? 0 : 1;            
        }   
        
        public function checkExtraFields($userid)
        {
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('select 1 from USER where ID=:userid and (PROFILE_PICTURE_FILENAME is null or PHONE_NUMBER is null) LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            return $row == null ? 0 : 1;
        }
        
        public function checkCreditMethod($userid)
        {
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('select 1 from USER where ID=:userid and PAYPAL_EMAIL_ADDRESS is null LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC); 

            return $row == null ? 0 : 1;            
        }
        
        public function checkDebitMethod($userid)
        {
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('select 1 from USER where ID=:userid and (BP_BUYER_URI is null or BP_PRIMARY_CARD_URI is null) LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC); 

            return $row == null ? 0 : 1;            
        }        
        
        public function extraSignupFieldsAction($userid,$phonenumber, $profilepicture, $network_email, $network_id)
        {
            $sqlParameters[":userid"] =  $userid;
            $arr = array('(' => '', ')'=> '','-' => '',' ' => '');
            $sqlParameters[":phonenumber"] =  '+1' . str_replace( array_keys($arr), array_values($arr), $phonenumber);
            $sqlParameters[":profile_picture"] =  substr($profilepicture[0], strlen('uploads/user/'), strlen($profilepicture[0]));

            $preparedStatement = $this->dbh->prepare('update USER SET PHONE_NUMBER=:phonenumber, PROFILE_PICTURE_FILENAME=:profile_picture where ID=:userid LIMIT 1');
            $preparedStatement->execute($sqlParameters);  
            
            if ($preparedStatement->rowCount() != 1)
                return -3;
            
            if (trim($network_email) == '' || trim($network_id) == '')
                return 0;
            
            // USER NETWORK STUFF
            $status = $this->addNetwork($network_id, $network_email, $userid);
            if ($status != 0)
                    return -4;

            return 0;                
        }
        
        public function addNetwork($network_id, $network_email, $userid)
        {
            // Make an insert into user_network
            $sqlParameters = array();
            $sqlParameters[":userid"] =  $userid;
            $sqlParameters[":confirmation_id"] =  getRandomGUID();
            $sqlParameters[":network_id"] =  $network_id;
            $sqlParameters[":user_network_email"] =  $network_email;
            $sqlParameters[":active"] =  0;
            $sqlParameters[":creation_date"] =  date("Y-m-d H:i:s");
            $preparedStatement = $this->dbh->prepare('INSERT INTO USER_NETWORK (CONFIRMATION_ID,USER_ID,NETWORK_ID,USER_NETWORK_EMAIL,ACTIVE,CREATION_DATE) VALUES (:confirmation_id, :userid, :network_id, :user_network_email, :active, :creation_date)');
            $preparedStatement->execute($sqlParameters);                
            
            if ($preparedStatement->rowCount() != 1)
                return -4;
            
            // Send email to user about joining network. Include URL with unique code
            $row = $this->getUserDetails($userid);
            $network = $this->getNetworkDetails($network_id);
            
            $message = "Hey " .  $row["FIRST_NAME"] . "!<br/><br/>";
            $message .= "Please confirm your network affiliation to " . $network['NAME'] . " by clicking the link below:<br/><br/>";
            $message .= "<a href='http://" . $_SERVER['HTTP_HOST'] . "/user/confirmnetwork/" . $sqlParameters[":confirmation_id"] . "/0'>" . "http://" . $_SERVER['HTTP_HOST'] . "/user/confirmnetwork/" . $sqlParameters[":confirmation_id"] . "/0</a>";
            $message .= "<br/><br/>-team qhojo";

            $this->sendEmail('do-not-reply@qhojo.com', $network_email . "@" . $network['EMAIL_EXTENSION'], 'do-not-reply@qhojo.com', 'qhojo - Confirm Network Affiliation', $message);
            
            return 0;
        }
        
        public function checkIfUserAlreadyRequested($userid, $itemid)
        {
            $sqlParameters[":userid"] =  $userid;
            $sqlParameters[":itemid"] =  $itemid;
            $preparedStatement = $this->dbh->prepare('select 1 from ITEM_REQUESTS_VW where REQUESTER_ID=:userid and ITEM_ID=:itemid');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            return $row != null ? 1 : 0;            
        }
        
        public function hashPassword($username, $password)
        {
            $salt = hash('sha256', uniqid(mt_rand(), true) . 'qhojo' . strtolower($username));
            $hash = $salt . $password;
            
            for ( $i = 0; $i < 100000; $i ++ ) 
            {
              $hash = hash('sha256', $hash);
            }            
            
            return $salt . $hash;
        }
        
        public function comparePasswords($password_from_login, $password_from_db)
        {
            $salt = substr($password_from_db, 0, 64);
            $hash = $salt . $password_from_login;
            
            for ( $i = 0; $i < 100000; $i ++ ) 
            {
              $hash = hash('sha256', $hash);
            }
            
            $hash = $salt . $hash;
            return $hash == $password_from_db;
        }
        
        public function getRequestCount($userid)
        {
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('select count(*) from ITEM_REQUESTS_VW where LENDER_ID=:userid');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_NUM);

            return $row[0];
        }
        
        public function signupBorrowerAction($userid, $card_uri)
        {
            global $bp_api_key;
            
            Balanced\Settings::$api_key = $bp_api_key;
            Httpful\Bootstrap::init();
            RESTful\Bootstrap::init();
            Balanced\Bootstrap::init();
            
            try
            {
                $buyer = Balanced\Marketplace::mine()->createBuyer($userid . '@user.qhojo.com',$card_uri);    
                $account = Balanced\Account::get($buyer->uri);                
            }
            
             catch (Balanced\Exceptions\HTTPError $e)
            {
                error_log($e->response);
                return -1;
            }
            
            // Save buyer uri into database
            $sqlParameters[":buyer_uri"] =  $buyer->uri;
            $sqlParameters[":userid"] =  $userid;
            $sqlParameters[":card_uri"] =  $card_uri;
            $preparedStatement = $this->dbh->prepare('UPDATE USER set BP_BUYER_URI=:buyer_uri, BP_PRIMARY_CARD_URI=:card_uri where ID=:userid LIMIT 1');
            $preparedStatement->execute($sqlParameters);

            return $preparedStatement->rowCount() == 1 ? 0 : -2;            
        }
        
        public function signupLenderAction($userid, $paypalEmailAddress, $paypalFirstName, $paypalLastName)
        {
            if ($this->verifyPaypalAccount($paypalEmailAddress,$paypalFirstName, $paypalLastName))
                return -1;
            
            // Paypal credentials verified. Let's save them into the database
            $sqlParameters[":paypal_email"] =  $paypalEmailAddress;
            $sqlParameters[":paypal_first_name"] =  $paypalFirstName;
            $sqlParameters[":paypal_last_name"] =  $paypalLastName;
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('UPDATE USER set PAYPAL_EMAIL_ADDRESS=:paypal_email, PAYPAL_FIRST_NAME=:paypal_first_name, PAYPAL_LAST_NAME=:paypal_last_name where ID=:userid LIMIT 1');
            $preparedStatement->execute($sqlParameters);

            return $preparedStatement->rowCount() == 1 ? 0 : -2;            
        }
        
        public function verifyPaypalAccount($paypalEmailAddress, $paypalFirstName, $paypalLastName)
        {
            global $paypal_username,$paypal_password,$paypal_signature,$paypal_appid,$paypal_environment;
            
            $configMap = array("acct1.UserName" => $paypal_username, 
                                 "acct1.Password" => $paypal_password,
                                 "acct1.Signature" => $paypal_signature,
                                 "acct1.AppId" => $paypal_appid,
                                 "http.ConnectionTimeOut" => 30,
                                 "http.Retry" => 5,
                                 "mode" => $paypal_environment,
                                 "service.SandboxEmailAddress" => "pp.devtools@gmail.com",
                                 "log.FileName" => "PayPal.log",
                                 "log.LogLevel" => "INFO",
                                 "log.LogEnabled" => "FALSE"
                                );

            $getVerifiedStatus = new GetVerifiedStatusRequest();
            $getVerifiedStatus->emailAddress = $paypalEmailAddress;
            $getVerifiedStatus->firstName = $paypalFirstName;
            $getVerifiedStatus->lastName = $paypalLastName;
            $getVerifiedStatus->matchCriteria = 'NAME';

            $service  = new AdaptiveAccountsService($configMap);
            
            try 
            {
                $response = $service->GetVerifiedStatus($getVerifiedStatus);
            } 
            
            catch(Exception $ex) 
            {
                //require_once 'lib/adaptiveaccounts-sdk-php/samples/Common/Error.php';
                //exit;
            }

            $ack = strtoupper($response->responseEnvelope->ack);
            error_log(print_r($response,true));
            return $ack == "SUCCESS" ? 0 : -1;
        }        
        
        public function siteAdmin()
        {
            $sqlParameters[":currentdate"] =  date("Y-m-d H:i:s");
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_STATE_ID=2 and :currentdate > END_DATE');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

            return $row;            
        }
        
        public function extraSignupFieldsView($userid)
        {
            return $this->getNetworksUserIsNotIn($userid);           
        }
        
        public function getNetworkDetails($network_id)
        {
            $sqlParameters[":network_id"] =  $network_id;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM NETWORK where ID=:network_id LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            return $row;                 
        }
        
        public function confirmNetworkAction($confirmation_id)
        {
            $sqlParameters[":confirmation_id"] =  $confirmation_id;
            $sqlParameters[":confirmed_date"] =  date("Y-m-d H:i:s");
            $preparedStatement = $this->dbh->prepare('UPDATE USER_NETWORK set ACTIVE=1, CONFIRMED_DATE=:confirmed_date where CONFIRMATION_ID=:confirmation_id and ACTIVE=0 LIMIT 1');
            $preparedStatement->execute($sqlParameters);

            return $preparedStatement->rowCount() == 1 ? 0 : -1;
        }
        
        public function confirmNetworkSuccess($confirmation_id)
        {
            $sqlParameters[":confirmation_id"] = $confirmation_id;
            $preparedStatement = $this->dbh->prepare('SELECT u.FIRST_NAME, n.NAME, n.ICON_IMAGE FROM USER u INNER JOIN USER_NETWORK un on u.ID=un.USER_ID and un.CONFIRMATION_ID=:confirmation_id INNER JOIN NETWORK n on un.NETWORK_ID=n.ID LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
            
            return $row;            
        }
        
        public function getNetworksForUser($userid)
        {
            $sqlParameters[":userid"] = $userid;
            $preparedStatement = $this->dbh->prepare('SELECT n.NAME as "NETWORK_NAME", n.ICON_IMAGE FROM USER_NETWORK un INNER JOIN NETWORK n on un.NETWORK_ID=n.ID where un.USER_ID=:userid and un.ACTIVE=1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
            
            return $row;                
        }
        
        public function getNetworksUserIsNotIn($userid)
        {
            $sqlParameters[":userid"] = $userid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM NETWORK where ID not in (select NETWORK_ID from USER_NETWORK where USER_ID=:userid and ACTIVE=1)');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

            return $row;            
        }
        
        public function getCreditCardForUser($userid)
        {
            // Get the BP Account URI first
            $sqlParameters[":user_id"] = $userid;
            $preparedStatement = $this->dbh->prepare('SELECT BP_PRIMARY_CARD_URI FROM USER_VW where ID=:user_id and BP_PRIMARY_CARD_URI is not null LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);            
            
            if ($row == null)
                return null;
            
            global $bp_api_key;
            Balanced\Settings::$api_key = $bp_api_key;

            Httpful\Bootstrap::init();
            RESTful\Bootstrap::init();
            Balanced\Bootstrap::init();
            
            try
            {
                $card = Balanced\Card::get($row["BP_PRIMARY_CARD_URI"]);
                return $card->is_valid ? $card : null;
            }
            
            catch (Exception $e)
            {
                error_log($e->getMessage());
                return null;
            }  
            
            // No valid cards
            return null;
        }
        
        public function editUser($userid)
        {
            $location_model = new LocationModel();
            
            $row["USER"] = $this->getUserDetails($userid);
            $row["CURRENT_NETWORKS"] = $this->getNetworksForUser($userid);
            $row["OUTSIDE_NETWORKS"] = $this->getNetworksUserIsNotIn($userid);
            $row["LOCATIONS"] = $location_model->getAllLocations();
            $row["CREDITCARD"] = $this->getCreditCardForUser($userid);
            
            return $row;
        }
        
        public function editEmailAction($emailAddress, $userid)
        {
            $sqlParameters[":userid"] = $userid;
            $sqlParameters[":email_address"] = $emailAddress;
            $preparedStatement = $this->dbh->prepare('UPDATE USER set EMAIL_ADDRESS=:email_address where ID=:userid LIMIT 1');
            $preparedStatement->execute($sqlParameters);

            return $preparedStatement->rowCount() == 1 ? $emailAddress : "Error: Email address didn't submit successfully";            
        }
        
        public function editPaypalEmailAddressAction($paypalFirstName, $paypalLastName, $paypalEmailAddress, $userid)
        {
            $status = $this->signupLenderAction($userid, $paypalEmailAddress,$paypalFirstName, $paypalLastName);

            return $status == 0 ? json_encode(array('ppFirstName'=> $paypalFirstName,'ppLastName'=>$paypalLastName,'ppEmailAddress'=>$paypalEmailAddress)) : "Error: Invalid PayPal credentials.";
        }
        
        public function editNetworkAction($network_id, $network_email, $userid)
        {
            $status =  $this->addNetwork($network_id, $network_email, $userid);
            
            return $status == 0 ? 0 : "Error: Email could not be dispatched";
        }
        
        public function editProfilePicture($picture, $userid)
        {
            $sqlParameters[":userid"] = $userid;
            $sqlParameters[":profile_picture"] =  substr($picture, strlen('uploads/user/'), strlen($picture));
            $preparedStatement = $this->dbh->prepare('update USER SET PROFILE_PICTURE_FILENAME=:profile_picture where ID=:userid LIMIT 1');
            $preparedStatement->execute($sqlParameters);              
            
            return $preparedStatement->rowCount() == 1 ? 0 : "Error: Profile picture did not upload successfully."; 
        }
        
        public function editLocation($location_id, $userid)
        {
            $sqlParameters[":userid"] = $userid;
            $sqlParameters[":location_id"] =  $location_id;
            $preparedStatement = $this->dbh->prepare('update USER SET LOCATION_ID=:location_id where ID=:userid LIMIT 1');
            $preparedStatement->execute($sqlParameters);              
            
            return $preparedStatement->rowCount() == 1 ? 0 : "Error: Location did not update successfully."; 
        }
        
        public function removeCreditCard($userid)
        {
            // First check if there's a record in the ITEM table where borrower_id=me and item_status=2. If so, you're not allowed to remove a card.
            $sqlParameters[":userid"] = $userid;
            $preparedStatement = $this->dbh->prepare('select 1 from ITEM_VW where BORROWER_ID=:userid and ITEM_STATE_ID=2 LIMIT 1');
            $preparedStatement->execute($sqlParameters); 
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
            
            if ($row != null) return "Error: Can't remove a card because you have an active transaction open";
            
            // Get the card
            $card = $this->getCreditCardForUser($userid);
            if ($card == null) return "Error: No credit card to remove";
            
            try
            {
                $card->invalidate();
            }

            catch (Exception $e)
            {
                error_log($e->getMessage());
                return "Error: Couldn't remove the card";
            }

            $sqlParameters= array();
            $sqlParameters[":userid"] = $userid;
            $preparedStatement = $this->dbh->prepare('update USER SET BP_PRIMARY_CARD_URI=null where ID=:userid LIMIT 1');
            $preparedStatement->execute($sqlParameters);              
            
            return $preparedStatement->rowCount() == 1 ? 0 : "Error: DB change didn't go through.";             
        }
        
        public function addCard($userid, $card_uri)
        {
            // Get the account URI
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('select BP_BUYER_URI from USER where ID=:userid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);     
            
            if ($row == null || $row["BP_BUYER_URI"] == null)
                return -1;
            
            global $bp_api_key;
            
            Balanced\Settings::$api_key = $bp_api_key;
            Httpful\Bootstrap::init();
            RESTful\Bootstrap::init();
            Balanced\Bootstrap::init();
            
            try
            {
                $account = Balanced\Account::get($row["BP_BUYER_URI"]); 
                $account->addCard($card_uri);
            }
            
             catch (Balanced\Exceptions\HTTPError $e)
            {
                error_log($e->response);
                return -2;
            }
            
            // Save buyer uri into database
            $sqlParameters[":userid"] =  $userid;
            $sqlParameters[":card_uri"] =  $card_uri;
            $preparedStatement = $this->dbh->prepare('UPDATE USER set BP_PRIMARY_CARD_URI=:card_uri where ID=:userid LIMIT 1');
            $preparedStatement->execute($sqlParameters);

            return $preparedStatement->rowCount() == 1 ? 0 : -3;                    
        }
}

?>
