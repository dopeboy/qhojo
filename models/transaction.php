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

        $preparedStatement = $this->dbh->prepare('select 1 from REQUESTED_VW where TRANSACTION_ID=:transaction_id AND LENDER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);            
        
        if ($row == null)
            throw new AcceptRequestException($method, $user_id);    
        
        // 200 -> 300
        $this->insertDetail($method, $transaction_id, $this->getEdgeID(200,300,$method, $user_id), array("CONFIRMATION_CODE" => getRandomID()), $user_id);          
    }
    
    public function pending($method, $user_id, $transaction_id)
    {
        // Check if user is the lender on the transaction
        $sqlParameters[":transaction_id"] =  $transaction_id;
        $sqlParameters[":user_id"] =  $user_id;            

        $preparedStatement = $this->dbh->prepare('select 1 from REQUESTED_VW where TRANSACTION_ID=:transaction_id AND LENDER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);            
        
        if ($row == null)
            throw new PendingTransactionException($method, $user_id);    
        
        // 200 -> 250
        $this->insertDetail($method, $transaction_id, $this->getEdgeID(200,250,$method, $user_id), null, $user_id);  
        
        // TODO: Send email to lender and borrower
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
                $this->insertDetail($method, $transaction["TRANSACTION_ID"], $this->getEdgeID(250,300,$method, $user_id), array("CONFIRMATION_CODE" => getRandomID()), $user_id);          
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
                $transactions[$detail["TRANSACTION_ID"]]["ITEM_ID"] = $detail["ITEM_ID"];
                $transactions[$detail["TRANSACTION_ID"]]["DEPOSIT"] = $detail["DEPOSIT"];
                $transactions[$detail["TRANSACTION_ID"]]["LENDER_ID"] = $detail["LENDER_ID"];
                $transactions[$detail["TRANSACTION_ID"]]["LENDER_FIRST_NAME"] = $detail["LENDER_FIRST_NAME"];
                $transactions[$detail["TRANSACTION_ID"]]["LENDER_PHONE_NUMBER"] = $detail["LENDER_PHONE_NUMBER"];
                $transactions[$detail["TRANSACTION_ID"]]["BORROWER_ID"] = $detail["BORROWER_ID"];
                $transactions[$detail["TRANSACTION_ID"]]["BORROWER_FIRST_NAME"] = $detail["BORROWER_FIRST_NAME"];
                $transactions[$detail["TRANSACTION_ID"]]["BORROWER_PHONE_NUMBER"] = $detail["BORROWER_PHONE_NUMBER"];                
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
    
    public function borrowerConfirm($method, $confirmation_code, $phone_number) 
    {
        error_log("cc:" . $confirmation_code);
        error_log("ph:" . $phone_number);

        $sqlParameters[":phone_number"] =  '+' . $phone_number;
        $preparedStatement = $this->dbh->prepare('select * from USER_VW where PHONE_NUMBER=:phone_number LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $borrower = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        
        if ($borrower == null)
            throw new Invalid123();
        
        $sqlParameters = array();
        $sqlParameters[":borrower_id"] =  $borrower["USER_ID"];
        $preparedStatement = $this->dbh->prepare('select * from RESERVED_VW where BORROWER_ID=:borrower_id');
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

        if ($borrower["BP_BUYER_URI"] == null || $borrower["BP_PRIMARY_CARD_URI"] == null)
            throw new Invalid4(null,null,null);
        
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
            throw new Invalid5();
        }

        if ($hold->uri == null)
        {
            throw new Invalid6();
        }
        
        $this->insertDetail($method, $transaction["TRANSACTION_ID"], $this->getEdgeID(300,500,$method, $borrower["USER_ID"]), array("BORROWER_BP_HOLD_URI" => $hold->uri), $borrower["USER_ID"]);            

        $message = "Hey " . $transaction["LENDER_FIRST_NAME"] . "! It's qhojo here. We have received " . $transaction["BORROWER_FIRST_NAME"] . "'s confirmation. You can go ahead and hand the item over. It is due back to you by " . date("m/d g:i A", strtotime($transaction["REQ"]["END_DATE"])) . ".";
        $message2 = "When " . $transaction["BORROWER_FIRST_NAME"] . " comes back to return the item, verify it and then text this confirmation code back to us: " . $confirmation_code;
        $message3 = "Thanks " . $transaction["BORROWER_FIRST_NAME"] . ". The rental duration has now started. We've placed a hold of \${$transaction["DEPOSIT"]} on your credit card. The item must be returned to " . $transaction["LENDER_FIRST_NAME"] . " by " . date("m/d g:i A", strtotime($transaction["REQ"]["END_DATE"])) . ".";

        global $lender_number;
        global $borrower_number;
        
        $this->sendText($transaction["LENDER_PHONE_NUMBER"], $lender_number, $message, $method, $borrower["USER_ID"]);
        $this->sendText($transaction["LENDER_PHONE_NUMBER"], $lender_number, $message2, $method, $borrower["USER_ID"]);
        $this->sendText($transaction["BORROWER_PHONE_NUMBER"], $borrower_number, $message3, $method, $borrower["USER_ID"]);
    }    
    
    public function lenderConfirm($method, $confirmation_code, $phone_number) 
    {
        error_log("cc:" . $confirmation_code);
        error_log("ph:" . $phone_number);

        $sqlParameters[":phone_number"] =  '+' . $phone_number;
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
