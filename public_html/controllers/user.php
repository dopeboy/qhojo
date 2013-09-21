<?php

class User extends Controller 
{
    protected function signin() 
    {
        if (($method = Method::GET) && User::userNotSignedIn($method) && ($this->state == null || $this->state == 0 || $this->state == 100))
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
        if (($method = Method::GET) && User::userNotSignedIn($method) && ($this->state == 0 || $this->state == null))
            $this->returnView(null, $method);

        else if (($method = Method::POST) && User::userNotSignedIn($method) && $this->state == 1)
        {
            $user_info = $this->user_model->join
            (
                $method,
                $this->validateParameter($this->postvalues['firstname'],"First Name",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidName')),
                $this->validateParameter($this->postvalues['lastname'],"Last Name",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidName')),
                $this->validateParameter($this->postvalues['zipcode'],"Zipcode",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidZipCode')),
                $this->validateParameter($this->postvalues['email'],"Email Address",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidEmailAddress')),
                $this->validateParameter($this->postvalues['password'],"Password",$method,array('Validator::isNotNullAndNotEmpty','Validator::isValidPassword'))
            );

            $this->directUser($user_info);                
        } 
    }

    protected function index()
    {
        $method = Method::GET;
        $this->returnView($this->user_model->index($method, $this->validateParameter($this->id,"User ID",$method,array('Validator::isNotNullAndNotEmpty'))), $method);
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

    // Profile Picture (100), Blurb(500), Phone # (200), PP (300), CC (400)
    protected function extrasignup()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && $this->state == 0)
        {
            if ($status = $this->user_model->userNeedsExtraFields($_SESSION["USER"]["USER_ID"]))
            {
                if (!empty($this->urlvalues['return']))
                    $this->pushReturnURL ($this->urlvalues['return']);

                header('Location: /user/extrasignup/null/' . $status);
            }

            else
            {
                // At this point, all fields are in. So if the user has any transactions that are in the
                // pending state, switch them over to reserved
                $this->transaction_model->movePendingToReserved($method, $_SESSION["USER"]["USER_ID"]);

                if (($url = $this->popReturnURL()) != null)
                    header('Location: ' . $url);                    

                else
                    header('Location: /item/search.php');                    
            }
        }

        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 100))
        {
            $this->returnView(null, $method);            
        }

        else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 101))
        {
            $this->user_model->submitProfilePicture
            (
                $method,
                $_SESSION["USER"]['USER_ID'],
                $this->validateParameter($this->postvalues['profile-picture'],"Profile Picture",$method,array('Validator::isNotNullAndNotEmpty'))
            );

            $this->returnView(json_encode(array("Action" => "SUBMIT-PROFILE-PICTURE", "URL" => "/user/extrasignup/null/0")), $method); 
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

            $this->returnView(json_encode(array("Action" => "VERIFY-VERIFICATION-CODE", "URL" => "/user/extrasignup/null/0")), $method);     
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

             $this->returnView(json_encode(array("Action" => "SUBMIT-PAYPAL", "URL" => "/user/extrasignup/null/0")), $method);           
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

             $this->returnView(json_encode(array("Action" => "SUBMIT-CC", "URL" => "/user/extrasignup/null/0")), $method);           
        }

        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 500))
            $this->returnView(null, $method);          

        else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 501))
        {
            $this->user_model->submitBlurb
            (
                $method,
                $_SESSION["USER"]['USER_ID'],
                $this->validateParameter($this->postvalues['blurb'],"Blurb",$method,array('Validator::isNotNullAndNotEmpty'))
            );

            $this->returnView(json_encode(array("Action" => "SUBMIT-CC", "URL" => "/user/extrasignup/null/0")), $method);           
        }            
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
            $this->returnView(json_encode(array("URL" => "/item/search")), Method::POST);                            
    }

    public static function isUserSignedIn()
    {
        return !empty($_SESSION["USER"]["USER_ID"]);
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
