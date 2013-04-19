<?php

class UserModel extends Model 
{
	public function login() 
	{
		return 0;
	}
        
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
            
            return $row;
        }
        
        public function getUserDetails($userid)
        {
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM USER WHERE ID=:userid');
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
        
        public function signup()
        {            
            return 0;
        }
        
        public function signupAction($emailaddress, $password, $first_name)
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
            $preparedStatement = $this->dbh->prepare('insert into USER (ID,FIRST_NAME,EMAIL_ADDRESS,PASSWORD, JOIN_DATE) VALUES (:userid,:first_name, :email_address, :password, :join_date)');
            $preparedStatement->execute($sqlParameters);

            return $preparedStatement->rowCount() == 1 ? $userid : -1;
        }
        
        public function checkExtra($userid)
        {
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('select 1 from USER where ID=:userid and (PROFILE_PICTURE_FILENAME is null or PHONE_NUMBER is null) LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            return $row == null ? 0 : -1;
        }
        
        public function signupExtra($userid,$phonenumber, $profilepicture, $paypal_token)
        {
            // Add request-specific fields to the request string.
            $nvpStr = "&TOKEN=$paypal_token";
                
            $httpParsedResponseAr = $this->PPHttpPost('CreateBillingAgreement', $nvpStr);
            
            if("SUCCESS" != strtoupper($httpParsedResponseAr["ACK"]) && "SUCCESSWITHWARNING" != strtoupper($httpParsedResponseAr["ACK"])) 
            {
                exit(print_r($httpParsedResponseAr, true));
                return -1;                                
            }   

            $sqlParameters[":userid"] =  $userid;
            $arr = array('(' => '', ')'=> '','-' => '',' ' => '');
            $sqlParameters[":phonenumber"] =  '+1' . str_replace( array_keys($arr), array_values($arr), $phonenumber);
            $sqlParameters[":profile_picture"] =  substr($profilepicture[0], strlen('uploads/user/'), strlen($profilepicture[0]));
            $sqlParameters[":paypal_token"] =  $httpParsedResponseAr['BILLINGAGREEMENTID'];

            $preparedStatement = $this->dbh->prepare('update USER SET PHONE_NUMBER=:phonenumber, PROFILE_PICTURE_FILENAME=:profile_picture, PAYPAL_BILLING_AGREEMENT_ID=:paypal_token where ID=:userid');
            $preparedStatement->execute($sqlParameters);       
            
            return $preparedStatement->rowCount() == 1 ? 0 : -1;                
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
            error_log($hash);
            error_log($password_from_db);
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
        
        public function paypalExpressCheckout()
        {
            global $paypal_environment;
            $environment = $paypal_environment;
            
            // Set request-specific fields.
            $paymentAmount = urlencode('0');
            $currencyID = urlencode('USD');							// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
            $paymentType = urlencode('AUTHORIZATION');				// or 'Sale' or 'Order'
            $billingType = urlencode('MerchantInitiatedBilling');
            $billingAgreementDesc = urlencode('qhojo');
            $returnURL = urlencode("http://" . $_SERVER['SERVER_NAME']  . "/user/signup/null/4");
            //$returnURL = urlencode('http://www.yahoo.com');
            $cancelURL = urlencode("http://" . $_SERVER['SERVER_NAME']);
            $shipping = '1';
            
            // Add request-specific fields to the request string.
            $nvpStr = "&NOSHIPPING=$shipping&PAYMENTREQUEST_0_AMT=$paymentAmount&ReturnUrl=$returnURL&CANCELURL=$cancelURL&PAYMENTREQUEST_0_PAYMENTACTION=$paymentType&PAYMENTREQUEST_0_CURRENCYCODE=$currencyID&L_BILLINGTYPE0=$billingType&L_BILLINGAGREEMENTDESCRIPTION0=$billingAgreementDesc";

            // Execute the API operation; see the PPHttpPost function above.
            $httpParsedResponseAr = $this->PPHttpPost('SetExpressCheckout', $nvpStr);

            if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
            {
                // Redirect to paypal.com.
                $token = urldecode($httpParsedResponseAr["TOKEN"]);
                $payPalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token";
                
                if("sandbox" === $environment || "beta-sandbox" === $environment) 
                {
                    $payPalURL = "https://www.$environment.paypal.com/webscr&cmd=_express-checkout&token=$token";
                }
                
                header("Location: $payPalURL");
                exit;
            } 
            
            else  
            {
                exit('SetExpressCheckout failed: ' . print_r($httpParsedResponseAr, true));
            }

            return null;            
        }
}

?>
