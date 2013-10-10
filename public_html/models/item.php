<?php

require "lib/twilio/Twilio.php";

class ItemModel extends Model 
{
    public function getThreeLatestItems($method)
    {
        $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_EXTENDED_VW ORDER BY CREATE_DATE DESC LIMIT 3');
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
        
        $where = ' WHERE ACTIVE=1';
        
        if ($query != null || $location != null)
        {
            if ($query != null)
            {
                $sqlParameters[":query"] =  '%' . $query . '%';
                $query_clause = ' AND (lower(TITLE) like lower(:query) OR lower(DESCRIPTION) like lower(:query))';
            }

            if ($location != null)
            {
                $sqlParameters[":location"] =  $location;

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
            $user_clause = ' AND LENDER_ID = :user_id';
            
            $sqlParametersUser[":user_id"] =  $user_id;
            $preparedStatement = $this->dbh->prepare('SELECT FIRST_NAME FROM USER_VW where USER_ID=:user_id and ACTIVE=1 LIMIT 1');
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
            $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_EXTENDED_VW ' . $where . $query_clause . $location_clause . $user_clause . ' ORDER BY CREATE_DATE DESC LIMIT ' . $start_at . ',' . $results_per_page);
            $preparedStatement->execute($sqlParameters);
            $rows["ITEMS"] = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);   
        }
        
        return $rows;
    }
    
    public function getMyActiveItems($user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM ACTIVE_ITEMS_VW where LENDER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);
        return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);        
    }
    
    public function getMyInactiveItems($user_id)
    {
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM INACTIVE_ITEMS_VW where LENDER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);
        return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);        
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
        
        if ($row['ITEM']['LENDER_ID'] != $user_id)
        {
            // Increment view count
            $sqlParameters = array();
            $sqlParameters[":item_id"] =  $item_id;        
            $preparedStatement = $this->dbh->prepare('UPDATE ITEM SET VIEWS=VIEWS+1 WHERE ID=:item_id');
            $preparedStatement->execute($sqlParameters);
        }
        
        return $row;
    }
    
    public function post($method, $user_id)
    {   
        $user_model = new UserModel(); 
        $row["USER"] = $user_model->getUserDetails($user_id);
        $row["ITEM"]["ITEM_ID"] = getRandomID();
        
        $user_model = new UserModel();
        $row["USER"]["NEED_EXTRA_FIELDS"] = $user_model->checkIfUserNeedsExtraFields($user_id);
        
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
        $row["USER"]["NEED_EXTRA_FIELDS"] = $user_model->checkIfUserNeedsExtraFields($user_id);        
        
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
    
    public function getItemExtended($item_id)
    {
        $sqlParameters[":item_id"] =  $item_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_EXTENDED_VW where ITEM_ID=:item_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        
        return $preparedStatement->fetch(PDO::FETCH_ASSOC);        
    }
    
    public function getItem($item_id)
    {
        $sqlParameters[":item_id"] =  $item_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM ITEM_VW where ITEM_ID=:item_id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        
        return $preparedStatement->fetch(PDO::FETCH_ASSOC);        
    }
    
    public function deactivateItem($method, $user_id, $item_id)
    {
        $sqlParameters[":item_id"] =  $item_id;
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('UPDATE ITEM SET ACTIVE=0 where ID=:item_id and LENDER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);        
    }

    public function activateItem($method, $user_id, $item_id)
    {
        $sqlParameters[":item_id"] =  $item_id;
        $sqlParameters[":user_id"] =  $user_id;
        $preparedStatement = $this->dbh->prepare('UPDATE ITEM SET ACTIVE=1 where ID=:item_id and LENDER_ID=:user_id');
        $preparedStatement->execute($sqlParameters);            
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
