<?php

require "Services/Twilio.php";



class ItemModel extends Model 
{
	public function index($itemid) 
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW where ITEM_ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		$row[] = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		$preparedStatement = $this->dbh->prepare('SELECT FILENAME FROM ITEM_PICTURES where ITEM_ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		$row[] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

                $usermodel = new UserModel();                
                $row[] = $usermodel->getFeedbackAsLender($row[0]['LENDER_ID']);
                
		return $row;
	}
        
	public function main() 
	{
		$preparedStatement = $this->dbh->prepare('select * from ITEM_VW where ITEM_STATE_ID=0');
		$preparedStatement->execute();
		$rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
                                
		return $rows;
	}       
        
	public function test() 
	{
		return null;
	}           
        
	public function search($query) 
	{
		$sqlParameters[":search"] =  '%'. $query .'%';
                $preparedStatement = $this->dbh->prepare('select * from ITEM_VW where ITEM_STATE_ID=0 and (lower(TITLE) like lower(:search) OR lower(DESCRIPTION) like lower(:search))');
		$preparedStatement->execute($sqlParameters);
		$rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

		return $rows;
	}

	public function request($itemid) 
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		$row[] = $preparedStatement->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
        
        public function getItemDetails($itemid)
        {
            $sqlParameters[":itemid"] =  $itemid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid');
            $preparedStatement->execute($sqlParameters);
            $row[] = $preparedStatement->fetch(PDO::FETCH_ASSOC);
            return $row;            
        }

        // Passed in parameters are all of the borrower
        public function submitRequest($itemid, $userid, $duration, $message)
	{
		$sqlParameters[":itemid"] =  $itemid;
		$sqlParameters[":userid"] =  $userid;
		$sqlParameters[":duration"] =  $duration;
                $sqlParameters[":message"] =  $message;
		$preparedStatement = $this->dbh->prepare('INSERT INTO ITEM_REQUESTS (ITEM_ID,REQUESTER_ID,DURATION,MESSAGE) VALUES (:itemid, :userid, :duration, :message)');
		$preparedStatement->execute($sqlParameters);
            
                return $preparedStatement->rowCount() == 1 ? 0 : 1;
	}
        
