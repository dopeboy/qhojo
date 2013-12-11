<?php

class User extends Controller 
{
    protected function signin() 
    {
        if (($method = Method::NAKED) && User::userNotSignedIn($method) && ($this->state == null || $this->state == 0 || $this->state == 100 || $this->state == 200))
        {
            if (!empty($this->urlvalues['return']))
                $this->pushReturnURL ($this->urlvalues['return']);

            $this->returnView(null, $method);
        }

        else if (($method = Method::POST) && User::userNotSignedIn($method) && $this->state == 1)
        {
            $user_info = $this->user_model->verify
            (
                $method, 
                $this->validateParameter($this->postvalues['email'],"email",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidEmailAddress')),
                $this->validateParameter($this->postvalues['password'],"password",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidPassword'))
            );

            $this->directUser($user_info);
        }
    }

    protected function join()
    {        
        if (($method = Method::NAKED) && User::userNotSignedIn($method) && ($this->state == 0 || $this->state == null))
            $this->returnView($this->user_model->joinView($method, $this->validateParameter($this->id,"Invite ID",$method,array('Validator::isNotNullAndNotEmpty'))), $method);

        else if (($method = Method::POST) && User::userNotSignedIn($method) && $this->state == 1)
        {
            $user_info = $this->user_model->join
            (
                $method,
                $this->validateParameter($this->postvalues['firstname'],"First Name",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidName')),
                $this->validateParameter($this->postvalues['lastname'],"Last Name",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidName')),
                $this->validateParameter($this->postvalues['zipcode'],"Zipcode",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidZipCode')),
                $this->validateParameter($this->postvalues['email'],"Email Address",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidEmailAddress')),
                $this->validateParameter($this->postvalues['password'],"Password",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidPassword')),
                $this->validateParameter($this->postvalues['invite-id'],"Invite ID",$method,array('Validator::isNotNullAndNotEmpty'))
            );

            $this->directUser($user_info);                
        } 
    }

    protected function index()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 0 || $this->state == null))
            $this->returnView($this->user_model->index($method, $this->validateParameter($this->id,"User ID",$method,array('Validator::isNotNullAndNotEmpty'))), $method);
        
        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 1))
            $this->returnView($this->user_model->index($method, $this->validateParameter($this->id,"User ID",$method,array('Validator::isNotNullAndNotEmpty'))), $method);
    }    
    
    // 100 (blurb),  200 (profile picture-upload), 250 (profile picture - clear), 300 (location), 400 (personal website)
    protected function edit()
    {
        if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 100))
        {
            $this->user_model->submitBlurb
            (
                $method,
                $_SESSION["USER"]['USER_ID'],
                $this->postvalues['blurb']
            );
            
            $this->returnView(json_encode(array("Action" => "SUBMIT-BLURB")), $method);  
        }     
        
        else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 200))
        {
            $this->user_model->submitProfilePicture
            (
                $method,
                $_SESSION["USER"]['USER_ID'],
                $this->validateParameter($this->postvalues['profilepicture'],"Profile Picture",$method,array('Validator::isNotNullAndNotEmpty'))
            );
            
            $this->returnView(json_encode(array("Action" => "SUBMIT-PP")), $method);  
        }        
        
        else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 250))
        {
            $this->user_model->clearProfilePicture
            (
                $method,
                $_SESSION["USER"]['USER_ID']
            );
            
            $this->returnView(json_encode(array("Action" => "SUBMIT-PP")), $method);  
        }      
        
        else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 400))
        {
            // This is an optional parameter. So if it's non-empty, check if it is valid
            if (trim($this->postvalues['website']) != '')
            {
                 $this->validateParameter($this->postvalues['website'],"Website",$method,array('Validator::isValidURL'), 2);
            }
            
            $this->user_model->submitPersonalWebsite
            (
                $method,
                $_SESSION["USER"]['USER_ID'],
                trim($this->postvalues['website'])
            );
            
            $this->returnView(json_encode(array("Action" => "SUBMIT-PERSONALWEBSITE")), $method);  
        }            
    }

    protected function dashboard()
    {
        if (($method = Method::GET) && User::userSignedIn($method))
            $this->returnView($this->user_model->dashboard($method, $_SESSION["USER"]["USER_ID"]), $method);
    }

    protected function signout()
    {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) 
        {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                      $params["path"], $params["domain"],
                      $params["secure"], $params["httponly"]
                      );
        }

        session_unset();
        session_destroy();

        header('Location: /') ;           
    }

    // Phone # (200), PP (300), CC (400)
    protected function completeprofile()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 0 || $this->state == null))
        {
            if (($status = $this->user_model->userNeedsExtraFields($_SESSION["USER"]["USER_ID"])))
            {                header('Location: /user/completeprofile/null/' . $status);
            }

            else
            {
                // At this point, all fields are in. So if the user has any transactions that are in the
                // pending state, switch them over to reserved
                $pending = $this->transaction_model->movePendingToReserved($method, $_SESSION["USER"]["USER_ID"]);

                header('Location: /user/profilecompleted/null/' . ($pending == false ? '0' : '1'));                    
            }
        }

        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 200))
        {
            $this->returnView($this->user_model->phoneNumber($method, $_SESSION["USER"]['USER_ID']), $method);            
        }     

        else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 201))
        {
            $this->user_model->sendPhoneVerificationCode
            (
                $method, 
                $_SESSION["USER"]['USER_ID'],
                $this->postvalues['phonenumber'],"Phone Number",$method,array('Validator::isNotNullAndNotEmpty')
            );

            $this->returnView(json_encode(array("Action" => "VERIFY-PHONE", "Status" => "OK")), $method);   
        }

        else if (($method = Method::POST) && User::userSignedIn($method) && $this->state == 202)
        {
            $this->user_model->verifyVerificationCode
            (
                $method, 
                $_SESSION["USER"]['USER_ID'],
                $this->postvalues['verificationcode'],"Verification Code",$method,array('Validator::isNotNullAndNotEmpty')
            );

            $this->returnView(json_encode(array("Action" => "VERIFY-VERIFICATION-CODE", "URL" => "/user/completeprofile/null/0")), $method);     
        }             

        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 300))
        {
            $this->returnView(null, $method);            
        }         

        else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 301))
        {
            $this->user_model->submitPaypal
            (
                $method,
                $_SESSION["USER"]['USER_ID'],
                $this->validateParameter($this->postvalues['firstname'],"firstname",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['lastname'],"lastname",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['email'],"email",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidEmailAddress'))
            );

            $this->returnView(json_encode(array("Action" => "SUBMIT-PAYPAL", "URL" => "/user/completeprofile/null/0")), $method);           
        }    

        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 400))
            $this->returnView(null, $method);            

        else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 401))
        {
            $this->user_model->submitCreditCard
            (
                $method,
                $_SESSION["USER"]['USER_ID'],
                $this->validateParameter($this->postvalues['card-uri'],"Card URI",$method,array('Validator::isNotNullAndNotEmpty'))
            );

            $this->returnView(json_encode(array("Action" => "SUBMIT-CC", "URL" => "/user/completeprofile/null/0")), $method);           
        }         
    }
    
    protected function profilecompleted()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 0 || $this->state == 1))
            $this->returnView(null, $method);                    
    }

    protected function contact()
    {
        if (($method = Method::POST) && User::userSignedIn($method) && $this->state == 0)
        {
            $this->user_model->contact
            (
                $method,
                $this->validateParameter($this->postvalues['message'],"Message",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['sender-user-id'],"Sender User ID",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['receipient-user-id'],"Receipient User ID",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['entity-type'],"Entity Type",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['entity-id'],"Entity ID",$method,array('Validator::isNotNullAndNotEmpty'))
            );
            
            $this->returnView(json_encode(array("Action" => "CONTACT", "Status" => "SUCCESS", "EntityID" => $this->postvalues['entity-id'])), $method);            
        }         
    }
    
    protected function startlinkedin()
    {
        if (($method = Method::GET) && User::userSignedIn($method))
        {
            global $domain, $linkedInAPIKey;
            
            // Generate the state and save the state in SESSION
            $state = $this->user_model->startLinkedIn($method,$_SESSION["USER"]['USER_ID']);
            $_SESSION["USER"]["LINKEDIN_STATE"] = $state;
            
            $return_url = $domain . "/user/endlinkedin";
            header("Location: https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id={$linkedInAPIKey}&scope=r_basicprofile&state={$state}&redirect_uri={$return_url}");                       
        }
    }
    
    protected function endlinkedin()
    {
        if (($method = Method::GET) && User::userSignedIn($method))
        {
            // Check if the state matches ours
            if ($_SESSION["USER"]["LINKEDIN_STATE"] != $this->validateParameter($this->urlvalues["state"],"LinkedIn State",$method,array('Validator::isNotNullAndNotEmpty')))
                throw new LinkedInAuthenticationFailed($method, $_SESSION["USER"]['USER_ID']);
            
            // If so, clear it from the session
            unset($_SESSION["USER"]["LINKEDIN_STATE"]);
            
            global $domain;
            $return_url = $domain . "/user/endlinkedin";
            
            $this->user_model->endLinkedIn($method,$_SESSION["USER"]['USER_ID'], $this->validateParameter($this->urlvalues["code"],"LinkedIn Code",$method,array('Validator::isNotNullAndNotEmpty')), $return_url);
            
            // Regardless of whether they allow or disallow access to LinkedIn, take them back to their profile page
            $redirect_url = $domain . "/user/index/" . $_SESSION["USER"]['USER_ID'];
            header("Location: {$redirect_url}");                       
        }
    }    
    
    protected function disconnectlinkedin()
    {
        if (($method = Method::GET) && User::userSignedIn($method))
        {
            $this->user_model->disconnectLinkedIn($method,$_SESSION["USER"]['USER_ID']);
            $redirect_url = $domain . "/user/index/" . $_SESSION["USER"]['USER_ID'];
            header("Location: {$redirect_url}");                
        }
    }    

    private function directUser($user_info)
    {
        $url = $this->popReturnURL();

        $_SESSION["USER"]['USER_ID']  = $user_info["USER_ID"];
        $_SESSION["USER"]['FIRST_NAME']  = $user_info["FIRST_NAME"];
        $_SESSION["USER"]['NAME'] = $user_info["NAME"];
        $_SESSION["USER"]['ADMIN']  = $user_info["ADMIN"];
        $_SESSION["RETURN_URL"] = null;

        // If the user came in from another page, send them back to where they were
        if ($url != null)
            $this->returnView(json_encode(array("URL" => $url)), Method::POST);

        // Else, send them to the search page
        else
        {
            $this->returnView(json_encode(array("URL" => "/document/index")), Method::POST);                            
        }
            
    }

    public static function isUserSignedIn()
    {
        return !empty($_SESSION["USER"]["USER_ID"]);
    }
    
    public static function isUserAdmin()
    {
        return !empty($_SESSION["USER"]["ADMIN"]);
    }
    
    public static function userSignedIn($method)
    {
        if (empty($_SESSION["USER"]["USER_ID"]))
            throw new UserNotLoggedInException($method);

        return true;
    }
    
    public static function userIsAdmin($method)
    {
        if (empty($_SESSION["USER"]["ADMIN"]) || $_SESSION["USER"]["ADMIN"] == 0)
            throw new UserIsNotAdminException($method);

        return true;
    }    

    public static function userNotSignedIn($method)
    {
        if (!empty($_SESSION["USER"]["USER_ID"]))
            throw new UserAlreadyLoggedInException($method);

        return true;
    }    	
}

?>
