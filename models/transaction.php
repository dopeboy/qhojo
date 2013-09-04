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
                $this->insertDetail($method, $transaction_id, $this->getEdgeID(700,900), array("COMMENT" => $comments, "RATING" => $rating), $user_id);              
            
            // 1100 -> 1200
            else if ($transaction["FINAL_STATE_ID"] == 1100)
                $this->insertDetail($method, $transaction_id, $this->getEdgeID(1100,1200), array("COMMENT" => $comments, "RATING" => $rating), $user_id);                 
        }
        
        // If the user is the borrower of the transaction
        else if ($row["LENDER_FLAG"] == 0)
        {
            $preparedStatement = $this->dbh->prepare('select * from RETURNED_AND_NEED_BORROWER_REVIEW_VW where TRANSACTION_ID=:transaction_id AND BORROWER_ID=:user_id');
            $preparedStatement->execute($sqlParameters);
            $transaction = reset($this->denormalize($preparedStatement->fetchAll(PDO::FETCH_ASSOC)));
            
            // 700 -> 1100
            if ($transaction["FINAL_STATE_ID"] == 700)
                $this->insertDetail($method, $transaction_id, $this->getEdgeID(700,1100), array("COMMENT" => $comments, "RATING" => $rating), $user_id);            
            
            // 900 -> 1000
            else if ($transaction["FINAL_STATE_ID"] == 900)
                $this->insertDetail($method, $transaction_id, $this->getEdgeID(900,1000), array("COMMENT" => $comments, "RATING" => $rating), $user_id);          
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
            $this->insertDetail($method, $transaction_id, $this->getEdgeID(200,401), array("REJECT_ID" => $reject_option, "REASON" => $reject_reason), $user_id);
        // 200 -> 400
        else if ($source == 1)
            $this->insertDetail($method, $transaction_id, $this->getEdgeID(200,400), array("REJECT_ID" => $reject_option, "REASON" => $reject_reason), $user_id);
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
            $this->insertDetail($method, $transaction_id, $this->getEdgeID(300,601), array("CANCEL_ID" => $cancel_option, "REASON" => $cancel_reason), $user_id);      
        // 300 -> 600
        else if ($source == 1)
            $this->insertDetail($method, $transaction_id, $this->getEdgeID(300,600), array("CANCEL_ID" => $cancel_option, "REASON" => $cancel_reason), $user_id); 
        else
             throw new CancelRequestException($method, $user_id, null, $transaction_id);          
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
        }
        
        return $transactions;
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
    
    public function getEdgeID($state_a_id, $state_b_id)
    {
        $sqlParameters[":state_a_id"] =  $state_a_id;
        $sqlParameters[":state_b_id"] =  $state_b_id;        
        
        $preparedStatement = $this->dbh->prepare('select ID from EDGE where STATE_A_ID=:state_a_id and STATE_B_ID=:state_b_id AND ACTIVE=1 LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);        
        
        return $row['ID'];
    }
}

?>
