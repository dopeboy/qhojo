<?php

class TransactionModel extends Model 
{
    public function getLendingRequests($lender_id)
    {
        $sqlParameters[":lender_id"] =  $lender_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM REQUESTED_VW WHERE LENDER_ID=:lender_id');
        $preparedStatement->execute($sqlParameters);        
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));        
    }
    
    public function getLendingReservedAndExchanged($lender_id)
    {
        $sqlParameters[":lender_id"] =  $lender_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM RESERVED_AND_EXCHANGED_AND_LATE_VW WHERE LENDER_ID=:lender_id');
        $preparedStatement->execute($sqlParameters);        
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));               
    }
    
    public function getLendingReturnedAndNeedReview($lender_id)
    {
        $sqlParameters[":lender_id"] =  $lender_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM RETURNED_AND_NEED_LENDER_REVIEW_VW WHERE LENDER_ID=:lender_id');
        $preparedStatement->execute($sqlParameters);        
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));        
    }
    
    public function getLendingCompleted($lender_id)
    {
        $sqlParameters[":lender_id"] =  $lender_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM COMPLETED_BY_LENDER_VW WHERE LENDER_ID=:lender_id');
        $preparedStatement->execute($sqlParameters);        
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));          
    }
    
    public function getBorrowingRequests($borrower_id)
    {
        $sqlParameters[":borrower_id"] =  $borrower_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM REQUESTED_VW WHERE BORROWER_ID=:borrower_id');
        $preparedStatement->execute($sqlParameters);        
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));        
    }
    
    public function getBorrowingReservedAndExchanged($borrower_id)
    {
        $sqlParameters[":borrower_id"] =  $borrower_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM RESERVED_AND_EXCHANGED_AND_LATE_VW WHERE BORROWER_ID=:borrower_id');
        $preparedStatement->execute($sqlParameters);        
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));               
    }
    
    public function getBorrowingReturnedAndNeedReview($borrower_id)
    {
        $sqlParameters[":borrower_id"] =  $borrower_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM RETURNED_AND_NEED_BORROWER_REVIEW_VW WHERE BORROWER_ID=:borrower_id');
        $preparedStatement->execute($sqlParameters);        
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));        
    }
    
    public function getBorrowingCompleted($borrower_id)
    {
        $sqlParameters[":borrower_id"] =  $borrower_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM COMPLETED_BY_BORROWER_VW WHERE BORROWER_ID=:borrower_id');
        $preparedStatement->execute($sqlParameters);        
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));          
    }
    
    public function getLendingPendingRequests($lender_id)
    {
        $sqlParameters[":lender_id"] =  $lender_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM PENDING_VW WHERE LENDER_ID=:lender_id');
        $preparedStatement->execute($sqlParameters);        
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));               
    }
    
    public function getBorrowingPendingRequests($borrower_id)
    {
        $sqlParameters[":borrower_id"] =  $borrower_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM PENDING_VW WHERE BORROWER_ID=:borrower_id');
        $preparedStatement->execute($sqlParameters);        
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));               
    }    
    
    public function review($method, $user_id, $transaction_id)
    {   
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;
        
        $preparedStatement = $this->dbh->prepare('select 1 as LENDER_FLAG from RETURNED_AND_NEED_LENDER_REVIEW_VW where TRANSACTION_ID=:transaction_id AND LENDER_ID=:user_id LIMIT 1 ' . 
                                                 'UNION ' . 
                                                 'select 0 as LENDER_FLAG from RETURNED_AND_NEED_BORROWER_REVIEW_VW where TRANSACTION_ID=:transaction_id AND BORROWER_ID=:user_id LIMIT 1'                
                                                 );
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

        // If we get no results, that means this user wasn't involved in the transaction or the transaction has already been reviewed by them
        if ($row == null)
            throw new InvalidReviewAttemptException($method);       
        
        // If we got results, return the transaction from their perspective
        $clause = $row["LENDER_FLAG"] == 1 ? "LENDER_ID" : "BORROWER_ID";
        $db_view = $row["LENDER_FLAG"] == 1 ? "RETURNED_AND_NEED_LENDER_REVIEW_VW" : "RETURNED_AND_NEED_BORROWER_REVIEW_VW";
        
        $preparedStatement = $this->dbh->prepare('select * from ' . $db_view . ' where TRANSACTION_ID=:transaction_id AND ' . $clause . '=:user_id');
        $preparedStatement->execute($sqlParameters);
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));        
    }      
    
    public function submitReview($method, $user_id, $transaction_id, $rating, $comments)
    {
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;
        
        // Determine whether the user is the lender or the borrower of the given transaction
        $preparedStatement = $this->dbh->prepare('select 1 as LENDER_FLAG from RETURNED_AND_NEED_LENDER_REVIEW_VW where TRANSACTION_ID=:transaction_id AND LENDER_ID=:user_id LIMIT 1 ' . 
                                                 'UNION ' . 
                                                 'select 0 as LENDER_FLAG from RETURNED_AND_NEED_BORROWER_REVIEW_VW where TRANSACTION_ID=:transaction_id AND BORROWER_ID=:user_id LIMIT 1'                
                                                 );
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

        // If we get no results, that means this user wasn't involved in the transaction or the transaction has already been reviewed by them
        if ($row == null)
            throw new InvalidReviewAttemptException($method);              
        
        // If the user is the lender of the transaction
        if ($row["LENDER_FLAG"] == 1)
        {
            $preparedStatement = $this->dbh->prepare('select * from RETURNED_AND_NEED_LENDER_REVIEW_VW where TRANSACTION_ID=:transaction_id AND LENDER_ID=:user_id');
            $preparedStatement->execute($sqlParameters);
            $transaction = reset($this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC)));
            
            // 700 -> 900
            if ($transaction["FINAL_STATE_ID"] == 700)
                $this->insertDetail($method, $transaction_id, $this->getEdgeID(700,900,$method, $user_id), array("COMMENT" => $comments, "RATING" => $rating), $user_id);              
            
            // 1100 -> 1200
            else if ($transaction["FINAL_STATE_ID"] == 1100)
                $this->insertDetail($method, $transaction_id, $this->getEdgeID(1100,1200,$method, $user_id), array("COMMENT" => $comments, "RATING" => $rating), $user_id);                 
        }
        
        // If the user is the borrower of the transaction
        else if ($row["LENDER_FLAG"] == 0)
        {
            $preparedStatement = $this->dbh->prepare('select * from RETURNED_AND_NEED_BORROWER_REVIEW_VW where TRANSACTION_ID=:transaction_id AND BORROWER_ID=:user_id');
            $preparedStatement->execute($sqlParameters);
            $transaction = reset($this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC)));
            
            // 700 -> 1100
            if ($transaction["FINAL_STATE_ID"] == 700)
                $this->insertDetail($method, $transaction_id, $this->getEdgeID(700,1100,$method, $user_id), array("COMMENT" => $comments, "RATING" => $rating), $user_id);            
            
            // 900 -> 1000
            else if ($transaction["FINAL_STATE_ID"] == 900)
                $this->insertDetail($method, $transaction_id, $this->getEdgeID(900,1000,$method, $user_id), array("COMMENT" => $comments, "RATING" => $rating), $user_id);          
        }
    }   
    
    public function reviewSubmitted($method, $user_id, $transaction_id)
    {   
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;
        
        $preparedStatement = $this->dbh->prepare('select 1 as LENDER_FLAG from COMPLETED_BY_LENDER_VW where TRANSACTION_ID=:transaction_id AND LENDER_ID=:user_id LIMIT 1 ' . 
                                                 'UNION ' . 
                                                 'select 0 as LENDER_FLAG from COMPLETED_BY_BORROWER_VW where TRANSACTION_ID=:transaction_id AND BORROWER_ID=:user_id LIMIT 1'                
                                                 );
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

        // If we get no results, that means the review didn't submit successfully
        if ($row == null)
            throw new ReviewSubmissionFailureException($method);       
        
        $clause = $row["LENDER_FLAG"] == 1 ? "LENDER_ID" : "BORROWER_ID";
        $db_view = $row["LENDER_FLAG"] == 1 ? "COMPLETED_BY_LENDER_VW" : "COMPLETED_BY_BORROWER_VW";
        
        $preparedStatement = $this->dbh->prepare('select * from ' . $db_view . ' where TRANSACTION_ID=:transaction_id AND ' . $clause . '=:user_id');
        $preparedStatement->execute($sqlParameters);
        return $this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC));        
    }  
    
    public function createTransaction($method, $item_id, $borrower_id)
    {
        $transaction_id = getRandomID();
        
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":item_id"] =  $item_id;
        $sqlParameters[":borrower_id"] =  $borrower_id;
        
        $preparedStatement = $this->dbh->prepare('INSERT INTO TRANSACTION VALUES (:transaction_id, :item_id, :borrower_id)');
        try { $preparedStatement->execute($sqlParameters); } 
        catch (PDOException $e) { throw new DatabaseException($e->getMessage(), $method, $borrower_id, $e); } 
        
        return $transaction_id;
    }
    
    // $source = 0 means borrower is withdrawing their request, else lender is rejecting the request
    public function rejectRequest($method, $user_id, $transaction_id, $source, $reject_option, $reject_reason)
    {
        // Check if user is lender on this req'd transaction
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;
        $clause = $source == 0 ? "BORROWER" : "LENDER";
        
        $preparedStatement = $this->dbh->prepare('select 1 from REQUESTED_VW where TRANSACTION_ID=:transaction_id and ' . $clause . '_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

        if ($row == null)
            throw new RejectRequestException($method, $user_id, null, $transaction_id);    
        
        // 200 -> 401
        if ($source == 0)
            $this->insertDetail($method, $transaction_id, $this->getEdgeID(200,401,$method, $user_id), array("REJECT_ID" => $reject_option, "REASON" => $reject_reason), $user_id);
        // 200 -> 400
        else if ($source == 1)
            $this->insertDetail($method, $transaction_id, $this->getEdgeID(200,400,$method, $user_id), array("REJECT_ID" => $reject_option, "REASON" => $reject_reason), $user_id);
        else
             throw new RejectRequestException($method, $user_id, null, $transaction_id);    
    }    
    
    // $source = 0 means borrower, else lender
    public function cancelRequest($method, $user_id, $transaction_id, $source, $cancel_option, $cancel_reason)
    {
        // Is user a borrower on this transaction
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;            
        $clause = $source == 0 ? "BORROWER" : "LENDER";

        $preparedStatement = $this->dbh->prepare('select 1 from RESERVED_AND_EXCHANGED_AND_LATE_VW where TRANSACTION_ID=:transaction_id and ' . $clause . '_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);            
        
        if ($row == null)
            throw new CancelRequestException($method, $user_id, null, $transaction_id);            

        // 300 -> 601
        if ($source == 0)
            $this->insertDetail($method, $transaction_id, $this->getEdgeID(300,601,$method, $user_id), array("CANCEL_ID" => $cancel_option, "REASON" => $cancel_reason), $user_id);      
        // 300 -> 600
        else if ($source == 1)
            $this->insertDetail($method, $transaction_id, $this->getEdgeID(300,600,$method, $user_id), array("CANCEL_ID" => $cancel_option, "REASON" => $cancel_reason), $user_id); 
        else
             throw new CancelRequestException($method, $user_id, null, $transaction_id);          
    }
    
    public function getBorrowerOfRequest($method, $user_id, $transaction_id)
    {
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;            

        $preparedStatement = $this->dbh->prepare('select BORROWER_ID from REQUESTED_VW where TRANSACTION_ID=:transaction_id AND LENDER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);       
        
        if ($row == null)
            throw new AcceptRequestException($method, $user_id);
        
        return $row["BORROWER_ID"];
    }
    
    public function acceptRequestSuccess($method, $user_id, $transaction_id)
    {
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;           
        $preparedStatement = $this->dbh->prepare('select * from RESERVED_VW where TRANSACTION_ID=:transaction_id AND LENDER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);
        $rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        if ($rows == null)
            throw new InvalidReservedTransaction($method, $user_id, $transaction_id);
            
        return $this->denormalize($rows);             
    }
    
    public function acceptRequest($method, $user_id, $transaction_id)
    {
        // Check if user is the lender on the transaction
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;            

        $preparedStatement = $this->dbh->prepare('select TRANSACTION_ID, LENDER_FIRST_NAME, LENDER_ID, LENDER_EMAIL_ADDRESS, BORROWER_FIRST_NAME, BORROWER_ID, BORROWER_EMAIL_ADDRESS, ITEM_ID, TITLE, DATA from REQUESTED_VW where TRANSACTION_ID=:transaction_id AND LENDER_ID=:user_id and STATE_B_ID=200 LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);            
        
        if ($row == null)
            throw new AcceptRequestException($method, $user_id);    
        
        $cc = getRandomID();
        
        // 200 -> 300
        $this->insertDetail($method, $transaction_id, $this->getEdgeID(200,300,$method, $user_id), array("CONFIRMATION_CODE" => $cc), $user_id);          
        
        global $do_not_reply_email, $domain, $borrower_number, $support_email;
        
        $data = json_decode($row['DATA']); 
        
        $start_date = date("m/d", strtotime($data->{"START_DATE"}));
        $end_date = date("m/d", strtotime($data->{"END_DATE"}));

        // Send email to lender
        $message = "Hi {$row["LENDER_FIRST_NAME"]} and {$row["BORROWER_FIRST_NAME"]},<br/><br/>";
        $message .= "The transaction is ready to move forward. {$row["LENDER_FIRST_NAME"]}, you have accepted {$row["BORROWER_FIRST_NAME"]}'s request to rent your item, <a href=\"{$domain}/item/index/{$row["ITEM_ID"]}\">{$row["TITLE"]}</a>, from {$start_date} to {$end_date}. Your guys' confirmation code is <b>{$cc}</b>.<br/><br/>";
        $message .= "Here's what the both of you have to do next:<br/><br/>";
        $message .= "(1) Work out a place to meet over email (you can use this email chain since both of you are already on it).<br/>";
        $message .= "(2) {$row["BORROWER_FIRST_NAME"]}, when you meet {$row["LENDER_FIRST_NAME"]} on {$start_date}, text the above confirmation code to this number: <a href=\"tel:{$borrower_number}\">{$borrower_number}</a>.<br/>";
        $message .= "(3) {$row["LENDER_FIRST_NAME"]}, as soon we receive {$row["BORROWER_FIRST_NAME"]}'s text message, we will text you a message confirming so. Only hand over your item to {$row["BORROWER_FIRST_NAME"]} once you have received this message from us.<br/><br/>";
        $message .= "Questions? Hit us at <a href=\"mailto:{$support_email}\">{$support_email}</a>";
        
        $subject = "{$row["TITLE"]} - RESERVED - Item has been reserved - Transaction ID: {$row["TRANSACTION_ID"]}";
        sendEmail($do_not_reply_email, $row["LENDER_EMAIL_ADDRESS"] . ',' . $row["BORROWER_EMAIL_ADDRESS"], null, $subject, $message);        
    }
    
    public function pending($method, $user_id, $transaction_id)
    {
        // Check if user is the lender on the transaction
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;            

        $preparedStatement = $this->dbh->prepare('select TRANSACTION_ID, LENDER_FIRST_NAME, LENDER_ID, LENDER_EMAIL_ADDRESS, BORROWER_FIRST_NAME, BORROWER_ID, BORROWER_EMAIL_ADDRESS, ITEM_ID, TITLE from REQUESTED_VW where TRANSACTION_ID=:transaction_id AND LENDER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);            
        
        if ($row == null)
            throw new PendingTransactionException($method, $user_id);    
        
        // 200 -> 250
        $this->insertDetail($method, $transaction_id, $this->getEdgeID(200,250,$method, $user_id), null, $user_id);  
        
        global $do_not_reply_email, $domain;
        
        // Send email to lender
        $message = "Hi {$row["LENDER_FIRST_NAME"]},<br/><br/>";
        $message .= "You have accepted {$row["BORROWER_FIRST_NAME"]}'s request to rent your item: <a href=\"{$domain}/item/index/{$row["ITEM_ID"]}\">{$row["TITLE"]}</a><br/><br/>";
        $message .= "This transaction is currently in the <b>pending</b> state because {$row["BORROWER_FIRST_NAME"]} hasn't completed their user profile yet. If {$row["BORROWER_FIRST_NAME"]} does not complete their user profile in the next 24 hours, this transaction will be cancelled.We've notified {$row["BORROWER_FIRST_NAME"]} about this.<br/><br/>";
        $message .= "Once {$row["BORROWER_FIRST_NAME"]} completes their user profile, we will notify you via email and move the transaction forward.";
        $subject = "{$row["TITLE"]} - PENDING - Borrower must complete profile - Transaction ID: {$row["TRANSACTION_ID"]}";
        sendEmail($do_not_reply_email, $row["LENDER_EMAIL_ADDRESS"], null, $subject, $message);
        
        // Send email to borrower
        $message = "Hi {$row["BORROWER_FIRST_NAME"]},<br/><br/>";
        $message .= "{$row["LENDER_FIRST_NAME"]} has accepted your request to rent item: <a href=\"{$domain}/item/index/{$row["ITEM_ID"]}\">{$row["TITLE"]}</a><br/><br/>";
        $message .= "Before the transaction can move forward, you must complete your user profile. You can do this by clicking on the link below:<br/><br/>";
        $message .= "<a href=\"{$domain}/user/extrasignup/null/0\">{$domain}/user/extrasignup/null/0</a><br/><br/>";
        $message .= "If you do not complete your user profile in the next 24 hours, this transaction will be cancelled.";
        $subject = "{$row["TITLE"]} - PENDING - Your action is needed - Transaction ID: {$row["TRANSACTION_ID"]}";
        sendEmail($do_not_reply_email, $row["BORROWER_EMAIL_ADDRESS"], null, $subject, $message);        
    }   
    
    // Note this is for the lender only
    public function pendingView($method, $user_id, $transaction_id)
    {
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;           
        $preparedStatement = $this->dbh->prepare('select * from PENDING_VW where TRANSACTION_ID=:transaction_id AND LENDER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);
        $rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        if ($rows == null)
            throw new InvalidPendingTransaction($method, $user_id, $transaction_id);
            
        return $this->denormalize($rows);        
    }
    
    // User here is a borrower. Move all pending requests to the reserved state
    public function movePendingToReserved($method, $user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;           
        $preparedStatement = $this->dbh->prepare('select TRANSACTION_ID from PENDING_VW where BORROWER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);
        $rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        $transactions = $this->denormalize($rows);
        
        if ($transactions != null)
        {
            // 250 -> 300
            foreach($transactions as $transaction)
            {
                $cc = getRandomID();
                $this->insertDetail($method, $transaction["TRANSACTION_ID"], $this->getEdgeID(250,300,$method, $user_id), array("CONFIRMATION_CODE" => $cc), $user_id);
                
                global $do_not_reply_email, $domain, $borrower_number, $support_email;

                $start_date = $transaction["REQ"]["START_DATE"];
                $end_date = $transaction["REQ"]["START_DATE"];

                // Send email to lender
                $message = "Hi {$transaction["LENDER_FIRST_NAME"]} and {$transaction["BORROWER_FIRST_NAME"]},<br/><br/>";
                $message .= "The transaction is ready to move forward. {$transaction["LENDER_FIRST_NAME"]}, you have accepted {$transaction["BORROWER_FIRST_NAME"]}'s request to rent your item, <a href=\"{$domain}/item/index/{$transaction["ITEM_ID"]}\">{$transaction["TITLE"]}</a>, from {$start_date} to {$end_date}. Your guys' confirmation code is <b>{$cc}</b>.<br/><br/>";
                $message .= "Here's what the both of you have to do next:<br/><br/>";
                $message .= "(1) Work out a place to meet over email (you can use this email chain since both of you are already on it).<br/>";
                $message .= "(2) {$transaction["BORROWER_FIRST_NAME"]}, when you meet {$transaction["LENDER_FIRST_NAME"]} on {$start_date}, text the above confirmation code to this number: <a href=\"tel:{$borrower_number}\">{$borrower_number}</a>.<br/>";
                $message .= "(3) {$transaction["LENDER_FIRST_NAME"]}, as soon we receive {$transaction["BORROWER_FIRST_NAME"]}'s text message, we will text you a message confirming so. Only hand over your item to {$transaction["BORROWER_FIRST_NAME"]} once you have received this message from us.<br/><br/>";
                $message .= "Questions? Hit us at <a href=\"mailto:{$support_email}\">{$support_email}</a>";

                $subject = "{$transaction["TITLE"]} - RESERVED - Item has been reserved - Transaction ID: {$transaction["TRANSACTION_ID"]}";
                sendEmail($do_not_reply_email, $transaction["LENDER_EMAIL_ADDRESS"] . ',' . $transaction["BORROWER_EMAIL_ADDRESS"], null, $subject, $message);                    
            }               
        }     
    }    
    
    private function denormalize($details)
    {
        $transactions = null;
        
        foreach ($details as $key=>$detail)
        {
            if (empty($transactions[$detail["TRANSACTION_ID"]]))
            {
                $transactions[$detail["TRANSACTION_ID"]] = $detail;
                
            }
            
            $transactions[$detail["TRANSACTION_ID"]]["HIST"][] = array("STATE_B_ID" => $detail["STATE_B_ID"],"SUMMARY" => $detail["SUMMARY"], "ENTRY_DATE" => $detail["ENTRY_DATE"]);
            $transactions[$detail["TRANSACTION_ID"]]["FINAL_STATE_NAME"] = $detail["STATE_NAME"];
            $transactions[$detail["TRANSACTION_ID"]]["FINAL_STATE_ID"] = $detail["STATE_B_ID"];
            
            // Request
            if ($detail["STATE_B_ID"] == 200)
            {
                $data = json_decode($detail['DATA']); 
                $transactions[$detail["TRANSACTION_ID"]]["REQ"]["START_DATE"] = $data->{"START_DATE"}; 
                $transactions[$detail["TRANSACTION_ID"]]["REQ"]["END_DATE"] = $data->{"END_DATE"};
                $transactions[$detail["TRANSACTION_ID"]]["REQ"]["MESSAGE"] = $data->{"MESSAGE"};                
                $transactions[$detail["TRANSACTION_ID"]]["REQ"]["RECEIVED_DATE"] = $detail["ENTRY_DATE"];
                
                $end = new DateTime($data->{"END_DATE"}); 
                $start = new DateTime($data->{"START_DATE"}); 
                $diff = $end->diff($start); 
                $transactions[$detail["TRANSACTION_ID"]]["REQ"]["TOTAL"] = $diff->d * $detail["RATE"];
            }
            
            else if ($detail["STATE_B_ID"] == 300)
            {
                $data = json_decode($detail['DATA']); 
                $transactions[$detail["TRANSACTION_ID"]]["RESERVATION"]["CONFIRMATION_CODE"] = $data->{"CONFIRMATION_CODE"};      
            }          
            
            else if ($detail["STATE_B_ID"] == 500)
            {
                $data = json_decode($detail['DATA']); 
                $transactions[$detail["TRANSACTION_ID"]]["EXCHANGED"]["BORROWER_BP_HOLD_URI"] = $data->{"BORROWER_BP_HOLD_URI"};
                $transactions[$detail["TRANSACTION_ID"]]["EXCHANGED"]["BORROWER_BP_BUYER_URI"] = $detail["BORROWER_BP_BUYER_URI"];     
                $transactions[$detail["TRANSACTION_ID"]]["EXCHANGED"]["LENDER_PAYPAL_EMAIL_ADDRESS"] = $detail["LENDER_PAYPAL_EMAIL_ADDRESS"];   
            }            
            
        }
        
        return $transactions;
    }
    
    // For exceptions, because these are text msg initiated, we're just going to make them and not throw them.
    // In the case of an error here:
    //  send text msg to borrower saying Error - <error msg> + transaction cancelled
    //  send text msg to lender saying Error - <error msg> + transaction cancelled
    //  send emails to both
    // move 
    public function borrowerConfirm($method, $confirmation_code, $phone_number) 
    {
        $sqlParameters[":phone_number"] =  $phone_number;
        $preparedStatement = $this->dbh->prepare('select * from USER_VW where PHONE_NUMBER=:phone_number LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $borrower = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        
        if ($borrower == null)
            throw new Invalid123(); // No user with that phone number
        
        $sqlParameters = array();
        $sqlParameters[":borrower_id"] =  $borrower["USER_ID"];
        $preparedStatement = $this->dbh->prepare('select * from RESERVED_VW where BORROWER_ID=:borrower_id');
        $preparedStatement->execute($sqlParameters);
        $rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        if ($rows == null)
            throw new Invalid234(null, null, null); // Reservation doesn't exist
        
        $transactions = $this->denormalize($rows);
        $transaction = null;
        
        foreach ($transactions as $t)
        {
            if ($t["RESERVATION"]["CONFIRMATION_CODE"] == $confirmation_code)
            {       
                $transaction = $t;
                break;
            }
        }
        
        if ($transaction == null)
            throw new Invalid3(null, null, null); // Reservation doesn't exist

        if ($borrower["BP_BUYER_URI"] == null || $borrower["BP_PRIMARY_CARD_URI"] == null)
            throw new Invalid4(null,null,null); // Borrower missing payment details
        
        global $bp_api_key;

        Balanced\Settings::$api_key = $bp_api_key;
        Httpful\Bootstrap::init();
        RESTful\Bootstrap::init();
        Balanced\Bootstrap::init();

        // Make the hold
        try
        {
            $account =  Balanced\Account::get($borrower["BP_BUYER_URI"]);
            $hold = $account->hold($transaction["DEPOSIT"] * 100, 'qhojo.com'); // cents
        }

        catch (Exception $e)
        {
            throw new Invalid5(); // Hold error
        }

        if ($hold->uri == null)
        {
            throw new Invalid6(); // Hold Error
        }
        
        $this->insertDetail($method, $transaction["TRANSACTION_ID"], $this->getEdgeID(300,500,$method, $borrower["USER_ID"]), array("BORROWER_BP_HOLD_URI" => $hold->uri), $borrower["USER_ID"]);            

        $message = "Hey " . $transaction["LENDER_FIRST_NAME"] . "! It's qhojo here. We have received " . $transaction["BORROWER_FIRST_NAME"] . "'s confirmation. You can go ahead and hand the item over. It is due back to you by " . date("m/d g:i A", strtotime($transaction["REQ"]["END_DATE"])) . ".";
        $message2 = "Thanks " . $transaction["BORROWER_FIRST_NAME"] . ". The rental duration has now started. We've placed a hold of \${$transaction["DEPOSIT"]} on your credit card. The item must be returned to " . $transaction["LENDER_FIRST_NAME"] . " by " . date("m/d g:i A", strtotime($transaction["REQ"]["END_DATE"])) . ".";

        global $lender_number;
        global $borrower_number;
        
        $this->sendText($transaction["LENDER_PHONE_NUMBER"], $lender_number, $message, $method, $borrower["USER_ID"]);
        $this->sendText($transaction["BORROWER_PHONE_NUMBER"], $borrower_number, $message2, $method, $borrower["USER_ID"]);
        
        // Send emails
        global $do_not_reply_email,$support_email;
        
        // Send email to both
        $message = "Hi {$transaction["LENDER_FIRST_NAME"]} and {$transaction["BORROWER_FIRST_NAME"]},<br/><br/>";
        $message .= "The rental period has started. We have received {$transaction["BORROWER_FIRST_NAME"]}'s confirmation that they are OK with the item. {$transaction["LENDER_FIRST_NAME"]}, if you haven't already, you can hand your item over to {$transaction["BORROWER_FIRST_NAME"]} to start the rental period.<br/><br/>";
        $message .= "{$transaction["BORROWER_FIRST_NAME"]}, we have placed a {$transaction["DEPOSIT"]} hold on your credit card. This hold will be released at the end of the rental period, " . date("m/d g:i A", strtotime($transaction["REQ"]["END_DATE"])) . ".<br/><br/>";
        $message .= "As a reminder, your guys' confirmation code is: {$transaction["RESERVATION"]["CONFIRMATION_CODE"]}<br/><br/>";
        $message .= "Here's what you guys need to do next:<br/><br/>";
        $message .= "(1) {$transaction["BORROWER_FIRST_NAME"]}, rock out with your rented gear until " . date("m/d g:i A", strtotime($transaction["REQ"]["END_DATE"])) . ".<br/>";
        $message .= "(2) On " . date("m/d g:i A", strtotime($transaction["REQ"]["END_DATE"])) . ", {$transaction["BORROWER_FIRST_NAME"]} go meet {$transaction["LENDER_FIRST_NAME"]} to return the item.<br/>";
        $message .= "(3) {$transaction["LENDER_FIRST_NAME"]}, at this meeting, verify that your returned item is OK. Once you have, text the above confirmation code to this number: <a href=\"tel:{$lender_number}\">{$lender_number}</a>.<br/>";
        $message .= "(4) {$transaction["BORROWER_FIRST_NAME"]}, you will receive a text message from us confirming that {$transaction["LENDER_FIRST_NAME"]} is OK with the returned item. Only return the item to {$transaction["LENDER_FIRST_NAME"]} once you have received this message from us.<br/><br/>";
        $message .= "Questions? Hit us at <a href=\"mailto:{$support_email}\">{$support_email}</a>";
        
        $subject = "{$transaction["TITLE"]} - EXCHANGED - Item has been exchanged - Transaction ID: {$transaction["TRANSACTION_ID"]}";
        
        sendEmail($do_not_reply_email, $transaction["LENDER_EMAIL_ADDRESS"] . ',' . $transaction["BORROWER_EMAIL_ADDRESS"], null, $subject, $message);        
    }    
    
    public function lenderConfirm($method, $confirmation_code, $phone_number) 
    {
        error_log("cc:" . $confirmation_code);
        error_log("ph:" . $phone_number);

        $sqlParameters[":phone_number"] =  $phone_number;
        $preparedStatement = $this->dbh->prepare('select * from USER_VW where PHONE_NUMBER=:phone_number LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $lender = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        
        if ($lender == null)
            throw new Invalid123();
        
        $sqlParameters = array();
        $sqlParameters[":lender_id"] =  $lender["USER_ID"];
        $preparedStatement = $this->dbh->prepare('select * from EXCHANGED_VW where LENDER_ID=:lender_id');
        $preparedStatement->execute($sqlParameters);
        $rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        if ($rows == null)
            throw new Invalid234(null, null, null);
        
        $transactions = $this->denormalize($rows);
        $transaction = null;
        
        foreach ($transactions as $t)
        {
            if ($t["RESERVATION"]["CONFIRMATION_CODE"] == $confirmation_code)
            {       
                $transaction = $t;
                break;
            }
        }
        
        if ($transaction == null)
            throw new Invalid3(null, null, null);
        
        global $bp_api_key;

        Balanced\Settings::$api_key = $bp_api_key;
        Httpful\Bootstrap::init();
        RESTful\Bootstrap::init();
        Balanced\Bootstrap::init();

        // Void the hold
        try
        {
            $hold = Balanced\Hold::get($transaction["EXCHANGED"]["BORROWER_BP_HOLD_URI"]);
            $hold->void();
        }

        catch (Exception $e)
        {
            throw new Invalid5();
        }

        // Charge the borrower
        
        if ($transaction["EXCHANGED"]["BORROWER_BP_HOLD_URI"] == null)
            throw new Invalid6();
        
        global $transaction_fee_variable;
        global $transaction_fee_fixed;
        $total_without_fee = $transaction["REQ"]["TOTAL"];
        $total_with_fee = $total_without_fee*(1-$transaction_fee_variable)-$transaction_fee_fixed;

        try
        {
            // Debit the borrower
            $account = Balanced\Account::get($transaction['BORROWER_BP_BUYER_URI']);
            $account->debit($total_without_fee * 100);                    // cents
        }

        catch (Exception $e)
        {
        }
        
        // Pay the lender
        $status = $this->paypalMassPayToLender($transaction["EXCHANGED"]['LENDER_PAYPAL_EMAIL_ADDRESS'],$total_with_fee);

        if ($status != 0)
        {
            error_log($status);
        }

        $this->insertDetail($method, $transaction["TRANSACTION_ID"], $this->getEdgeID(500,700,$method, $transaction["LENDER_ID"]), null,  $transaction["LENDER_ID"]);            
        
        $message = "Hey " . $transaction["BORROWER_FIRST_NAME"] . "! It's qhojo here. We have received " . $transaction["LENDER_FIRST_NAME"] . "'s confirmation. Go ahead and return the item. The hold of \${$transaction["DEPOSIT"]} on your credit card has been released.";
        global $borrower_number;
        $this->sendText($transaction["BORROWER_PHONE_NUMBER"], $borrower_number, $message, $method, $transaction["LENDER_ID"]);      
        
      // Send emails
        global $do_not_reply_email,$support_email, $domain;
        
        // Send email to both
        $message = "Hi {$transaction["LENDER_FIRST_NAME"]} and {$transaction["BORROWER_FIRST_NAME"]},<br/><br/>";
        $message .= "The transaction is now complete. We have recezived {$transaction["LENDER_FIRST_NAME"]}'s confirmation that they are OK with the returned item. {$transaction["BORROWER_FIRST_NAME"]}, if you haven't already, you can return the item to {$transaction["LENDER_FIRST_NAME"]}.<br/><br/>";
        $message .= "{$transaction["BORROWER_FIRST_NAME"]}, we have released the \${$transaction["DEPOSIT"]} hold on your credit card and charged you \${$total_without_fee}<br/><br/>";
        $message .= "{$transaction["LENDER_FIRST_NAME"]}, we have deposited \${$total_with_fee} into your PayPal account.<br/><br/>";
        $message .= "Please review how the transaction went. Leave feedback by following the link below:<br/><br/>";
        $message .= "<a href=\"{$domain}/transaction/review/{$transaction["TRANSACTION_ID"]}/0\">{$domain}/transaction/review/{$transaction["TRANSACTION_ID"]}/0</a><br/><br/>";
        $message .= "Questions? Hit us at <a href=\"mailto:{$support_email}\">{$support_email}</a>";
        
        $subject = "{$transaction["TITLE"]} - RETURNED - Item has been returned - Transaction ID: {$transaction["TRANSACTION_ID"]}";
        
        sendEmail($do_not_reply_email, $transaction["LENDER_EMAIL_ADDRESS"] . ',' . $transaction["BORROWER_EMAIL_ADDRESS"], null, $subject, $message);        
    }    
    
    public function insertDetail($method, $transaction_id, $edge_id, $data, $user_id)
    {
        $sqlParameters[":edge_id"] =  $edge_id;
        $sqlParameters[":entry_date"] =  date("Y-m-d H:i:s");;
        $sqlParameters[":json_data"] =  json_encode($data);        
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;    
        
        $preparedStatement = $this->dbh->prepare('INSERT INTO DETAIL VALUES (:transaction_id, :edge_id, :entry_date, :json_data, :user_id)');
        try { $preparedStatement->execute($sqlParameters); } 
        catch (PDOException $e) { throw new DatabaseException($e->getMessage(), $method, $user_id, $e); }          
    }
    
    public function getEdgeID($state_a_id, $state_b_id,$method, $user_id)
    {
        $sqlParameters[":state_a_id"] =  $state_a_id;
        $sqlParameters[":state_b_id"] =  $state_b_id;        
        
        $preparedStatement = $this->dbh->prepare('select ID from EDGE where STATE_A_ID=:state_a_id and STATE_B_ID=:state_b_id AND ACTIVE=1 LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);        
        
        if ($row == null)
            throw InvalidEdgeException($method, $user_id, $state_a_id, $state_b_id);
        
        return $row['ID'];
    }
    
    private function paypalMassPayToLender($paypal_email, $amount)
    {
        $recvType = 'EmailAddress';							
        $currencyID = urlencode('USD');

        // Add request-specific fields to the request string.
        $nvpStr = "&RECEIVERTYPE=$recvType&L_EMAIL0=$paypal_email&L_AMT0=$amount&CURRENCYCODE=$currencyID";

        // Execute the API operation; see the PPHttpPost function above.
        $httpParsedResponseAr = $this->PPHttpPost('MassPay', $nvpStr);

        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
        {
            return 0;
        } 

        else  
        {
            error_log('MassPay failed: ' . print_r($httpParsedResponseAr, true));
            return -1;
        }
    }    
}

?>
