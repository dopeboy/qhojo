<?php

require('lib/adaptiveaccounts-sdk-php/samples/PPBootStrap.php');

class UserModel extends Model 
{    
    public function index($method, $user_id)
    {
        $row["USER"] = $this->getUserDetails($user_id);
        
        if ($row["USER"] == null)
            throw new UserDoesNotExistException($method, $user_id);
        
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT COUNT(*) FROM ACTIVE_ITEMS_VW WHERE LENDER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);
        $row["NUM_ITEMS_POSTED"] = $preparedStatement->fetchColumn();
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM REVIEW_VW WHERE REVIEWER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);        
        $row["REVIEWS_BY_ME"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM REVIEW_VW WHERE REVIEWEE_ID=:user_id');
        $preparedStatement->execute($sqlParameters);        
        $row["REVIEWS_OF_ME"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);        
        
        $row["USER"]["NEED_EXTRA_FIELDS"] = $this->checkIfUserNeedsExtraFields($user_id);
        
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
        $row["USER"]["NEED_EXTRA_FIELDS"] = $user_model->checkIfUserNeedsExtraFields($user_id);
        
        $item_model = new ItemModel();
        $row["MY_ITEMS"]["ACTIVE"] = $item_model->getMyActiveItems($user_id);
        $row["MY_ITEMS"]["INACTIVE"] = $item_model->getMyInactiveItems($user_id);               
        
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
    
    public function joinView($method, $invite_id)
    {
        $sqlParameters[":invite_id"] =  $invite_id;
        $preparedStatement = $this->dbh->prepare('SELECT 1 FROM INVITE_VW WHERE INVITE_ID=:invite_id AND COUNT > 0 LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        
        if ($row == null)
            throw new InvalidInvitationCodeException($method);        
    }
    
    public function join($method, $firstname, $lastname, $zipcode, $email, $password, $invite_id)
    {
        $sqlParameters[":invite_id"] =  $invite_id;
        $preparedStatement = $this->dbh->prepare('SELECT COUNT FROM INVITE_VW WHERE INVITE_ID=:invite_id AND COUNT > 0 LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        
        if ($row == null)
            throw new InvalidInvitationCodeException($method);  
        
        $count = $row["COUNT"];
        
        // Does this email address exist?
        $sqlParameters = array();
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
        
        // Decrement the count on the invite ID
        $sqlParameters = array();
        $sqlParameters[":count"] =  $count-1;
        $sqlParameters[":id"] =  $invite_id;
        $preparedStatement = $this->dbh->prepare('UPDATE INVITE SET COUNT=:count where ID=:id');
        $preparedStatement->execute($sqlParameters);   
        
        // Send an email to the new user
        global $do_not_reply_email,$support_email,$domain;
        
        // Finally, send the email to both the lender and borrower
        $message = "Hi {$firstname}!<br/><br/>";
        $message .= "Welcome to Qhojo, your community for borrowing gear from professionals around you.<br/><br/>";
        $message .= "If you have any questions, comments or concerns, don't hesitate to drop us a line at our support email address located at the bottom of this email.";
        
        $subject = "Welcome to Qhojo!";
        
        sendEmail($do_not_reply_email, $email, null, $subject, $message);                  
        
        return $user;
    }
    
    public function getUserDetails($user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM USER_EXTENDED_VW WHERE USER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        
        return $preparedStatement->fetch(PDO::FETCH_ASSOC);	            
    }
    
    // This is used by the reminder. It is only for borrowers who have made a request but haven't filled out their payment details.
    public function checkIfUserNeedsExtraFields($user_id)
    {
        // Does the user has an open request?
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT 1 FROM REQUESTED_VW WHERE USER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);           
        
        if ($row == null)
            return false;
        
        // Check if the user has missing fields
        $preparedStatement = $this->dbh->prepare('SELECT 1 FROM USER_VW WHERE USER_ID=:user_id and (PHONE_VERIFIED is null || PAYPAL_EMAIL_ADDRESS is null || BP_PRIMARY_CARD_URI is null) LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);  
        
        return $row != null;
    }    
    
    // Phone # (200), PP (300), CC (400)
    public function userNeedsExtraFields($user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT PHONE_VERIFIED,PAYPAL_EMAIL_ADDRESS,BP_PRIMARY_CARD_URI FROM USER_VW WHERE USER_ID=:user_id and (PHONE_VERIFIED is null || PAYPAL_EMAIL_ADDRESS is null || BP_PRIMARY_CARD_URI is null) LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
         
        if ($row == null)
            return 0;

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

        $preparedStatement = $this->dbh->prepare('update USER SET PROFILE_PICTURE_FILENAME=:profile_picture where ID=:user_id and ACTIVE=1 LIMIT 1');
        $preparedStatement->execute($sqlParameters);
    }
    
    public function clearProfilePicture($method, $user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('update USER SET PROFILE_PICTURE_FILENAME=null where ID=:user_id and ACTIVE=1 LIMIT 1');
        $preparedStatement->execute($sqlParameters);
    }    
    
    public function submitPersonalWebsite($method, $user_id, $website_url)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $sqlParameters[":website_url"] =  $website_url;

        $preparedStatement = $this->dbh->prepare('update USER SET PERSONAL_WEBSITE=:website_url where ID=:user_id and ACTIVE=1 LIMIT 1');
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
        $preparedStatement = $this->dbh->prepare('SELECT PHONE_VERIFICATION_DATESTAMP, PHONE_NUMBER FROM USER_VW WHERE USER_ID=:user_id and ACTIVE=1 LIMIT 1');
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
        $preparedStatement = $this->dbh->prepare('SELECT 1 FROM USER_VW WHERE PHONE_NUMBER=:phone_number and ACTIVE=1 LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);         
        
        if ($row != null)
            throw new PhoneNumberUsedByExistingUserException($method, $user_id);
        
        $sqlParameters = array();
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT PHONE_VERIFICATION_DATESTAMP FROM USER_VW WHERE USER_ID=:user_id and ACTIVE=1 LIMIT 1');
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
        
        $message = "Hey {$_SESSION["USER"]["FIRST_NAME"]}! It's Qhojo here. Here is your verification code: {$verification_code}";
        global $borrower_number;
        $this->sendText($phone_number, $borrower_number, $message, $method, $user_id);
    }    

    public function verifyVerificationCode($method, $user_id, $verification_code)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT PHONE_VERIFICATION_CODE FROM USER_VW WHERE USER_ID=:user_id and ACTIVE=1 LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);        
        
        if ($row["PHONE_VERIFICATION_CODE"] != $verification_code)
            throw new PhoneVerificationInvalidCodeException($method, $user_id);   
        
        $preparedStatement = $this->dbh->prepare('UPDATE USER set PHONE_VERIFIED=1 where ID=:user_id and ACTIVE=1 LIMIT 1');
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
            $preparedStatement = $this->dbh->prepare('SELECT ITEM_ID, TITLE FROM ITEM_VW WHERE LENDER_ID=:user_id and ITEM_ID=:item_id and ACTIVE=1 LIMIT 1');
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
            $preparedStatement = $this->dbh->prepare('SELECT TITLE, TRANSACTION_ID FROM BASE_VW WHERE (LENDER_ID=:receipient_id or BORROWER_ID=:receipient_id) and TRANSACTION_ID=:transaction_id LIMIT 1');
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
        $preparedStatement = $this->dbh->prepare('SELECT FIRST_NAME, EMAIL_ADDRESS FROM USER_VW WHERE USER_ID=:receipient_id and ACTIVE=1 LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);            
        $receipient_email = $row["EMAIL_ADDRESS"];
        $receipient_first_name = $row["FIRST_NAME"];
        
        $sqlParameters =  array();
        $sqlParameters[":sender_id"] =  $sender_id;
        $preparedStatement = $this->dbh->prepare('SELECT FIRST_NAME, EMAIL_ADDRESS FROM USER_VW WHERE USER_ID=:sender_id and ACTIVE=1 LIMIT 1');
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
    
    public function getMyNotifications($user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM NOTIFICATION_VW where RECEIPIENT_USER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);
        $rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        $preparedStatement = $this->dbh->prepare('UPDATE NOTIFICATION SET UNREAD=0 where RECEIPIENT_USER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);
        
        return $rows;
    }
    
    // Borrow pending + Lending open
    public function getActionItemCount($user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        
        $preparedStatement = $this->dbh->prepare('select (SELECT COUNT(*) FROM PENDING_VW WHERE STATE_B_ID=250 AND BORROWER_ID=:user_id) + (SELECT COUNT(*) FROM REQUESTED_VW WHERE STATE_B_ID=200 AND LENDER_ID=:user_id) as TOTAL;');
        $preparedStatement->execute($sqlParameters);        
        return $preparedStatement->fetchColumn();
    }
    
    // Generate a unique string
    // Save it in the database
    public function startLinkedIn($method, $user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $sqlParameters[":linkedin_state"] =  substr(str_shuffle(MD5(microtime())), 0, 16);
        
        $preparedStatement = $this->dbh->prepare('UPDATE USER SET LINKEDIN_STATE=:linkedin_state where ID=:user_id and ACTIVE=1 LIMIT 1');
        $preparedStatement->execute($sqlParameters);     
        
        return $sqlParameters[":linkedin_state"];
    }
    
    public function endLinkedIn($method, $user_id, $code, $state, $return_url)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $sqlParameters[":linkedin_state"] =  $state;
        
        $preparedStatement = $this->dbh->prepare('SELECT 1 FROM USER_EXTENDED_VW WHERE USER_ID=:user_id AND LINKEDIN_STATE=:linkedin_state AND ACTIVE=1 LIMIT 1');
        $preparedStatement->execute($sqlParameters);     
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

        if ($row != null)
        {
            global $linkedInAPIKey, $linkedInSecret;
            
            $params = array('grant_type' => 'authorization_code',
                            'client_id' => $linkedInAPIKey,
                            'client_secret' => $linkedInSecret,
                            'code' => $code,
                            'redirect_uri' => $return_url,
                            );

            // Access Token request
            $url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);        
            curl_close($ch);

            $token = json_decode($response);
  
            if (property_exists($token,"access_token"))
            {
                $params = array('oauth2_access_token' => $token->access_token,
                                'format' => 'json',);

                // Access Token request
                $url = 'https://api.linkedin.com/v1/people/~:(public-profile-url)?' . http_build_query($params);

                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);        
                curl_close($ch);              
                
                $result = json_decode($response);
                error_log($response);
                // Save to database
                if (property_exists($result, "publicProfileUrl"))
                {
                    $sqlParameters = array();
                    $sqlParameters[":user_id"] =  $user_id;
                    $sqlParameters[":linkedin_profile_url"] =  $result->publicProfileUrl;

                    $preparedStatement = $this->dbh->prepare('UPDATE USER SET LINKEDIN_PUBLIC_PROFILE_URL=:linkedin_profile_url where ID=:user_id and ACTIVE=1 LIMIT 1');
                    $preparedStatement->execute($sqlParameters);                     
                }
            }        
        }
    }
    
    public function disconnectLinkedIn($method, $user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('UPDATE USER SET LINKEDIN_PUBLIC_PROFILE_URL=null where ID=:user_id and ACTIVE=1 LIMIT 1');
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
