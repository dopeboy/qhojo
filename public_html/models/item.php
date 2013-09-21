<?php

require "lib/twilio/Twilio.php";
//require 'lib/rest-api-sdk-php/sample/bootstrap.php';
//use PayPal\Api\Address;
//use PayPal\Api\Amount;
//use PayPal\Api\AmountDetails;
//use PayPal\Api\CreditCard;
//use PayPal\Api\Payer;
//use PayPal\Api\Payment;
//use PayPal\Api\FundingInstrument;
//use PayPal\Api\Transaction;
//use PayPal\Rest\ApiContext;
//use PayPal\Auth\OAuthTokenCredential;

class ItemModel extends Model 
{
    public function getThreeLatestItems($method)
    {
        $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW ORDER BY CREATE_DATE DESC LIMIT 3');
        $preparedStatement->execute();
        $rows["ITEMS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);   
        return $rows;
    }
    
    // For search, you can:
    // do query
    // do location
    // do query + location
    // do user_id
    // do nothing (returns all items)
    public function search($method, $query, $location, $user_id, $page)
    {
        $where = '';
        $query_clause = '';
        $location_clause = '';
        $user_clause = '';
        $sqlParameters = null;
        
        if ($query != null || $location != null || $user_id != null)
            $where = ' WHERE ';
        
        if ($query != null || $location != null)
        {
            if ($query != null)
            {
                $sqlParameters[":query"] =  '%' . $query . '%';
                $query_clause = ' (lower(TITLE) like lower(:query) OR lower(DESCRIPTION) like lower(:query))';
            }

            if ($location != null)
            {
                $sqlParameters[":location"] =  $location;

                if ($query != null)
                    $location_clause = ' AND ';

                if (strlen($location) == 5 && is_numeric($location))
                    $location_clause .= ' (ZIPCODE=:location)';
                else
                {
                    $sqlParameters[":location"] = '%' . $sqlParameters[":location"] . '%';    
                    $location_clause .= ' (lower(CITY) like lower(:location))';
                }
            }
        }
        
        else if ($user_id != null)
        {
            $sqlParameters[":user_id"] =  $user_id;
            $user_clause = ' LENDER_ID = :user_id';
            
            $sqlParametersUser[":user_id"] =  $user_id;
            $preparedStatement = $this->dbh->prepare('SELECT FIRST_NAME FROM USER_VW where USER_ID=:user_id LIMIT 1');
            $preparedStatement->execute($sqlParametersUser);
            $rows["USER_FIRST_NAME"] = $preparedStatement->fetchColumn();                
        }

        global $results_per_page;
        if ($page == null)
            $start_at = 0;
        else
            $start_at = ($page-1) * $results_per_page;
        
        // First find the total number of results. 
        $preparedStatement = $this->dbh->prepare('SELECT COUNT(*) FROM ITEM_VW' . $where . $query_clause . $location_clause . $user_clause);
        $preparedStatement->execute($sqlParameters);
        $rows["ITEMS_COUNT"] = $preparedStatement->fetchColumn();           
        
        if ($rows["ITEMS_COUNT"] != 0)
        {
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW ' . $where . $query_clause . $location_clause . $user_clause . ' ORDER BY CREATE_DATE DESC LIMIT ' . $start_at . ',' . $results_per_page);
            $preparedStatement->execute($sqlParameters);
            $rows["ITEMS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);   
        }
        
        return $rows;
    }
    
    public function index($method, $user_id, $item_id)
    {
        $sqlParameters[":item_id"] =  $item_id;
        $row['ITEM'] = $this->getItem($item_id);;

        $preparedStatement = $this->dbh->prepare('SELECT FILENAME FROM ITEM_PICTURE where ITEM_ID=:item_id');
        $preparedStatement->execute($sqlParameters);
        $row['ITEM_PICTURES'] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        $sqlParameters[":lender_id"] =  $row['ITEM']["LENDER_ID"];
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM REVIEW_VW where ITEM_ID=:item_id AND REVIEWEE_ID=:lender_id');
        $preparedStatement->execute($sqlParameters);
        $row['ITEM_REVIEWS'] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        // Has user already requested item?
        $sqlParameters = array();
        $sqlParameters[":item_id"] =  $item_id;
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT 1 FROM REQUESTED_VW where ITEM_ID=:item_id AND BORROWER_ID=:user_id LIMIT 1 UNION SELECT 1 FROM PENDING_VW where ITEM_ID=:item_id and BORROWER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row['ALREADY_REQUESTED']= ($preparedStatement->fetch(PDO::FETCH_ASSOC)  != null);
        
        return $row;
    }
    
    public function request($method, $user_id, $item_id)
    {
        // Has user already requested item?
        $sqlParameters[":item_id"] =  $item_id;
        $sqlParameters[":borrower_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT 1 FROM REQUESTED_VW where ITEM_ID=:item_id AND BORROWER_ID=:borrower_id LIMIT 1 UNION SELECT 1 FROM PENDING_VW where ITEM_ID=:item_id and BORROWER_ID=:borrower_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        
        if ($row != null)
            throw new UserHasAlreadyRequestedItemException($method, $user_id);
            
        // Is the item active?
        $row['ITEM'] =  $this->getItem($item_id);
        
        if ($row['ITEM'] == null)
            throw new RequestInactiveItemException($method, $user_id);
        
        $user_model = new UserModel();
        $row["USER"]["NEED_EXTRA_FIELDS"] = $user_model->userNeedsExtraFields($user_id);
        
        return $row;
    }  
    
    // 100 -> 200
    public function submitRequest($method, $item_id, $requestor_id, $start_date, $end_date, $message)
    {
        // Call this for validation purposes
        $this->request($method, $requestor_id, $item_id);
        
        $start = new DateTime($start_date); 
        $end = new DateTime($end_date); 
        $end->setTime(23, 59);
        
        $formatted_end_date = $end->format('Y-m-d H:i:s');
        
        global $maximum_rental_duration_days;
        if ($end->diff($start)->d > $maximum_rental_duration_days)
            throw new RentalDurationExceedsLimitException($maximum_rental_duration_days, $method, $requestor_id);
        
        $transaction_model = new TransactionModel();
        $transaction_id = $transaction_model->createTransaction($method, $item_id, $requestor_id);
        $transaction_model->insertDetail($method, $transaction_id, $transaction_model->getEdgeID(100,200, $method, $requestor_id), array("START_DATE" => $start_date, "END_DATE" => $formatted_end_date, "MESSAGE" => $message), $requestor_id);
        
        // Get user info for both lender and borrower
        $user_model = new \UserModel();
        $requestor = $user_model->getUserDetails($requestor_id);
        
        $sqlParameters[":item_id"] =  $item_id;
        $preparedStatement = $this->dbh->prepare('SELECT LENDER_ID, TITLE FROM ITEM_VW where ITEM_ID=:item_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $item = $preparedStatement->fetch(PDO::FETCH_ASSOC);        
        
        $lender = $user_model->getUserDetails($item["LENDER_ID"]);
        
        global $do_not_reply_email, $domain;
        
        // Send email to lender
        $email = "Hi {$lender["FIRST_NAME"]},<br/><br/>";
        $email .= "{$requestor["FIRST_NAME"]} has requested to rent your item, {$item["TITLE"]}, from " . date("m/d g:i A", strtotime($start_date)) . " to " . date("m/d g:i A", strtotime($formatted_end_date)) . ".<br/><br/>";
        $email .= "Here's the message {$requestor["FIRST_NAME"]} attached to the request:<br/><br/>";
        $email .= "<blockquote>{$message}</blockquote><br/>";
        $email .= "If you want to accept {$requestor["FIRST_NAME"]}'s request, click on the link below: <br/><br/>";
        $email .= "<a href=\"{$domain}/transaction/accept/{$transaction_id}/0\">{$domain}/transaction/accept/{$transaction_id}/0</a><br/><br/>";
        $email .= "Alternatively, go to your <a href=\"{$domain}/user/dashboard/\">dashboard</a> to see all your requests.";

        $subject = "{$item["TITLE"]} - REQUESTED - Item has been requested - Transaction ID: {$transaction_id}";
        
        sendEmail($do_not_reply_email, $lender["EMAIL_ADDRESS"], null, $subject, $email);            
    }   
    
    public function requestSubmitted($method, $item_id, $user_id)
    {   
        $user_model = new UserModel();
        $row["USER"]["NEED_EXTRA_FIELDS"] = $user_model->userNeedsExtraFields($user_id);
        
        $row['ITEM'] =  $this->getItem($item_id);     
        return $row;
    }
    
    public function post($method, $user_id)
    {   
        $user_model = new UserModel(); 
        $row["USER"] = $user_model->getUserDetails($user_id);
        $row["ITEM"]["ITEM_ID"] = getRandomID();
        
        $user_model = new UserModel();
        $row["USER"]["NEED_EXTRA_FIELDS"] = $user_model->userNeedsExtraFields($user_id);
        
        return $row;
    }    
    
    public function submitPost($method, $user_id, $item_id, $title, $description, $hold, $rate, $zipcode, $files)
    {
        $location = $this->reverseGeocode($zipcode, $method);

        $sqlParameters[":item_id"] =  $item_id;
        $sqlParameters[":title"] =  $title;
        $sqlParameters[":description"] =  $description;
        $sqlParameters[":rate"] =  $rate;
        $sqlParameters[":deposit"] =  $hold;
        $sqlParameters[":zipcode"] =  $zipcode;
        $sqlParameters[":city"] =  $location['CITY'];
        $sqlParameters[":state"] =  $location['STATE'];
        $sqlParameters[":lender_id"] =  $user_id;
        $sqlParameters[":active"] =  1;
        $sqlParameters[":create_date"] =  date("Y-m-d H:i:s");                    
                    
        $preparedStatement = $this->dbh->prepare('insert into ITEM (ID, TITLE,DESCRIPTION,RATE,DEPOSIT,ZIPCODE,CITY,STATE,LENDER_ID, ACTIVE, CREATE_DATE) VALUES (:item_id,:title,:description,:rate,:deposit,:zipcode,:city,:state,:lender_id, :active, :create_date)');
        $preparedStatement->execute($sqlParameters);
        
        // Handle the files
        $sqlParameters[] = array();
        $datafields = array('ITEM_ID' => '', 'FILENAME' => '', 'PRIMARY_FLAG' => '');

        foreach ($files as $key=>$file)
        {
            $data[] = array('ITEM_ID' => $item_id, 'FILENAME' => $file, 'PRIMARY_FLAG' => $key == 0 ? 1 : 0);
        }

        $insert_values = array();
        foreach($data as $d)
        {
            $question_marks[] = '('  . $this->placeholders('?', sizeof($d)) . ')';
            $insert_values = array_merge($insert_values, array_values($d));
        }

        $sql = "INSERT INTO ITEM_PICTURE (" . implode(",", array_keys($datafields) ) . ") VALUES " . implode(',', $question_marks);

        $this->dbh->beginTransaction();
        $stmt = $this->dbh->prepare ($sql);
        $stmt->execute($insert_values);
        $this->dbh->commit();        
    }
    
    public function postSubmitted($method, $item_id, $user_id)
    {
        $row["ITEM"] = $this->getItem($item_id);
        
        if ($row["ITEM"] == null)
            throw new ItemSubmissionIssueException($method,$user_id);
        
        $user_model = new UserModel();
        $row["USER"]["NEED_EXTRA_FIELDS"] = $user_model->userNeedsExtraFields($user_id);        
        
        return $row;
    }
    
    public function canUserModifyItem($item_id, $user_id)
    {
        // If the item doesn't exist, then yes.
        $sqlParameters[":item_id"] =  $item_id;
        $preparedStatement = $this->dbh->prepare('SELECT LENDER_ID FROM ITEM_VW where ITEM_ID=:item_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);        
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        if ($row == null)
            return true;
        
        // If it does exist, are we the lender on it?
        return $row["LENDER_ID"] == $user_id;
    }
    
    private function getItem($item_id)
    {
        $sqlParameters[":item_id"] =  $item_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW where ITEM_ID=:item_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        
        return $preparedStatement->fetch(PDO::FETCH_ASSOC);        
    }
    
    private function placeholders($text, $count=0, $separator=",")
    {
        $result = array();
        if($count > 0){
            for($x=0; $x<$count; $x++){
                $result[] = $text;
            }
        }

        return implode($separator, $result);
    }    
}

?>