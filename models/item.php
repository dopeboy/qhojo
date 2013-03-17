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

	public function reserve($itemid) 
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		$row[] = $preparedStatement->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

        public function submitReservation($itemid, $userid, $duration, $message, $user_model)
	{
		$sqlParameters[":itemid"] =  $itemid;
		$sqlParameters[":userid"] =  $userid;
		$sqlParameters[":duration"] =  $duration;
                $sqlParameters[":confirmation_code"] = $code = getConfirmationID();
		$preparedStatement = $this->dbh->prepare('UPDATE ITEM set STATE_ID = 1,BORROWER_ID = :userid , BORROW_DURATION=:duration, CONFIRMATION_CODE=:confirmation_code where ID=:itemid');
		$preparedStatement->execute($sqlParameters);

                if ($preparedStatement->rowCount() > 0)
                {
                    $to      = 'arithmetic@gmail.com'; // Should be borrower email
                    $subject = 'Qhojo - Your item has been reserved';
                    $headers = 'From: webmaster@qhojo.com' . "\r\n" .
                        'Reply-To: webmaster@example.com' . "\r\n" . // This should be lender's email
                        'X-Mailer: PHP/' . phpversion();     
                    
                    $user = $user_model->getUserDetails($userid);
                    $message = "Hey " . $user["FIRST_NAME"] . "! It's qhojo here. Text this confirmation ID back to us when you've confirmed the item: " . $code;
                    $status = mail($to, $subject, $message, $headers);
                    
                    if ($status == true)
                    {
                        global $TwilioAccountSid;   
                        global $TwilioAuthToken;
                        $client = new Services_Twilio($TwilioAccountSid, $TwilioAuthToken);
                        $sms = $client->account->sms_messages->create("9493287319", $user['PHONE_NUMBER'],$message);
                        
                        return 0;
                    }
                    
                    return 2;
                }
                
		return 1;
	}
        
	public function reservationComplete($itemid) 
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
                        $client = new Services_Twilio($TwilioAccountSid, $TwilioAuthToken);
                        $sms = $client->account->sms_messages->create("9494852584", $row["LENDER_PHONE_NUMBER"],$message);                        
                        $sms = $client->account->sms_messages->create("9494852584", $row["LENDER_PHONE_NUMBER"],$message2);     
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
                        $message = "Hey " . $row["BORROWER_FIRST_NAME"] . "! It's qhojo here. We have received " . $row["LENDER_FIRST_NAME"] . "'s confirmation. You can go ahead and return the item.";

                        global $TwilioAccountSid;   
                        global $TwilioAuthToken;
                        $client = new Services_Twilio($TwilioAccountSid, $TwilioAuthToken);
                        $sms = $client->account->sms_messages->create("9493287319", $row["BORROWER_PHONE_NUMBER"],$message);                        
                        error_log("4");    
                        return 0;
                    }
                    
                    return 2;
                }

		return 1;
	}

	public function getDuration($itemid)
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT BORROW_DURATION FROM ITEM WHERE ITEM_ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row == null ? -1 : $row['BORROW_DURATION'];		
	}

        public function post($userid, $usermodel, $locationmodel)
	{
            $row[] = $usermodel->getUserDetails($userid);
            $row[] = $locationmodel->getAllLocations();
            
            return $row;
	}
        
	public function submitPost($userid, $title, $rate,$deposit,$description, $locationid, $files)
	{ 
            $filelist = array();
            
            foreach ($files as $file)
            {
                // TODO: revisit this 'if' check
                if (isset($file["name"]) && !empty($file["name"]))
                {
                    $filename = null;
                    $status = $this->processFile($file, $filename);

                    if ($status != 0)
                    {
                        error_log($file["name"] . ": " . $status);   
                        return $status;
                    }

                    array_push($filelist, $filename);
                }
            }
            
            $sqlParameters[":userid"] =  $userid;
            $sqlParameters[":title"] =  $title;
            $sqlParameters[":description"] =  $description;
            $sqlParameters[":rate"] =  $rate;
            $sqlParameters[":deposit"] =  $deposit;
            $sqlParameters[":locationid"] =  $locationid;
            $preparedStatement = $this->dbh->prepare('insert into ITEM (TITLE,DESCRIPTION,RATE,DEPOSIT,STATE_ID,LOCATION_ID,LENDER_ID) VALUES (:title,:description,:rate,:deposit,0,:locationid,:userid)');
            
            $preparedStatement->execute($sqlParameters);
            
            $newitem_id =  $this->dbh->lastInsertId(); 

            // -------------------------------------------------------------------------
            
            $sqlParameters[] = array();
            $itemid =  $this->dbh->lastInsertId();

            $datafields = array('ITEM_ID' => '', 'FILENAME' => '', 'PRIMARY_FLAG' => '');
            
            foreach ($filelist as $file)
            {
                $data[] = array('ITEM_ID' => $itemid, 'FILENAME' => $file, 'PRIMARY_FLAG' => 1);
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

            return $newitem_id;	
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
        
        public function submitFeedback($userid, $itemid, $value)
	{
                // Find out whether we are the borrower or the lender
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('select LENDER_ID, BORROWER_ID from ITEM where ID=:itemid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		$flag = ($row["LENDER_ID"] == $userid ? 0 : 1);
                
                $rating_query = null;
		$stars_query = null;
                
                if ($flag == 0)
                {
			$rating_query = "LENDER_TO_BORROWER_STARS";
			$stars_query = "LENDER_ID";                    
                }
                
                else
                {
			$rating_query = "BORROWER_TO_LENDER_STARS";
			$stars_query = "BORROWER_ID";                    
                }
                
		$sqlParameters[":rating"] =  $value;
		$sqlParameters[":userid"] =  $userid;		

		$preparedStatement = $this->dbh->prepare('update ITEM set ' . $rating_query . '=:rating WHERE ID=:itemid and ' . $stars_query . '=:userid');
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
}

?>
