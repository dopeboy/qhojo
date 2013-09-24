<?php

class InviteModel extends Model 
{
    public function submitInvitationCode($method, $code)
    {
        $sqlParameters[":code"] =  $code;
        $preparedStatement = $this->dbh->prepare('SELECT INVITE_ID, COUNT, INVITE_CODE FROM INVITE_VW WHERE INVITE_CODE=:code LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        
        if ($row == null)
            throw new InvalidInvitationCodeException($method);
        else if ($row["COUNT"] == 0)
            throw new InvitationCodeExpiredException($method);
        
        return $row["INVITE_ID"];
    }
    
    public function requestInvitationCode($method, $firstname, $lastname, $email)
    {
        $sqlParameters[":id"] = getRandomID();
        $sqlParameters[":email"] =  $email;
        $sqlParameters[":firstname"] =  $firstname;
        $sqlParameters[":lastname"] =  $lastname;
        $sqlParameters[":date"] =  date("Y-m-d H:i:s");
        
        $preparedStatement = $this->dbh->prepare('INSERT INTO INVITE_REQUEST (ID, FIRST_NAME, LAST_NAME, EMAIL_ADDRESS, DATE_CREATED) VALUES (:id, :firstname, :lastname, :email, :date)');
        $preparedStatement->execute($sqlParameters);
    }    
}

?>