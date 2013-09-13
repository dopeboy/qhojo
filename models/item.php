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
        $preparedStatement = $this->dbh->prepare('SELECT 1 FROM REQUESTED_VW where ITEM_ID=:item_id AND BORROWER_ID=:user_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row['ALREADY_REQUESTED']= ($preparedStatement->fetch(PDO::FETCH_ASSOC)  != null);
        
        return $row;
    }
    
    public function request($method, $user_id, $item_id)
    {
        // Has user already requested item?
        $sqlParameters[":item_id"] =  $item_id;
        $sqlParameters[":borrower_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT 1 FROM REQUESTED_VW where ITEM_ID=:item_id AND BORROWER_ID=:borrower_id LIMIT 1');
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
        
        global $maximum_rental_duration_days;
        if ($end->diff($start)->d > $maximum_rental_duration_days)
            throw new RentalDurationExceedsLimitException($maximum_rental_duration_days, $method, $requestor_id);
        
        $transaction_model = new TransactionModel();
        $transaction_id = $transaction_model->createTransaction($method, $item_id, $requestor_id);
        $transaction_model->insertDetail($method, $transaction_id, $transaction_model->getEdgeID(100,200, $method, $requestor_id), array("START_DATE" => $start_date, "END_DATE" => $end_date, "MESSAGE" => $message), $requestor_id);
    }   
    
    public function requestSubmitted($method, $item_id)
    {   
        $row['ITEM'] =  $this->getItem($item_id);     
        return $row['ITEM'];
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
	/*public function index($itemid, $userid) 
	{
            $sqlParameters[":itemid"] =  $itemid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW where ITEM_ID=:itemid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row[] = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            $preparedStatement = $this->dbh->prepare('SELECT FILENAME FROM ITEM_PICTURES where ITEM_ID=:itemid');
            $preparedStatement->execute($sqlParameters);
            $row[] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

            $usermodel = new UserModel();                
            $row[] = $usermodel->getFeedbackAsLender($row[0]['LENDER_ID']);

            $row[] = $usermodel->checkIfUserAlreadyRequested($userid, $itemid);

            $row[] = $usermodel->getNetworksForUser($row[0]['LENDER_ID']);

            $tag_model = new TagModel();
            $row[] = $tag_model->getTagsForItem($itemid);

            return $row;
	}     
        
	public function test() 
	{            
            //$status = $this->paypalMassPayToLender('arithmetic@gmail.com',1);

            //if ($status != 0)
          //  {
                // error_log("Error with sending {$total_with_fee} to {$item_row['LENDER_PAYPAL_EMAIL_ADDRESS']}");
             //   error_log("lenderConfirm FML 3");
             //   return 3;
           // }
            
        }
        
        public function testest()
        {
            Balanced\Settings::$api_key = "3ec0da1cb80e11e2bb37026ba7d31e6f";
            
            Httpful\Bootstrap::init();
            RESTful\Bootstrap::init();
            Balanced\Bootstrap::init();
  
            
            $card = Balanced\Card::get("/v1/marketplaces/TEST-MP1UEXukTLr6ID7auHkkCHd6/cards/CC4IeFuTtDWL9MG7iu3DMTN6");

            return print_r($card,true);          
  
//$card->items[0]->is_valid = false;
//$card->items[0]->save();

//            $marketplace = \Balanced\Marketplace::mine();
//
//            $account = Balanced\Account::get('/v1/marketplaces/TEST-MP1UEXukTLr6ID7auHkkCHd6/accounts/AC7cwcHRdank5o2CCyVPHXXU');
//            
//            $card = Balanced\Card::get($account->cards->uri);
//            
//            //$card->
//            foreach ($card->items as $card)
//            {
//                if ($card->is_valid)
//                {
//                    $blah .= print_r($card->last_four,true);
//                }
//            }
//            
//            return $blah;
            //return print_r($card->items);
            
           // $status = $this->paypalMassPayToLender('bob@qhojo.com',23);
            // CREATE THE BUYER using the CARD URI
           // $buyer = Balanced\Marketplace::mine()->createBuyer('tdes223322f2dds22t2@test.com',$card_uri);
//error_log($buyer->uri);
            // *****************save $buyer->uri in db

            // MAKE THE HOLD
            //$account =  Balanced\Account::get('/v1/marketplaces/TEST-MP1UEXukTLr6ID7auHkkCHd6/accounts/AC1nlg6iqSR8gq76vPYwnNx6');
            //$hold = $account->hold('46', 'GOOD BURGER PALO ALTO');
            //error_log($hold->uri);
            // *****************save $hold->uri to the db
                        
            // VOID THE HOLD
            //$hold = Balanced\Hold::get("/v1/marketplaces/TEST-MP1UEXukTLr6ID7auHkkCHd6/holds/HL7dH6pFLs33AZN3w70c3fQP");
            //$hold->void();

            // DEBIT (rr x rd) + commission
            //$account = Balanced\Account::get($buyer->uri);
            //$account->debit('1234');    
            
            // CREDIT (rr x rd) via Paypal using MassPay. 
            
            return 2;
        }
        
        public function search($query, $borough, $neighborhood, $tag)
        {
            $preparedStatement = $this->dbh->prepare('select * from BOROUGH');
            $preparedStatement->execute();
            $rows["BOROUGHS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
            
            $preparedStatement = $this->dbh->prepare('select * from NEIGHBORHOOD ORDER BY BOROUGH_ID, FULL_NAME');
            $preparedStatement->execute();
            $rows["NEIGHBORHOODS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);   
            
            $tag_model = new TagModel();
            $rows["TAGS"] = $tag_model->getAllTags();
            
            // If they're all empty, return all items
            if ($query == null && $borough == null & $neighborhood == null && $tag == null)
            {
                $preparedStatement = $this->dbh->prepare('select * from ITEM_VW where ITEM_STATE_ID=0');
                $preparedStatement->execute();
                $rows["ITEMS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);                
            }
            
            else if ($borough != null)
            {
                if ($tag != null)
                {
                    $query = "select a.* from ITEM_VW a, ITEM_TAG b where a.BOROUGH_ID=:borough_id and a.ITEM_STATE_ID=0 and a.ITEM_ID=b.ITEM_ID  and b.TAG_ID=:tag_id";
                    $sqlParameters[":tag_id"] =  $tag;
                }
                
                else
                    $query = "select a.* from ITEM_VW a where a.BOROUGH_ID=:borough_id and a.ITEM_STATE_ID=0";
                
                $sqlParameters[":borough_id"] =  $borough;
                $preparedStatement = $this->dbh->prepare($query);
                $preparedStatement->execute($sqlParameters);
                $rows["ITEMS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);                  
            }
            
            else if ($neighborhood != null)
            {
                if ($tag != null)
                {
                    $query = "select a.* from ITEM_VW a, ITEM_TAG b where a.LOCATION_ID=:location_id and a.ITEM_STATE_ID=0 and a.ITEM_ID=b.ITEM_ID and b.TAG_ID=:tag_id";
                    $sqlParameters[":tag_id"] =  $tag;
                }
                
                else
                    $query = "select a.* from ITEM_VW a where a.LOCATION_ID=:location_id and a.ITEM_STATE_ID=0";
                
                $sqlParameters[":location_id"] =  $neighborhood;
                $preparedStatement = $this->dbh->prepare($query);
                $preparedStatement->execute($sqlParameters);
                $rows["ITEMS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);  
            }
            
            else if ($tag != null)
            {
                $sqlParameters[":tag"] =  $tag;
                $preparedStatement = $this->dbh->prepare('select a.* from ITEM_VW a, ITEM_TAG b where a.ITEM_STATE_ID=0 and a.ITEM_ID=b.ITEM_ID and b.TAG_ID=:tag');
                $preparedStatement->execute($sqlParameters);
                $rows["ITEMS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);                  
            }
            
            else if ($query != null)
            {
		$sqlParameters[":search"] =  '%'. $query .'%';
                $preparedStatement = $this->dbh->prepare('select * from ITEM_VW where ITEM_STATE_ID=0 and (lower(TITLE) like lower(:search) OR lower(DESCRIPTION) like lower(:search))');
		$preparedStatement->execute($sqlParameters);
		$rows["ITEMS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);              
            }
            
            return $rows;
        }
        
//        public function searchByLocation($location_id)
//        {
//            $preparedStatement = $this->dbh->prepare('select * from BOROUGH');
//            $preparedStatement->execute();
//            $rows["BOROUGHS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
//            
//            $preparedStatement = $this->dbh->prepare('select * from NEIGHBORHOOD');
//            $preparedStatement->execute();
//            $rows["NEIGHBORHOODS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);   
//            
//            $sqlParameters[":location_id"] =  $location_id;
//            $preparedStatement = $this->dbh->prepare('select * from LOCATION_VW where ID=:location_id LIMIT 1');
//            $preparedStatement->execute($sqlParameters);
//            $rows["LOCATION"] = $preparedStatement->fetch(PDO::FETCH_ASSOC);
//            
//            $preparedStatement = $this->dbh->prepare('select * from ITEM_VW where LOCATION_ID=:location_id and ITEM_STATE_ID=0');
//            $preparedStatement->execute($sqlParameters);
//            $rows["ITEMS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
//            
//            $tag_model = new TagModel();
//            $rows["TAGS"] = $tag_model->getAllTags();
//
//            return $rows;
//        }
//        
//        public function searchByBorough($borough_id)
//        {
//            $preparedStatement = $this->dbh->prepare('select * from BOROUGH');
//            $preparedStatement->execute();
//            $rows["BOROUGHS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
//            
//            $preparedStatement = $this->dbh->prepare('select * from NEIGHBORHOOD');
//            $preparedStatement->execute();
//            $rows["NEIGHBORHOODS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);   
//            
//            $sqlParameters[":borough_id"] =  $borough_id;
//            $preparedStatement = $this->dbh->prepare('select * from BOROUGH where ID=:borough_id LIMIT 1');
//            $preparedStatement->execute($sqlParameters);
//            $rows["BOROUGH"] = $preparedStatement->fetch(PDO::FETCH_ASSOC);   
//            
//            $preparedStatement = $this->dbh->prepare('select * from ITEM_VW where BOROUGH_ID=:borough_id and ITEM_STATE_ID=0');
//            $preparedStatement->execute($sqlParameters);
//            $rows["ITEMS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
//            
//            $tag_model = new TagModel();
//            $rows["TAGS"] = $tag_model->getAllTags();            
//
//            return $rows;            
//        }
//        
//	public function searchForItem($query) 
//	{
//            $preparedStatement = $this->dbh->prepare('select * from BOROUGH');
//            $preparedStatement->execute();
//            $rows["BOROUGHS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
//            
//            $preparedStatement = $this->dbh->prepare('select * from NEIGHBORHOOD');
//            $preparedStatement->execute();
//            $rows["NEIGHBORHOODS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);   
//            
//		$sqlParameters[":search"] =  '%'. $query .'%';
//                $preparedStatement = $this->dbh->prepare('select * from ITEM_VW where ITEM_STATE_ID=0 and (lower(TITLE) like lower(:search) OR lower(DESCRIPTION) like lower(:search))');
//		$preparedStatement->execute($sqlParameters);
//		$rows["ITEMS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
//
//		return $rows;
//	}

	public function request($itemid) 
	{
            $sqlParameters[":itemid"] =  $itemid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row[] = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            $user_model = new UserModel();
            $row[] = $user_model->getFeedbackAsLender($row[0]['LENDER_ID']);
            $row[] = $user_model->getNetworksForUser($row[0]['LENDER_ID']);

            return $row;
	}
        
        public function getItemDetails($itemid)
        {
            $sqlParameters[":itemid"] =  $itemid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            return $preparedStatement->fetch(PDO::FETCH_ASSOC);            
        }

        // Passed in parameters are all of the borrower
        public function submitRequest($itemid, $userid, $duration, $message)
	{
            $sqlParameters[":itemid"] =  $itemid;
            $sqlParameters[":userid"] =  $userid;

            if ($duration < 1 || $duration > 7)
                $duration = 1;

            $sqlParameters[":duration"] =  $duration;
            $sqlParameters[":message"] =  $message;
            $sqlParameters[":requestid"] =  getRandomID();
            $sqlParameters[":create_date"] =  date("Y-m-d H:i:s");
            $preparedStatement = $this->dbh->prepare('INSERT INTO ITEM_REQUESTS (REQUEST_ID,ITEM_ID,REQUESTER_ID,DURATION,MESSAGE, CREATE_DATE) VALUES (:requestid, :itemid, :userid, :duration, :message, :create_date)');
            $preparedStatement->execute($sqlParameters);

            if ($preparedStatement->rowCount() != 1)
                return 1;
            
            // Send an email to the lender saying they received a request
            
            // Get the lender's information first
            $sqlParameters = array();
            $sqlParameters[":itemid"] =  $itemid;
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW a, USER b where a.ITEM_ID=:itemid and b.ID=:userid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $item_row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
            
            if ($preparedStatement->rowCount() != 1)
                return 2;            
            
            // Send the email
            $title = "qhojo - You've received a rental request for {$item_row['TITLE']}";
            $message_to_lender = "Hey " .  $item_row["LENDER_FIRST_NAME"] . "!<br/><br/>";
            $message_to_lender .= "You've received a rental request from {$item_row["FIRST_NAME"]} for your following post: <a href=\"http://{$_SERVER['HTTP_HOST']}/item/index/{$item_row["ITEM_ID"]}\">{$item_row["TITLE"]}</a>. Check your <a href=\"http://{$_SERVER['HTTP_HOST']}/user/dashboard\">dashboard</a> to see the entire rental request.";
            $message_to_lender .= "<br/><br/>-team qhojo<br/><a href=\"http://qhojo.com\">http://qhojo.com</a>";

            $this->sendEmail('do-not-reply@qhojo.com', $item_row['LENDER_EMAIL_ADDRESS'], 'do-not-reply@qhojo.com', $title, $message_to_lender);
            
            return 0;
	}
        
	public function requestComplete($itemid) 
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
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
            $preparedStatement = $this->dbh->prepare('select * from ITEM_VW where CONFIRMATION_CODE=:confirmation_code and BORROWER_PHONE_NUMBER=:phone_number and ITEM_STATE_ID=:status_id LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $item_row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            error_log("1");
            if ($preparedStatement->rowCount() == 1 && $item_row["BORROWER_BP_BUYER_URI"] != null)
            {
                global $bp_api_key;

                Balanced\Settings::$api_key = $bp_api_key;
                Httpful\Bootstrap::init();
                RESTful\Bootstrap::init();
                Balanced\Bootstrap::init();
            
                // Make the hold
                try
                {
                    $account =  Balanced\Account::get($item_row["BORROWER_BP_BUYER_URI"]);
                    $hold = $account->hold($item_row["DEPOSIT"] * 100, 'qhojo.com'); // cents
                }
                
                catch (Exception $e)
                {
                    $error_msg = "Something went wrong. Halt the transaction. Action: borrowerConfirm. Error code 1. Item ID: {$item_row['ITEM_ID']}. Confirmation code: {$confirmation_code}. Phone number: {$phone_number}. Deposit: {$item_row["DEPOSIT"]}<br/>";
                    $error_msg .= $e->getMessage();
                    
                    $this->sendEmail('support@qhojo.com', $item_row['LENDER_EMAIL_ADDRESS'], 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);        
                    $this->sendEmail('support@qhojo.com', $item_row['BORROWER_EMAIL_ADDRESS'], 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);        
                    $this->sendEmail('support@qhojo.com', 'support@qhojo.com', 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);        
                    
                    error_log($e->getMessage());
                    error_log("borrowerConfirm FML 1");
                    return 1;
                }
                
                error_log("2");
                
                if ($hold->uri != null)
                {
                    // Update the state & hold_uri
                    $sqlParameters = null;
                    $sqlParameters[":status_id"] =  2;
                    $sqlParameters[":bp_hold_uri"] =  $hold->uri;
                    $sqlParameters[":item_id"] =  $item_row['ITEM_ID'];
                    $sqlParameters[":startdate"] =  date("Y-m-d H:i:s");
                    $sqlParameters[":enddate"] =  date("Y-m-d H:i:s",strtotime("+" . $item_row['DURATION'] . " days"));
                    $preparedStatement = $this->dbh->prepare('update ITEM set STATE_ID=:status_id, START_DATE=:startdate, END_DATE=:enddate, BORROWER_BP_HOLD_URI=:bp_hold_uri WHERE ID=:item_id LIMIT 1');
                    $preparedStatement->execute($sqlParameters);    
                    error_log("3");    

                    if ($preparedStatement->rowCount() == 1)
                    { 
                        error_log("4");    
                        $message = "Hey " . $item_row["LENDER_FIRST_NAME"] . "! It's qhojo here. We have received " . $item_row["BORROWER_FIRST_NAME"] . "'s confirmation. You can go ahead and hand the item over. It is due back to you by " . date("m/d g:i A", strtotime($sqlParameters[":enddate"])) . ".";
                        $message2 = "When " . $item_row["BORROWER_FIRST_NAME"] . " comes back to return the item, verify it and then text this confirmation code back to us: " . $confirmation_code;
                        $message3 = "Thanks " . $item_row["BORROWER_FIRST_NAME"] . ". The rental duration has now started. We've placed a hold of \${$item_row["DEPOSIT"]} on your credit card. The item must be returned to " . $item_row["LENDER_FIRST_NAME"] . " by " . date("m/d g:i A", strtotime($sqlParameters[":enddate"])) . ".";

                        global $TwilioAccountSid;   
                        global $TwilioAuthToken;
                        global $lender_number;
                        global $borrower_number;
                        $client = new Services_Twilio($TwilioAccountSid, $TwilioAuthToken);
                        $sms = $client->account->sms_messages->create($lender_number, $item_row["LENDER_PHONE_NUMBER"],$message);                        
                        error_log("5");    
                        $sms = $client->account->sms_messages->create($lender_number, $item_row["LENDER_PHONE_NUMBER"],$message2);
                        error_log("6");    
                        $sms = $client->account->sms_messages->create($borrower_number, $item_row["BORROWER_PHONE_NUMBER"],$message3);
                        error_log("7");

                        return 0;
                    }   
                    
                    error_log("borrowerConfirm FML 4");
                    return 4;
                }
                
                $error_msg = "Something went wrong. Halt the transaction. Action: borrowerConfirm. Error code 3. Item ID: {$item_row['ITEM_ID']}. Confirmation code: {$confirmation_code}. Phone number: {$phone_number}. Deposit: {$item_row["DEPOSIT"]}<br/>";
                $this->sendEmail('support@qhojo.com', $item_row['LENDER_EMAIL_ADDRESS'], 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);        
                $this->sendEmail('support@qhojo.com', $item_row['BORROWER_EMAIL_ADDRESS'], 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);    
                $this->sendEmail('support@qhojo.com', 'support@qhojo.com', 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);       
                error_log("borrowerConfirm FML 3");
                return 3;
            }

            error_log("borrowerConfirm FML 2");
            return 2;
        }

	public function lenderConfirm($confirmation_code, $phone_number) 
	{
            global $demo;
            
            error_log("cc:" . $confirmation_code);
            error_log("ph:" . $phone_number);
          
            $sqlParameters[":confirmation_code"] =  $confirmation_code;
            $sqlParameters[":phone_number"] =  $phone_number;
            $sqlParameters[":status_id"] =  2;
            $preparedStatement = $this->dbh->prepare('select * from ITEM_VW where CONFIRMATION_CODE=:confirmation_code and LENDER_PHONE_NUMBER=:phone_number and ITEM_STATE_ID=:status_id LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $item_row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            // In production, the lender can only confirm within 12 hours of the due date
            $td_hrs = (strtotime($item_row['END_DATE']) - time()) / 3600;    

            error_log("1");
            if ($preparedStatement->rowCount() == 1 && ($demo==true || $td_hrs <= 12))
            {
                global $bp_api_key;

                Balanced\Settings::$api_key = $bp_api_key;
                Httpful\Bootstrap::init();
                RESTful\Bootstrap::init();
                Balanced\Bootstrap::init();
                
                try
                {
                    // Void the hold
                    $hold = Balanced\Hold::get($item_row['BORROWER_BP_HOLD_URI']);
                    $hold->void();  
                }
                
                catch (Exception $e)
                {
                    $error_msg = "Something went wrong. Halt the transaction. Action: lenderConfirm. Error code 1. Item ID: {$item_row['ITEM_ID']}. Confirmation code: {$confirmation_code}. Phone number: {$phone_number}. <br/>";
                    $error_msg .= $e->getMessage();
                    
                    $this->sendEmail('support@qhojo.com', $item_row['LENDER_EMAIL_ADDRESS'], 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);        
                    $this->sendEmail('support@qhojo.com', $item_row['BORROWER_EMAIL_ADDRESS'], 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);    
                    $this->sendEmail('support@qhojo.com', 'support@qhojo.com', 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR !', $error_msg);                      
                    
                    error_log($e->getMessage());
                    error_log("lenderConfirm FML 1");
                    return 1;
                }
                
                global $transaction_fee_variable;
                global $transaction_fee_fixed;
                $total_without_fee = $item_row["RATE"] * $item_row["DURATION"];
                $total_with_fee = $total_without_fee*(1-$transaction_fee_variable)-$transaction_fee_fixed;
                
                try
                {
                    // Debit the borrower
                    $account = Balanced\Account::get($item_row['BORROWER_BP_BUYER_URI']);
                    $account->debit($total_without_fee * 100);                    // cents
                }
                
                catch (Exception $e)
                {
                    $error_msg = "Something went wrong. Halt the transaction. Action: lenderConfirm. Error code 2. Item ID: {$item_row['ITEM_ID']}. Confirmation code: {$confirmation_code}. Phone number: {$phone_number}. Debit: {$total_without_fee}<br/>";
                    $error_msg .= $e->getMessage();
                    
                    $this->sendEmail('support@qhojo.com', $item_row['LENDER_EMAIL_ADDRESS'], 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);        
                    $this->sendEmail('support@qhojo.com', $item_row['BORROWER_EMAIL_ADDRESS'], 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);    
                    $this->sendEmail('support@qhojo.com', 'support@qhojo.com', 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);                      
                    
                    error_log($e->getMessage());
                    error_log("lenderConfirm FML 2");
                    return 2;
                }                
                
                // Pay the lender
                $status = $this->paypalMassPayToLender($item_row['LENDER_PAYPAL_EMAIL_ADDRESS'],$total_with_fee);
                
                if ($status != 0)
                {
                    $error_msg = "Something went wrong. Action: lenderConfirm. Error code 3. Item ID: {$item_row['ITEM_ID']}. Confirmation code: {$confirmation_code}. Phone number: {$phone_number}. Pay: {$total_with_fee}. Lender PP: {$item_row['LENDER_PAYPAL_EMAIL_ADDRESS']}<br/>";
                    $this->sendEmail('support@qhojo.com', 'support@qhojo.com', 'support@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - ERROR!', $error_msg);                      
                    
                    error_log("Error with sending {$total_with_fee} to {$item_row['LENDER_PAYPAL_EMAIL_ADDRESS']}");
                    error_log("lenderConfirm FML 3");
                    //return 3;
                }
                
                $sqlParameters = null;
                $sqlParameters[":status_id"] =  3;
                $sqlParameters[":item_id"] =  $item_row['ITEM_ID'];
                $preparedStatement = $this->dbh->prepare('update ITEM set STATE_ID=:status_id WHERE ID=:item_id LIMIT 1');
                $preparedStatement->execute($sqlParameters);    
                error_log("2");  
                
                if ($preparedStatement->rowCount() == 1)
                {
                    error_log("3");    
                    $message = "Hey " . $item_row["BORROWER_FIRST_NAME"] . "! It's qhojo here. We have received " . $item_row["LENDER_FIRST_NAME"] . "'s confirmation. Go ahead and return the item. The hold of \${$item_row["DEPOSIT"]} on your credit card has been released.";

                    global $TwilioAccountSid;   
                    global $TwilioAuthToken;
                    global $borrower_number;
                    $client = new Services_Twilio($TwilioAccountSid, $TwilioAuthToken);
                    $sms = $client->account->sms_messages->create($borrower_number, $item_row["BORROWER_PHONE_NUMBER"],$message);   
                    error_log("4");    
                    
                    // send an email to lender and borrower and ask for feedback
                    $message_to_lender = "Hey " .  $item_row["LENDER_FIRST_NAME"] . "!<br/><br/>";
                    $message_to_lender .= "Now that the transaction is complete, we owe you some money. Check your paypal account: we have deposited \$" . number_format($total_with_fee,2) . ". <br/><br/>";
                    $message_to_lender .= "Also, when you get a second, help our community be a better one. Give us your feedback on this transaction by clicking <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/item/feedback/" . $item_row['ITEM_ID'] . "/0\">here</a>.";
                    $message_to_lender .= "<br/><br/>-team qhojo<br/><a href=\"http://qhojo.com\">http://qhojo.com</a>";

                    $message_to_borrower = "Hey " .  $item_row["BORROWER_FIRST_NAME"] . "!<br/><br/>";
                    $message_to_borrower .= "Now that the transaction is complete, we're gonna need some of your money. Check your credit card account: we have deducted \$" . number_format($total_without_fee,2) . ". <br/><br/>";
                    $message_to_borrower .= "Also, when you get a second, help our community be a better one. Give us your feedback on this transaction by clicking <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/item/feedback/" . $item_row['ITEM_ID'] . "/1\">here</a>.";
                    $message_to_borrower .= "<br/><br/>-team qhojo<br/><a href=\"http://qhojo.com\">http://qhojo.com</a>";                  

                    $this->sendEmail('do-not-reply@qhojo.com', $item_row['LENDER_EMAIL_ADDRESS'], 'do-not-reply@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - Transaction Complete!', $message_to_lender);
                    $this->sendEmail('do-not-reply@qhojo.com', $item_row['BORROWER_EMAIL_ADDRESS'], 'do-not-reply@qhojo.com', 'qhojo - ' . $item_row['TITLE'] . ' - Transaction Complete!', $message_to_borrower);

                    return 0;
                }

                error_log("lenderConfirm FML 4");
                return 4;
            }

            error_log("lenderConfirm FML 5");
            return 5;
	}

	public function getDuration($itemid)
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT DURATION FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row == null ? -1 : $row['DURATION'];		
	}

        public function post($userid, $usermodel, $locationmodel)
	{
            $row[] = $usermodel->getUserDetails($userid);
            $row[] = $locationmodel->getAllLocations();
            $row[] = $usermodel->getFeedbackAsLender($userid);
            $row[] = $usermodel->getNetworksForUser($userid);
            
            $tag_model = new TagModel();
            $row[] = $tag_model->getAllTags();
            
            return $row;
	}
        
	public function submitPost($itemid, $userid, $title, $rate,$deposit,$description, $locationid, $files, $tags)
	{ 
            if ($deposit == null || $deposit < 1 || $deposit > 2500 || $rate == null || $rate < 1)
            {
                error_log("submitPost FML -1");
                return -1;
            }
            
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
            $sqlParameters[":create_date"] =  date("Y-m-d H:i:s");
            $preparedStatement = $this->dbh->prepare('insert into ITEM (ID, TITLE,DESCRIPTION,RATE,DEPOSIT,STATE_ID,LOCATION_ID,LENDER_ID, ACTIVE_FLAG, CREATE_DATE) VALUES (:id,:title,:description,:rate,:deposit,0,:locationid,:userid, 1, :create_date)');
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
            
            $this->dbh->beginTransaction();
            $stmt = $this->dbh->prepare ($sql);
            
            try 
            {
                $stmt->execute($insert_values);
            } 
            
            catch (PDOException $e)
            {
                error_log($e->getMessage());
                return -1;
            }
            
            $this->dbh->commit();
            
            // Handle tags. They're optional
            if (!empty($tags))
            {
                // item tags
                $datafields = array('ITEM_ID' => '', 'TAG_ID' => '');
                $data = array();
                
                foreach ($tags as $tag)
                {
                    $data[] = array('ITEM_ID' => $itemid, 'TAG_ID' => $tag);
                }         

                $insert_values = array();
                $question_marks = array();
                foreach($data as $d)
                {
                    $question_marks[] = '('  . $this->placeholders('?', sizeof($d)) . ')';
                    $insert_values = array_merge($insert_values, array_values($d));
                }            

                $sql = "INSERT INTO ITEM_TAG (" . implode(",", array_keys($datafields) ) . ") VALUES " . implode(',', $question_marks);

                $this->dbh->beginTransaction();
                $stmt = $this->dbh->prepare ($sql);

                try 
                {
                    $stmt->execute($insert_values);
                } 

                catch (PDOException $e)
                {
                    error_log($e->getMessage());
                    return -2;
                }

                $this->dbh->commit();
            }
            
            return $itemid;	
	}   
        
        public function postComplete($itemid)
        {
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
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
        
        public function feedback($itemid)
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row;	
	}        
        
        public function submitFeedback($userid, $itemid, $rating, $comments)
	{
                // Find out whether we are the borrower or the lender
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('select LENDER_ID, BORROWER_ID from ITEM_VW where ITEM_ID=:itemid LIMIT 1');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

                if ($row["LENDER_ID"] == $userid)
                    $flag = 0;
                else if ($row["BORROWER_ID"] == $userid)
                    $flag = 1;
                else
                    return 1;   // Hacker alert
                
                $rating_query = null;
                $comments_query = null;
                
                if ($flag == 0)
                {
			$rating_query = "LENDER_TO_BORROWER_STARS";
                        $comments_query = "LENDER_TO_BORROWER_COMMENTS";
                }
                
                else
                {
			$rating_query = "BORROWER_TO_LENDER_STARS";                   
                        $comments_query = "BORROWER_TO_LENDER_COMMENTS";
                }
                
		$sqlParameters[":rating"] =  $rating;	
		$sqlParameters[":comments"] =  $comments;		                

		$preparedStatement = $this->dbh->prepare('update ITEM set ' . $rating_query . '=:rating, ' . $comments_query . '=:comments' . ' WHERE ID=:itemid');
		$preparedStatement->execute($sqlParameters);

		return $preparedStatement->rowCount() == 1 ? 0 : 1;               
	}
        
        public function feedbackComplete($itemid)
	{
		$sqlParameters[":itemid"] =  $itemid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
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
        
        public function submitAcceptance($request_id, $userid)
        {
            $sqlParameters[":requestid"] =  $request_id;
            $sqlParameters[":userid"] =  $userid;
            $preparedStatement = $this->dbh->prepare('select 1 from ITEM where ID = (select ITEM_ID from ITEM_REQUESTS where REQUEST_ID=:requestid) and LENDER_ID = :userid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
            
            if ($row == null)
                return 1;
            
            $sqlParameters = array();
            $sqlParameters[":requestid"] =  $request_id;
            $preparedStatement = $this->dbh->prepare('UPDATE ITEM_REQUESTS set ACCEPTED_FLAG = 1 where REQUEST_ID=:requestid LIMIT 1');
            $preparedStatement->execute($sqlParameters);

            $itemreq = $this->getRequest($request_id);
            
            $sqlParameters = array();
            $sqlParameters[":itemid"] =  $itemreq['ITEM_ID'];
            $sqlParameters[":confirmation_code"] = $confirmation_code = getRandomID();
            $preparedStatement = $this->dbh->prepare('UPDATE ITEM set STATE_ID = 1,CONFIRMATION_CODE=:confirmation_code where ID=:itemid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            
            $sqlParameters = array();
            $sqlParameters[":itemid"] =  $itemreq['ITEM_ID'];
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            // email to lender
            $message = "Hey " . $row['LENDER_FIRST_NAME'] . "!<br/><br/>";
            $message .= "You have accepted " . $row['BORROWER_FIRST_NAME'] . "'s rental request. Your item, " . $row['TITLE'] . ", is now reserved for " . $row['DURATION'] . " day(s).<br/><br/>";
            $message .= "Your confirmation code is: <b><u>" . $row['CONFIRMATION_CODE'] . "</b></u><br/><br/>";
            $message .= "Here's what you need to do next:<br/><br/>";
            $message .= "1) Over email, arrange to meet with " .  $row['BORROWER_FIRST_NAME'] . ". Here's " . $row['BORROWER_FIRST_NAME'] . "'s email address for reference: " .  $row['BORROWER_EMAIL_ADDRESS'] . "<br/>";
            $message .= "2) Once you guys meet, " .  $row['BORROWER_FIRST_NAME'] . " will check out your item. Once satisfied, " . $row['BORROWER_FIRST_NAME'] . " will confirm to qhojo via text message." . "<br/>";
            $message .= "3) We'll pass on this confirmation to you via text message. <b>Only hand the item over once you've received this confirmation from us</b>. At this point, the rental period has started and " . $row['BORROWER_FIRST_NAME'] . " will be responsible to bring the item back after the agreed upon duration.<br/><br/>";
            $message .= "Still confused? Check out our <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/document/howitworks/#lender\">how-it-works guide</a>";
            $message .= "<br/><br/>-team qhojo<br/><a href=\"http://qhojo.com\">http://qhojo.com</a>";
            
            $this->sendEmail('do-not-reply@qhojo.com', $row['LENDER_EMAIL_ADDRESS'], 'do-not-reply@qhojo.com', 'qhojo - ' . $row['TITLE'] . ' Reservation Details', $message);
            
            global $borrower_number;
            
            // email to borrower
            $message = "Hey " . $row['BORROWER_FIRST_NAME'] . "!<br/><br/>";
            $message .= "Your rental request for item " . $row['TITLE'] . " for a duration of " . $row['DURATION'] . " day(s) has been approved! <br/><br/>";
            $message .= "Your confirmation code is: <b><u>" . $row['CONFIRMATION_CODE'] . "</b></u>. We just texted it to you. Hang on to it because you'll need it later.<br/><br/>";
            $message .= "Here's what you need to do next:<br/><br/>";
            $message .= "1) Over email, arrange to meet with " .  $row['LENDER_FIRST_NAME'] . ". Here's " . $row['LENDER_FIRST_NAME'] . "'s email address for reference: " .  $row['LENDER_EMAIL_ADDRESS'] . "<br/>";
            $message .= "2) Once you guys meet, check out the item. Once satisfied, reply to the text we sent you with the confirmation code above. (In case you lose the original text, here's the number you need to send the code to: " . $borrower_number . ")<br/>";
            $message .= "3) We'll pass on this confirmation to " .  $row['LENDER_FIRST_NAME'] . " via text message. Once " .  $row['LENDER_FIRST_NAME'] . " has received it, he/she will hand the item over to you. At this point, the rental period has started and you are responsible to bring your item back after the agreed upon duration.<br/><br/>";
            $message .= "Still confused? Check out our <a href=\"http://" . $_SERVER['HTTP_HOST'] . "/document/howitworks/#borrower\">how-it-works guide</a>";
            $message .= "<br/><br/>-team qhojo<br/><a href=\"http://qhojo.com\">http://qhojo.com</a>";
            
            $this->sendEmail('do-not-reply@qhojo.com', $row['BORROWER_EMAIL_ADDRESS'], 'do-not-reply@qhojo.com', 'qhojo - ' . $row['TITLE'] . ' Reserved!', $message);
          
            $sms_message = "Hey " . $row["BORROWER_FIRST_NAME"] . "! It's qhojo here. Text this confirmation ID back to us after you have met " . $row["LENDER_FIRST_NAME"] . " and have verified the item: " . $confirmation_code;
            global $TwilioAccountSid;   
            global $TwilioAuthToken;
            $client = new Services_Twilio($TwilioAccountSid, $TwilioAuthToken);
            $sms = $client->account->sms_messages->create($borrower_number, $row['BORROWER_PHONE_NUMBER'],$sms_message);
            
            return 0;
        }
        
        public function acceptSuccess($itemid)
        {
            $sqlParameters[":itemid"] =  $itemid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
            
            return $row;	                
        }
        
        public function ignore($request_id)
        {
            $sqlParameters[":requestid"] =  $request_id;
            $preparedStatement = $this->dbh->prepare('UPDATE ITEM_REQUESTS set ACCEPTED_FLAG = 0 where REQUEST_ID=:requestid');
            $preparedStatement->execute($sqlParameters);
            
            return $preparedStatement->rowCount() == 1 ? 0 : 1;
        }      
        
        public function delete($itemid)
        {
            $sqlParameters[":itemid"] =  $itemid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
            
            return $row;	            
        }
        
        public function deleteAction($itemid)
        {
            $sqlParameters[":itemid"] =  $itemid;
            $preparedStatement = $this->dbh->prepare('UPDATE ITEM set ACTIVE_FLAG = 0 where ID=:itemid');
            $preparedStatement->execute($sqlParameters);
            
            return $preparedStatement->rowCount() == 1 ? 0 : 1;            
        }
             
        public function paypalDoReferenceTransaction($amount, $billing_agreement_id)
        {
            global $paypal_environment;
            $environment = $paypal_environment;
            
            // Set request-specific fields.
            $paymentAmount = urlencode($amount);
            $currencyID = urlencode('USD');							// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
            $paymentAction = urlencode('SALE');				// or 'Sale' or 'Order'
            $referenceID = urlencode($billing_agreement_id);
            
            // Add request-specific fields to the request string.
            $nvpStr = "&AMT=$paymentAmount&PAYMENTACTION=$paymentAction&CURRENCYCODE=$currencyID&REFERENCEID=$referenceID";

            // Execute the API operation; see the PPHttpPost function above.
            $httpParsedResponseAr = $this->PPHttpPost('DoReferenceTransaction', $nvpStr);

            if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
            {
                return 0;
            } 
            
            else  
            {
                error_log('DoReferenceTransaction failed: ' . print_r($httpParsedResponseAr, true));
                return -1;
            }
        }
        
        public function paypalMassPayToLender($paypal_email, $amount)
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
        
        public function chargeDeposit($itemid)
        {
            $sqlParameters[":itemid"] =  $itemid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
            
            return $row;            
        }
        
        public function chargeDepositAction($itemid)
        {
            $sqlParameters[":itemid"] =  $itemid;
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW WHERE ITEM_ID=:itemid LIMIT 1');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
            
            if ($row == null)
                return -1;
            
            // Charge borrower (DoReferenceTransaction)
            if ($this->paypalDoReferenceTransaction($row['DEPOSIT'], $row['BORROWER_PAYPAL_BILLING_AGREEMENT_ID']) == 0)
            {
                // Pay lender (MassPay)
                if ($this->paypalMassPayToLender($row['LENDER_PAYPAL_EMAIL'], $row['DEPOSIT']) == 0)
                        return $row['ITEM_ID'];
                else
                    return -3;
            }
            
            else
                return -2;
            
            // Set the item to ITEM_STATE=3
        }
        
        public function chargeDepositComplete($itemid)
        {
            return null;      
        }*/
}

?>
