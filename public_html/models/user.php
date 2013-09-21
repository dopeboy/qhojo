<?php

require('lib/adaptiveaccounts-sdk-php/samples/PPBootStrap.php');

class UserModel extends Model 
{
    public function index($method, $user_id)
    {
        $row["USER"] = $this->getUserDetails($user_id);
        
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT COUNT(*) FROM ITEM_VW WHERE LENDER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);
        $row["NUM_ITEMS_POSTED"] = $preparedStatement->fetchColumn();
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM REVIEW_VW WHERE REVIEWER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);        
        $row["REVIEWS_BY_ME"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM REVIEW_VW WHERE REVIEWEE_ID=:user_id');
        $preparedStatement->execute($sqlParameters);        
        $row["REVIEWS_OF_ME"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);        
        
        $row["USER"]["NEED_EXTRA_FIELDS"] = $this->userNeedsExtraFields($user_id);
        
        return $row;
    }
    
    public function dashboard($method, $user_id)
    {
        $transaction_model = new TransactionModel();
        
        $row["LENDING"]["REQUESTS"]["OPEN"] = $transaction_model->getLendingRequests($user_id);
        $row["LENDING"]["REQUESTS"]["PENDING"] = $transaction_model->getLendingPendingRequests($user_id);
        $row["LENDING"]["CURRENT_TRANSACTIONS"] = $transaction_model->getLendingReservedAndExchanged($user_id);
        $row["LENDING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"] = $transaction_model->getLendingReturnedAndNeedReview($user_id);
        $row["LENDING"]["PAST_TRANSACTIONS"]["COMPLETED"] = $transaction_model->getLendingCompleted($user_id);
        
        $row["BORROWING"]["REQUESTS"]["OPEN"] = $transaction_model->getBorrowingRequests($user_id);
        $row["BORROWING"]["REQUESTS"]["PENDING"] = $transaction_model->getBorrowingPendingRequests($user_id);
        $row["BORROWING"]["CURRENT_TRANSACTIONS"] = $transaction_model->getBorrowingReservedAndExchanged($user_id);
        $row["BORROWING"]["PAST_TRANSACTIONS"]["AWAITING_REVIEW"] = $transaction_model->getBorrowingReturnedAndNeedReview($user_id);
        $row["BORROWING"]["PAST_TRANSACTIONS"]["COMPLETED"] = $transaction_model->getBorrowingCompleted($user_id);

        $preparedStatement = $this->dbh->prepare('SELECT * FROM REJECT_OPTIONS_VW');
        $preparedStatement->execute();        
        $row["REJECT_OPTIONS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);     
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM CANCEL_OPTIONS_VW');
        $preparedStatement->execute();        
        $row["CANCEL_OPTIONS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);          
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM WITHDRAW_OPTIONS_VW');
        $preparedStatement->execute();        
        $row["WITHDRAW_OPTIONS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);                  
        
        $user_model = new UserModel();
        $row["USER"]["NEED_EXTRA_FIELDS"] = $user_model->userNeedsExtraFields($user_id);
        
        return $row;        
    }

    public function verify($method, $email_address, $password) 
    {
        $sqlParameters[":email"] =  $email_address;
        $preparedStatement = $this->dbh->prepare('SELECT NAME, FIRST_NAME, USER_ID, PASSWORD, ADMIN FROM USER_VW WHERE EMAIL_ADDRESS=:email LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        
        if ($row == null || !$this->comparePasswords($password, $row['PASSWORD']) )
            throw new InvalidLoginException($method);

        return $row;
    }
    
    public function join($method, $firstname, $lastname, $zipcode, $email, $password)
    {
        // Does this email address exist?
        $sqlParameters[":email"] =  $email;
        $preparedStatement = $this->dbh->prepare('SELECT 1 FROM USER_VW WHERE EMAIL_ADDRESS=:email LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

        if ($row != null)
            throw new UserWithEmailAlreadyExists($email, $method);

        // Reverse geocode the zip to find the state and city
        $location = $this->reverseGeocode($zipcode, $method);
        
        $sqlParameters[":city"] = $location["CITY"];
        $sqlParameters[":state"] =  $location["STATE"];
        $sqlParameters[":email"] =  $email;
        $sqlParameters[":password"] =  $this->hashPassword($email, $password);
        $sqlParameters[":user_id"] = getRandomID();
        $sqlParameters[":firstname"] =  $firstname;
        $sqlParameters[":lastname"] =  $lastname;
        $sqlParameters[":zipcode"] =  $zipcode;
        $sqlParameters[":join_date"] =  date("Y-m-d H:i:s");
        
        $preparedStatement = $this->dbh->prepare('INSERT INTO USER (ID,FIRST_NAME,LAST_NAME,ZIPCODE,CITY,STATE,EMAIL_ADDRESS, PASSWORD, JOIN_DATE, ACTIVE) VALUES (:user_id,:firstname, :lastname, :zipcode, :city, :state, :email, :password, :join_date, 1)');
        $preparedStatement->execute($sqlParameters);
        
        $user['USER_ID'] = $sqlParameters[":user_id"];
        $user['FIRST_NAME'] = $firstname;
        $user['NAME'] = $firstname . ' ' . $lastname[0] . '.';
        $user['ADMIN'] = 0;
        
        // Send an email to the new user
        global $do_not_reply_email,$support_email,$domain;
        
        // Finally, send the email to both the lender and borrower
        $message = "Hi {$firstname}!<br/><br/>";
        $message .= "Welcome to Qhojo, your community for borrowing and renting film and video gear across New York City.<br/><br/>";
        $message .= "To get started, we strongly encourage you to complete the rest of your user profile. It'll help you later when you borrow and lend gear from and to the community. To complete your profile, click on the link below:<br/><br/>";
        $message .= "<a href=\"{$domain}/user/extrasignup/null/0\">{$domain}/user/extrasignup/null/0</a><br/><br/>";
        $message .= "If you have any questions, comments or concerns, never hesitate to drop us a line at our support email address located at the bottom of this email.";
        
        $subject = "Welcome to Qhojo!";
        
        sendEmail($do_not_reply_email, $email, null, $subject, $message);                  
        
        return $user;
    }
    
    public function getUserDetails($user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM USER_VW WHERE USER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        
        return $preparedStatement->fetch(PDO::FETCH_ASSOC);	            
    }
    
    // Profile Picture (100), Blurb(500), Phone # (200), PP (300), CC (400)
    public function userNeedsExtraFields($user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT PROFILE_PICTURE_FILENAME,BLURB,PHONE_VERIFIED,PAYPAL_EMAIL_ADDRESS,BP_PRIMARY_CARD_URI FROM USER_VW WHERE USER_ID=:user_id and (PROFILE_PICTURE_FILENAME is null || BLURB is null || PHONE_VERIFIED is null || PAYPAL_EMAIL_ADDRESS is null || BP_PRIMARY_CARD_URI is null) LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
         
        if ($row == null)
            return 0;
        
        else if ($row["PROFILE_PICTURE_FILENAME"]  == null)
            return 100;
        
        else if ($row["BLURB"]  == null)
            return 500;        

        else if ($row["PHONE_VERIFIED"]  == null)
            return 200;
        
        else if ($row["PAYPAL_EMAIL_ADDRESS"]  == null)
            return 300;     
        
        else if ($row["BP_PRIMARY_CARD_URI"]  == null)
            return 400;            
    }
    
    public function submitProfilePicture($method, $user_id, $profile_picture)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $sqlParameters[":profile_picture"] =  $profile_picture;

        $preparedStatement = $this->dbh->prepare('update USER SET PROFILE_PICTURE_FILENAME=:profile_picture where ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
    }
    
    public function submitPaypal($method, $user_id,$firstname, $lastname, $email)
    {
        $this->verifyPaypalAccount($email,$firstname, $lastname, $method, $user_id);

        // Paypal credentials verified. Let's save them into the database
        $sqlParameters[":paypal_email"] =  $email;
        $sqlParameters[":paypal_first_name"] =  $firstname;
        $sqlParameters[":paypal_last_name"] =  $lastname;
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('UPDATE USER set PAYPAL_EMAIL_ADDRESS=:paypal_email, PAYPAL_FIRST_NAME=:paypal_first_name, PAYPAL_LAST_NAME=:paypal_last_name where ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);           
    }
    
    public function submitBlurb($method, $user_id,$blurb)
    {
        // Paypal credentials verified. Let's save them into the database
        $sqlParameters[":blurb"] =  $blurb;
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('UPDATE USER set BLURB=:blurb where ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);           
    }    
    
    public function phoneNumber($method, $user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT PHONE_VERIFICATION_DATESTAMP, PHONE_NUMBER FROM USER_VW WHERE USER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row["USER"] = $preparedStatement->fetch(PDO::FETCH_ASSOC);        
        
        return $row;
    }
    
    public function sendPhoneVerificationCode($method, $user_id, $phone_number)
    {
        $arr = array('(' => '', ')'=> '','-' => '',' ' => '');
        $phone_clean =  '+1' . str_replace( array_keys($arr), array_values($arr), $phone_number); 
        
        // Does this phone number already exist?
        $sqlParameters[":phone_number"] =  $phone_clean;
        $preparedStatement = $this->dbh->prepare('SELECT 1 FROM USER_VW WHERE PHONE_NUMBER=:phone_number LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);         
        
        if ($row != null)
            throw new PhoneNumberUsedByExistingUserException($method, $user_id);
        
        $sqlParameters = array();
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT PHONE_VERIFICATION_DATESTAMP FROM USER_VW WHERE USER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);        
        
        if ($row["PHONE_VERIFICATION_DATESTAMP"] != null)
        {
            $now = new DateTime();
            $ref = new DateTime($row["PHONE_VERIFICATION_DATESTAMP"]); 
            
            $diff = $now->diff($ref);
            
            if ($diff->d == 0)
                throw new PhoneVerificationCodeException($method, $user_id);                
        }
           
        $verification_code = getRandomID();
        
        $sqlParameters[":phone_verification_code"] =  $verification_code;
        $sqlParameters[":phone_number"] =  $phone_clean;
        $sqlParameters[":phone_verification_datestamp"] =  date("Y-m-d H:i:s");
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('UPDATE USER set PHONE_VERIFICATION_CODE=:phone_verification_code, PHONE_VERIFICATION_DATESTAMP=:phone_verification_datestamp, PHONE_NUMBER=:phone_number where ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);        
        
        global $borrower_number;
        $this->sendText($phone_number, $borrower_number, $verification_code, $method, $user_id);
    }    

    public function verifyVerificationCode($method, $user_id, $verification_code)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT PHONE_VERIFICATION_CODE FROM USER_VW WHERE USER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);        
        
        if ($row["PHONE_VERIFICATION_CODE"] != $verification_code)
            throw new PhoneVerificationInvalidCodeException($method, $user_id);   
        
        $preparedStatement = $this->dbh->prepare('UPDATE USER set PHONE_VERIFIED=1 where ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);        
    }    
    
    public function submitCreditCard($method, $user_id,$card_uri)
    {
        global $bp_api_key;

        Balanced\Settings::$api_key = $bp_api_key;
        Httpful\Bootstrap::init();
        RESTful\Bootstrap::init();
        Balanced\Bootstrap::init();
    
        try
        {
            $buyer = Balanced\Marketplace::mine()->createBuyer($user_id . '@user.qhojo.com',$card_uri);    
            $account = Balanced\Account::get($buyer->uri);                
        }

        catch (Balanced\Errors\DuplicateAccountEmailAddress $e) 
        {
            # oops, account for $email_address already exists so just add the card
            $buyer = Balanced\Account::get($e->extras->account_uri);
            $buyer->addCard($card_uri);
        }
    
        catch (Balanced\Exceptions\HTTPError $e)
        {
            throw new AddCreditCardException($method, $user_id, $e);
        }

        // Save buyer uri into database
        $sqlParameters[":buyer_uri"] =  $buyer->uri;
        $sqlParameters[":user_id"] =  $user_id;
        $sqlParameters[":card_uri"] =  $card_uri;
        $preparedStatement = $this->dbh->prepare('UPDATE USER set BP_BUYER_URI=:buyer_uri, BP_PRIMARY_CARD_URI=:card_uri where ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);        
    }
                 
    public function contact($method, $message_content, $sender_id, $receipient_id, $entity_type, $entity_id)
    {
        $item_title = null;
        
        if ($entity_type == 'ITEM')
        {
            // Is the receipient the owner of the Item?
            $sqlParameters[":user_id"] =  $receipient_id;
            $sqlParameters[":item_id"] =  $entity_id;
            $preparedStatement = $this->dbh->prepare('SELECT ITEM_ID, TITLE FROM ITEM_VW WHERE LENDER_ID=:user_id and ITEM_ID=:item_id LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);        

            if ($row == null)
                throw new InvalidContactAttemptException($method, $sender_id,null,$entity_id);
            
            $item_title = $row['TITLE'];

        }
        
        else if ($entity_type == 'TRANSACTION')
        {
            // Are the receipient and lender someway affiliated with the transaction
            $sqlParameters[":receipient_id"] =  $receipient_id;
            $sqlParameters[":sender_id"] =  $sender_id;
            $sqlParameters[":transaction_id"] =  $entity_id;
            $preparedStatement = $this->dbh->prepare('SELECT TITLE, TRANSACTION_ID FROM BASE_VW WHERE (LENDER_ID=:receipient_id or BORROWER_ID=:receipient_id) and (LENDER_ID=:sender_id or BORROWER_ID=:sender_id) and TRANSACTION_ID=:transaction_id LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);    
            
            if ($row == null)
                throw new InvalidContactAttemptException($method, $sender_id,null,$entity_id); 
            
            $item_title = $row['TITLE'];
        }
        
        else
            throw new InvalidEntityTypeException($method, $sender_id,null,$entity_id);
        
        global $do_not_reply_email;
        
        // Fetch the receipient and sender email addresses
        $sqlParameters =  array();
        $sqlParameters[":receipient_id"] =  $receipient_id;
        $preparedStatement = $this->dbh->prepare('SELECT FIRST_NAME, EMAIL_ADDRESS FROM USER_VW WHERE USER_ID=:receipient_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);            
        $receipient_email = $row["EMAIL_ADDRESS"];
        $receipient_first_name = $row["FIRST_NAME"];
        
        $sqlParameters =  array();
        $sqlParameters[":sender_id"] =  $sender_id;
        $preparedStatement = $this->dbh->prepare('SELECT FIRST_NAME, EMAIL_ADDRESS FROM USER_VW WHERE USER_ID=:sender_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);            
        $sender_email = $row["EMAIL_ADDRESS"];
        $sender_first_name = $row["FIRST_NAME"];
        
        $subject = null;
        $message = null;
        $message_id = getRandomID();
        $message .= "Hi {$receipient_first_name},<br/><br/>";
        
        if ($entity_type == 'ITEM')
        {
            $subject = "{$sender_first_name} sent you a question about your item {$item_title} - Item ID: {$entity_id}";    
            $message .= "You have received a question about your item {$item_title}:<br/><br/>";
        }
        
        else if ($entity_type == 'TRANSACTION')
        {
            $subject = "{$sender_first_name} sent you a question about your transaction involving item {$item_title} - Transaction ID: {$entity_id}";
            $message .= "You have received a question about your item {$item_title}:<br/><br/>";            
        }
        
        $message .= "<blockquote>{$message_content}</blockquote><br/>";
        $message .= "To reply to {$sender_first_name}, just reply to this email.";        
        
        sendEmail($do_not_reply_email, $receipient_email, $sender_email, $subject, $message);
        
        // Save in the db
        $sqlParameters =  array();
        $sqlParameters[":id"] =  $message_id;
        $sqlParameters[":sender_id"] =  $sender_id;
        $sqlParameters[":receipient_id"] =  $receipient_id;
        $sqlParameters[":entity_type"] =  $entity_type;
        $sqlParameters[":entity_id"] =  $entity_id;
        $sqlParameters[":message"] =  $message_content;
        $sqlParameters[":date_sent"] =  date("Y-m-d H:i:s");
        
        $preparedStatement = $this->dbh->prepare('INSERT INTO CONTACT_MESSAGES (ID,SENDER_ID,RECIPIENT_ID,ENTITY_TYPE,ENTITY_ID,MESSAGE,DATE_SENT) VALUES (:id, :sender_id, :receipient_id, :entity_type, :entity_id, :message, :date_sent)');
        $preparedStatement->execute($sqlParameters);             
    }
    
    private function comparePasswords($password_from_login, $password_from_db)
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
    
    private function hashPassword($username, $password)
    {
        $salt = hash('sha256', uniqid(mt_rand(), true) . 'qhojo' . strtolower($username));
        $hash = $salt . $password;

        for ( $i = 0; $i < 100000; $i ++ ) 
        {
          $hash = hash('sha256', $hash);
        }            

        return $salt . $hash;
    }  
    
    private function verifyPaypalAccount($paypalEmailAddress, $paypalFirstName, $paypalLastName, $method, $user_id)
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
            throw new PaypalCommunicationException($method, $user_id, $ex);
        }

        $ack = strtoupper($response->responseEnvelope->ack);
        
        if ($ack != "SUCCESS")
            throw new InvalidPaypalCredentialsException($method, $user_id);
    }     
}

?>
