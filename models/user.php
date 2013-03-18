<?php

class UserModel extends Model 
{
	public function login() 
	{
		return 0;
	}
        
	public function verify($emailaddress, $password, &$userid, &$firstname, &$lastname) 
	{
            $sqlParameters[":email"] =  $emailaddress;
            $sqlParameters[":password"] =  $password;
            $preparedStatement = $this->dbh->prepare('SELECT FIRST_NAME, LAST_NAME, ID FROM USER WHERE EMAIL_ADDRESS=:email and PASSWORD=:password');
            $preparedStatement->execute($sqlParameters);
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

            if ($row == null)
                return -1;

            $userid = $row['ID'];
            $firstname = $row['FIRST_NAME'];
            $lastname = $row['LAST_NAME'];

            return 0;
	}
        
        public function index($userid)
        {
            $row["USER"] = $this->getUserDetails($userid);
            $row["LENDER_FEEDBACK"] = $this->getFeedbackAsLender($userid);
            $row["BORROWER_FEEDBACK"] = $this->getFeedbackAsBorrower($userid);
            
            return $row;
        }
        
        public function getUserDetails($userid)
        {
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('SELECT * FROM USER WHERE ID=:userid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row;	            
        }
        
        public function getDashboardData($userid, $item_model)
        {
            $row["loans"]["current"] = $item_model->getCurrentLoans($userid);
            $row["loans"]["past"] = $item_model->getPastLoans($userid);
            $row["borrows"]["current"] = $item_model->getCurrentBorrows($userid);
            $row["borrows"]["past"] = $item_model->getPastBorrows($userid);

            return $row;
        }
        
        public function getFeedbackAsLender($userid)
        {
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('SELECT AVG(BORROWER_TO_LENDER_STARS) as LENDER_FEEDBACK FROM ITEM WHERE LENDER_ID=:userid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row['LENDER_FEEDBACK'];	            
        }
        
        public function getFeedbackAsBorrower($userid)
        {
		$sqlParameters[":userid"] =  $userid;
		$preparedStatement = $this->dbh->prepare('SELECT AVG(LENDER_TO_BORROWER_STARS) as BORROWER_FEEDBACK FROM ITEM WHERE BORROWER_ID=:userid');
		$preparedStatement->execute($sqlParameters);
		$row = $preparedStatement->fetch(PDO::FETCH_ASSOC);

		return $row['BORROWER_FEEDBACK'];	            
        }  
        
        public function signup()
        {            
            return 0;
        }
        
        public function signupAction($firstname, $emailaddress, $password, $phonenumber)
        {
            $sqlParameters[":first_name"] =  $firstname;
            $sqlParameters[":email_address"] =  $emailaddress;
            $sqlParameters[":password"] =  $password;
            $sqlParameters[":phonenumber"] =  $phonenumber;
            $preparedStatement = $this->dbh->prepare('insert into USER (FIRST_NAME,EMAIL_ADDRESS,PASSWORD, PHONE_NUMBER) VALUES (:first_name, :email_address, :password, :phonenumber)');
            $preparedStatement->execute($sqlParameters);

            return $this->dbh->lastInsertId();                 
        }
}

?>
