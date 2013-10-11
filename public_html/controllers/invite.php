<?php

class Invite extends Controller 
{    
    protected function submit()
    {
        if (($method = Method::NAKED) && ($this->state == null || $this->state == 0 || $this->state == 100) && User::userNotSignedIn($method) )
              $this->returnView(null, $method);
        
        else if (($method = Method::POST) && $this->state==1 && User::userNotSignedIn($method) )
        {
            $invite_model = new InviteModel();
            
            $invite_id = $invite_model->submitInvitationCode
            (
                $method,
                $this->validateParameter($this->postvalues['code'],"Invite Code",$method,array('Validator::isNotNullAndNotEmpty'))
            );
            
            $this->returnView(json_encode(array("URL" => "/user/join/" . $invite_id . "/0")), $method);
        }   
    }      
    
    
    protected function request()
    {
        if (($method = Method::NAKED) && ($this->state == null || $this->state == 0 || $this->state == 100) && User::userNotSignedIn($method) )
              $this->returnView(null, $method);
        
        else if (($method = Method::POST) && $this->state==1 && User::userNotSignedIn($method) )
        {
            $invite_model = new InviteModel();
            
            $invite_model->requestInvitationCode
            (
                $method,
                $this->validateParameter($this->postvalues['firstname'],"First Name",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidName')),
                $this->validateParameter($this->postvalues['lastname'],"Last Name",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidName')),
                $this->validateParameter($this->postvalues['email'],"Email Address",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidEmailAddress'))
            );
            
            $this->returnView(json_encode(array("URL" => "/invite/request/null/2")), $method);
        }   
        
        else if (($method = Method::NAKED) && ($this->state == 2) && User::userNotSignedIn($method) )
              $this->returnView(null, $method);        
    }     
}

?>