	public function requestComplete($itemid) 
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		$row[] = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row;
	}      
            
	public function getCurrentLoans($userid)
	{
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('select *, 1 as "LENDER" from ITEM_VW where LENDER_ID=:userid and ITEM_STATE_ID != 3');                
		$preparedStatement->execute($sqlParameters);
		return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
	}
        
	public function getPastLoans($userid)
	{
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('select *, 1 as "LENDER" from ITEM_VW where LENDER_ID=:userid and ITEM_STATE_ID = 3');                
		$preparedStatement->execute($sqlParameters);
		return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
	}        
        
        public function getRequests($userid)
        {
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('select * from ITEM_REQUESTS_VW where LENDER_ID=:userid');                
		$preparedStatement->execute($sqlParameters);
		return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);            
        }       

        public function getCurrentBorrows($userid)
	{
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('select *, 0 as "LENDER" from ITEM_VW where BORROWER_ID=:userid and ITEM_STATE_ID != 3');                
		$preparedStatement->execute($sqlParameters);
		return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
	}
        
        public function getPastBorrows($userid)
	{
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('select *, 0 as "LENDER" from ITEM_VW where BORROWER_ID=:userid and ITEM_STATE_ID = 3');                
		$preparedStatement->execute($sqlParameters);
		return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
	}        

	public function borrowerConfirm($confirmation_code, $phone_number) 
	{
            error_log("cc:" . $confirmation_code);
            error_log("ph:" . $phone_number);
		$sqlParameters[":confirmation_code"] =  $confirmation_code;
                $sqlParameters[":phone_number"] =  $phone_number;
                $sqlParameters[":status_id"] =  1;
                $preparedStatement = $this->dbh->prepare('select * from ITEM_VW where CONFIRMATION_CODE=:confirmation_code and BORROWER_PHONE_NUMBER=:phone_number and ITEM_STATE_ID=:status_id');
		$preparedStatement->execute($sqlParameters);
                $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
                
              //  error_log("1");
                if ($preparedStatement->rowCount() == 1)
                {
                    $sqlParameters = null;
                    $sqlParameters[":status_id"] =  2;
                    $sqlParameters[":item_id"] =  $row['ITEM_ID'];
                    $preparedStatement = $this->dbh->prepare('update ITEM set STATE_ID=:status_id WHERE ID=:item_id');
                    $preparedStatement->execute($sqlParameters);    
            //    error_log("2");    
                    if ($preparedStatement->rowCount() == 1)
                    {//error_log("3");    
                        $message = "Hey " . $row["LENDER_FIRST_NAME"] . "! It's qhojo here. We have received " . $row["BORROWER_FIRST_NAME"] . "'s confirmation. You can go ahead and hand the item over.";
                        $message2 = "When you meet " . $row["BORROWER_FIRST_NAME"] . " again, text the following confirmation code to this number: " . $confirmation_code;

                        global $TwilioAccountSid;   
                        global $TwilioAuthToken;
                        global $lender_number;
                        $client = new Services_Twilio($TwilioAccountSid, $TwilioAuthToken);
                        $sms = $client->account->sms_messages->create($lender_number, $row["LENDER_PHONE_NUMBER"],$message);                        
                        $sms = $client->account->sms_messages->create($lender_number, $row["LENDER_PHONE_NUMBER"],$message2);     
                        error_log("4");    
                        return 0;
                    }
                    
                    return 2;
                }

		return 1;
	}

	public function lenderConfirm($confirmation_code, $phone_number) 
	{
            error_log("cc:" . $confirmation_code);
            error_log("ph:" . $phone_number);
		$sqlParameters[":confirmation_code"] =  $confirmation_code;
                $sqlParameters[":phone_number"] =  $phone_number;
                $sqlParameters[":status_id"] =  2;
                $preparedStatement = $this->dbh->prepare('select * from ITEM_VW where CONFIRMATION_CODE=:confirmation_code and LENDER_PHONE_NUMBER=:phone_number and ITEM_STATE_ID=:status_id');
		$preparedStatement->execute($sqlParameters);
                $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
                
                error_log("1");
                if ($preparedStatement->rowCount() == 1)
                {
                    $sqlParameters = null;
                    $sqlParameters[":status_id"] =  3;
                    $sqlParameters[":item_id"] =  $row['ITEM_ID'];
                    $preparedStatement = $this->dbh->prepare('update ITEM set STATE_ID=:status_id WHERE ID=:item_id');
                    $preparedStatement->execute($sqlParameters);    
                    error_log("2");    
                    if ($preparedStatement->rowCount() == 1)
                    {
                        error_log("3");    
                        $message = "Hey " . $row["BORROWER_FIRST_NAME"] . "! It's qhojo here. We have received " . $row["LENDER_FIRST_NAME"] . "'s confirmation. You can go ahead and return the item and carry on with the rest of your day";

                        global $TwilioAccountSid;   
                        global $TwilioAuthToken;
                        global $borrower_number;
                        $client = new Services_Twilio($TwilioAccountSid, $TwilioAuthToken);
                        $sms = $client->account->sms_messages->create($borrower_number, $row["BORROWER_PHONE_NUMBER"],$message);   
                        error_log("4");    
                        
                        // TODO: MONEY STUFF HAPPENS HERE
                        
                        // send an email to lender and borrower and ask for feedback
                        $message_to_lender = "Hey " .  $row["LENDER_FIRST_NAME"] . "!<br/><br/>";
                        $message_to_lender .= "Now that the transaction is complete, we owe you some green. Check your paypal account: we have deposited \$999 minus a 3% transaction fee. <br/><br/>";
                        $message_to_lender .= "Also, when you get a second, help our community be a better one. Submit some feedback on this transaction by clicking here.<br/><br/>";
                        $message_to_lender .= "<br/><br/>-team qhojo";
                        
                        $message_to_borrower = "Hey " .  $row["BORROWER_FIRST_NAME"] . "!<br/><br/>";
                        $message_to_borrower .= "Now that the transaction is complete, we're gonna need some of your green. Check your paypal account: we have dedeucted \$999. <br/><br/>";
                        $message_to_borrower .= "Also, when you get a second, help our community be a better one. Submit some feedback on this transaction by clicking here.<br/><br/>";
                        $message_to_borrower .= "<br/><br/>-team qhojo";                        
                        
                        $this->sendEmail('do-not-reply@qhojo.com', $row['LENDER_EMAIL_ADDRESS'], 'do-not-reply@qhojo.com', 'qhojo - ' . $row['TITLE'] . ' - Transaction Complete!', $message_to_lender);
                        $this->sendEmail('do-not-reply@qhojo.com', $row['BORROWER_EMAIL_ADDRESS'], 'do-not-reply@qhojo.com', 'qhojo - ' . $row['TITLE'] . ' - Transaction Complete!', $message_to_borrower);
                        
                        return 0;
                    }
                    
                    return 2;
                }

		return 1;
	}

	public function getDuration($itemid)
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT DURATION FROM ITEM WHERE ITEM_ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row == null ? -1 : $row['DURATION'];		
	}

        public function post($userid, $usermodel, $locationmodel)
	{
            $row[] = $usermodel->getUserDetails($userid);
            $row[] = $locationmodel->getAllLocations();
            
            return $row;
	}
        
	public function submitPost($itemid, $userid, $title, $rate,$deposit,$description, $locationid, $files)
	{ 
            $filelist = array();
            
            foreach ($files as $file)
            {
                $prefix = '/uploads/item/';

                if (substr($file, 0, strlen($prefix)) == $prefix) {
                    $file = substr($file, strlen($prefix), strlen($file));
                    array_push($filelist, $file);
                }                 
            }
            
            $sqlParameters[":id"] =  $itemid;
            $sqlParameters[":userid"] =  $userid;
            $sqlParameters[":title"] =  $title;
            $sqlParameters[":description"] =  $description;
            $sqlParameters[":rate"] =  $rate;
            $sqlParameters[":deposit"] =  $deposit;
            $sqlParameters[":locationid"] =  $locationid;
            $preparedStatement = $this->dbh->prepare('insert into ITEM (ID, TITLE,DESCRIPTION,RATE,DEPOSIT,STATE_ID,LOCATION_ID,LENDER_ID) VALUES (:id,:title,:description,:rate,:deposit,0,:locationid,:userid)');
            $preparedStatement->execute($sqlParameters);

            $sqlParameters[] = array();
            $datafields = array('ITEM_ID' => '', 'FILENAME' => '', 'PRIMARY_FLAG' => '');
            
            foreach ($filelist as $key=>$file)
            {
                $data[] = array('ITEM_ID' => $itemid, 'FILENAME' => $file, 'PRIMARY_FLAG' => $key == 0 ? 1 : 0);
            }
            
            $insert_values = array();
            foreach($data as $d)
            {
                $question_marks[] = '('  . $this->placeholders('?', sizeof($d)) . ')';
                $insert_values = array_merge($insert_values, array_values($d));
            }

            $sql = "INSERT INTO ITEM_PICTURES (" . implode(",", array_keys($datafields) ) . ") VALUES " . implode(',', $question_marks);
            error_log($sql);
            //error_log($insert_values);
            
            $this->dbh->beginTransaction();
            $stmt = $this->dbh->prepare ($sql);
            
            try 
            {
                $stmt->execute($insert_values);
            } 
            
            catch (PDOException $e)
            {
                error_log($e->getMessage());
            }
            
            $this->dbh->commit();

            return $itemid;	
	}   
        
        public function postComplete($userid, $itemid)
        {
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM WHERE ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		return $preparedStatement->fetch(PDO::FETCH_ASSOC);                
        }
        
        function placeholders($text, $count=0, $separator=","){
            $result = array();
            if($count > 0){
                for($x=0; $x<$count; $x++){
                    $result[] = $text;
                }
            }

            return implode($separator, $result);
        }        
        
        public function processFile($file, &$filename)
        {
            $arr = explode(".", $file["name"]);
            $name = current($arr);
            $extension = end($arr);   
            $allowedExts = array("jpg", "jpeg", "gif", "png", "JPG");
        
            if ((($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/png") || ($file["type"] == "image/pjpeg"))
                && ($file["size"] < 10000000) && in_array($extension, $allowedExts))
            {     
                if ($file["error"] > 0)
                {    
                    return 2;
                }
              
                else
                {
                    $directory = "uploads/item/";
                    $filename = $name. "_" . date("Ymd_His") . "." . $extension;
                    
                    if (file_exists($directory.$filename))
                    {
                        return 3;
                    }
                    
                    else
                    {
                        move_uploaded_file($file["tmp_name"],$directory.$filename);
                        return 0;
                    }
                }
            }
            
            else
            {
                return 1;
            }            
        }
        
        public function feedback($itemid)
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row;	
	}        
        
        public function submitFeedback($userid, $itemid, $rating, $comments)
	{
                // Find out whether we are the borrower or the lender
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('select LENDER_ID, BORROWER_ID from ITEM where ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		$flag = ($row["LENDER_ID"] == $userid ? 0 : 1);
                
                $rating_query = null;
		$stars_query = null;
                $comments_query = null;
                
                if ($flag == 0)
                {
			$rating_query = "LENDER_TO_BORROWER_STARS";
			$stars_query = "LENDER_ID";  
                        $comments_query = "LENDER_TO_BORROWER_COMMENTS";
                }
                
                else
                {
			$rating_query = "BORROWER_TO_LENDER_STARS";
			$stars_query = "BORROWER_ID";                    
                        $comments_query = "BORROWER_TO_LENDER_COMMENTS";
                }
                
		$sqlParameters[":rating"] =  $rating;
		$sqlParameters[":userid"] =  $userid;		
		$sqlParameters[":comments"] =  $comments;		                

		$preparedStatement = $this->dbh->prepare('update ITEM set ' . $rating_query . '=:rating, ' . $comments_query . '=:comments' . ' WHERE ID=:itemid and ' . $stars_query . '=:userid');
		$preparedStatement->execute($sqlParameters);

		return $preparedStatement->rowCount() > 0 ? 0 : 1;               
	}
        
        public function feedbackComplete($itemid)
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row;	
	}       
        
        public function getRequest($request_id)
        {
		$sqlParameters[":requestid"] =  $request_id;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_REQUESTS WHERE REQUEST_ID=:requestid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row;	            
        }
        
        public function accept($request_id)
        {
            $sqlParameters[":requestid"] =  $request_id;
            $preparedStatement = $this->dbh->prepare('UPDATE ITEM_REQUESTS set ACCEPTED_FLAG = 1 where REQUEST_ID=:requestid');
            $preparedStatement->execute($sqlParameters);

            $itemreq = $this->getRequest($request_id);
            
            $sqlParameters = array();
            $sqlParameters[":itemid"] =  $itemreq['ITEM_ID'];
            $sqlParameters[":confirmation_code"] = $confirmation_code = getConfirmationID();
            $preparedStatement = $this->dbh->prepare('UPDATE ITEM set STATE_ID = 1,CONFIRMATION_CODE=:confirmation_code where ID=:itemid');
            $preparedStatement->execute($sqlParameters);
            
            $sqlParameters = array();
            $sqlParameters[":itemid"] =  $itemreq['ITEM_ID'];
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            // email to lender
            $message = "Hey " . $row['LENDER_FIRST_NAME'] . "!<br/><br/>";
            $message .= "You have accepted " . $row['BORROWER_FIRST_NAME'] . "'s rental request. Your item, " . $row['TITLE'] . ", is now reserved for " . $row['DURATION'] . " days.<br/><br/>";
            $message .= "Your confirmation code is: <b><u>" . $row['CONFIRMATION_CODE'] . "</b></u><br/><br/>";
            $message .= "Here's what you need to do next:<br/><br/>";
            $message .= "1) Over email, arrange to meet with " .  $row['BORROWER_FIRST_NAME'] . ". Here's " . $row['BORROWER_FIRST_NAME'] . "'s email address for reference: " .  $row['BORROWER_EMAIL_ADDRESS'] . "<br/>";
            $message .= "2) Once you guys meet, " .  $row['BORROWER_FIRST_NAME'] . " will check out your item. Once satisfied, " . $row['BORROWER_FIRST_NAME'] . " will confirm to qhojo via text message." . "<br/>";
            $message .= "3) We'll pass on this confirmation to you via text message. <b>Only hand the item over once you've received this confirmation from us</b>. At this point, the rental period has started and " . $row['BORROWER_FIRST_NAME'] . " will be responsible to bring your item back after the agreed upon duration.<br/><br/>";
            $message .= "Still confused? Check out our <a href=\"http://" . $_SERVER[HTTP_HOST] . "/document/howitworks\">how-it-works guide</a>";
            $message .= "<br/><br/>-team qhojo";
            
            $this->sendEmail('do-not-reply@qhojo.com', $row['LENDER_EMAIL_ADDRESS'], 'do-not-reply@qhojo.com', 'qhojo - ' . $row['TITLE'] . ' Reservation Details', $message);
            
            global $borrower_number;
            
            // email to borrower
            $message = "Hey " . $row['BORROWER_FIRST_NAME'] . "!<br/><br/>";
            $message .= "Your rental request for item " . $row['TITLE'] . " for a duration of " . $row['DURATION'] . " days has been approved! <br/><br/>";
            $message .= "Your confirmation code is: <b><u>" . $row['CONFIRMATION_CODE'] . "</b></u>. We just texted it to you. Hang on to it because you'll need it later.<br/><br/>";
            $message .= "Here's what you need to do next:<br/><br/>";
            $message .= "1) Over email, arrange to meet with " .  $row['LENDER_FIRST_NAME'] . ". Here's " . $row['LENDER_FIRST_NAME'] . "'s email address for reference: " .  $row['LENDER_EMAIL_ADDRESS'] . "<br/>";
            $message .= "2) Once you guys meet, verify the quality of the item. Once satisfied, reply to the text we sent you with the confirmation code above. (In case you lose the original text, here's the number you need to send the code to: " . $borrower_number . ")<br/>";
            $message .= "3) We'll pass on this confirmation to " .  $row['LENDER_FIRST_NAME'] . " via text message. Once " .  $row['LENDER_FIRST_NAME'] . " has received it, he/she will hand the item over to you. At this point, the rental period has started and you are responsible to bring your item back after the agreed upon duration.<br/><br/>";
            $message .= "Still confused? Check out our <a href=\"http://" . $_SERVER[HTTP_HOST] . "/document/howitworks\">how-it-works guide</a>";
            $message .= "<br/><br/>-team qhojo";
            
            $this->sendEmail('do-not-reply@qhojo.com', $row['BORROWER_EMAIL_ADDRESS'], 'do-not-reply@qhojo.com', 'qhojo - ' . $row['TITLE'] . ' Reserved!', $message);
          
            $sms_message = "Hey " . $row["BORROWER_FIRST_NAME"] . "! It's qhojo here. Text this confirmation ID back to us after you have met " . $row["LENDER_FIRST_NAME"] . " and have verified the item: " . $confirmation_code;
            global $TwilioAccountSid;   
            global $TwilioAuthToken;
            $client = new Services_Twilio($TwilioAccountSid, $TwilioAuthToken);
            $sms = $client->account->sms_messages->create($borrower_number, $row['BORROWER_PHONE_NUMBER'],$sms_message);
                    
            return $row;	                
        }
        
        public function ignore($request_id)
        {
            $sqlParameters[":requestid"] =  $request_id;
            $preparedStatement = $this->dbh->prepare('UPDATE ITEM_REQUESTS set ACCEPTED_FLAG = 0 where REQUEST_ID=:requestid');
            $preparedStatement->execute($sqlParameters);
            
            return $preparedStatement->rowCount() == 1 ? 0 : 1;
        }        
}

?>
