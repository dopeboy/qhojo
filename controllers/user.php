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
                        header('Location: /user/dashboard/');                    
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

        public static function userNotSignedIn($method)
        {
            if (!empty($_SESSION["USER"]["USER_ID"]))
                throw new UserAlreadyLoggedInException($method);

            return true;
        }        
        
 	/*protected function verify() 
	{NAME, FIRST_NAME, ID, PASSWORD, ADMIN 
		$viewmodel = new UserModel();
                $userid = $firstname = $lastname = null;
		$status = $viewmodel->verify($this->postvalues['emailaddress'],$this->postvalues['password'], $userid, $firstname, $lastname);
                
                if ($status != 0)
                {
                    $this->returnView($status, false,true);
                }
                
                else
                {
                    $_SESSION['userid']  = $userid;
                    $_SESSION['firstname']  = $firstname;
                    $_SESSION['lastname']  = $lastname;
                    $_SESSION['admin'] = $viewmodel->isAdmin($userid);
                    
                    if ($_SESSION['referrer'] != null)
                    {
                        $tmp = $_SESSION['referrer'];
                        $_SESSION['referrer'] = null;
                        $this->returnView($tmp, false,true);
                    }
                        
                    else
                        $this->returnView('/', false,true);
                }
	} 
        
        
        
        
        
  
        
        protected function index()
        {
            $viewmodel = new UserModel();
            $this->returnView($viewmodel->index($this->id), true,false);
        }
        
	protected function dashboard() 
	{
            if ($this->userid == null)
            {
                $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
                header('Location: /user/login/null/2');
                exit;
            }
            
            $viewmodel = new UserModel();
            $this->returnView($viewmodel->getDashboardData($this->userid, new ItemModel()), true,false);
	}  

        
        protected function submitFeedback()
        {
            if ($this->userid == null)
            {
                header('Location: /user/login/null/2');
                exit;
            }
            
            $viewmodel = new UserModel();
            $this->returnView($viewmodel->submitFeedback($this->postvalues['role'],$this->postvalues['lender']), false,true);
        }
        
        protected function logout()
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

            $this->returnView(0, false,false);
        }
        
        // STATE IDS
        // 0 - signup page
        // 1 - signup action
        // 2 - extra signup nonbill page
        // 3 - extra signup nonbill action
        // 4 - extra signup debit page
        // 5 - extra signup debit action
        // 6 - extra signup credit page
        // 7 - extra signup credit action
        protected function signup()
        {
            $viewmodel = new UserModel();
            
            if ($this->state == 0)
                $this->returnView($viewmodel->signup(new LocationModel()), true,false);
            
            else if ($this->state == 1)
            {
                $userid = $viewmodel->signupAction($this->postvalues['emailaddress'],$this->postvalues['password'], $this->postvalues['name'], $this->postvalues['locationid']);
                
                if ($userid != -1)
                {
                    $_SESSION['userid']  = $userid;               
                    $_SESSION['firstname'] = $this->postvalues['name'];  
                    $_SESSION['admin'] = $viewmodel->isAdmin($userid);                    
                }
                
                $this->returnView($userid, false,true);
            }
            
            // --- Extra signup (non-bill)
            else if ($this->state == 2 && $this->userid != null)
            {
                $this->returnView($viewmodel->extraSignupFieldsView($this->userid), true,false);
            }
            
            // Extra signup action (non-bill)
            else if ($this->state == 3 && $this->userid != null)
            {
                $status = $viewmodel->extraSignupFieldsAction($this->userid, $this->postvalues['phonenumber'],$this->postvalues['file'], $this->postvalues['networkemail'], $this->postvalues['networkid']);

                if ($status == 0)
                {
                    $this->returnView($_SESSION['referrer'], false,true);
                    $_SESSION['referrer'] = null;
                }
                
                else
                    $this->returnView(-1, false,true);                
            }
            
            // Debit
            else if ($this->state == 4 && $this->userid != null && !$viewmodel->checkExtraFields($this->userid))
            {
                $this->returnView($viewmodel->getUserDetails($this->userid), true,false);
            }      
            
            else if ($this->state == 5 && $this->userid != null && !$viewmodel->checkExtraFields($this->userid))
            {
                $status = $viewmodel->signupBorrowerAction($this->userid, $this->postvalues['uri']);
                
                if ($status == 0)
                {
                    $this->returnView($_SESSION['referrer'], false,true);
                    $_SESSION['referrer'] = null;
                }
                
                else
                    $this->returnView($status, false,true);                  
            }      
            
            // Credit
            else if ($this->state == 6 && $this->userid != null && !$viewmodel->checkExtraFields($this->userid))
            {
                $this->returnView($viewmodel->getUserDetails($this->userid), true,false);
            }       
            
            else if ($this->state == 7 && $this->userid != null && !$viewmodel->checkExtraFields($this->userid))
            {
                $status = $viewmodel->signupLenderAction($this->userid, trim($this->postvalues['paypalemail']), trim($this->postvalues['paypalfirstname']), trim($this->postvalues['paypallastname']));
                
                if ($status == 0)
                {
                    $this->returnView($_SESSION['referrer'], false,true);
                    $_SESSION['referrer'] = null;
                }
                
                else
                    $this->returnView($status, false,true);    
            }
            
            // Error
            else
            {
                
            }
        }
        
        protected function siteadmin()
        {
            $viewmodel = new UserModel();
            
            if ($this->admin == 1)
            {
                $this->returnView($viewmodel->siteAdmin(), true,false);
            }
        }
        
        protected function confirmnetwork()
        {
            if ($this->id != null && $this->state != null)
            {
                $viewmodel = new UserModel();
                
                if ($this->state == 0 && $viewmodel->confirmNetworkAction($this->id) == 0)
                {
                    header('Location: /user/confirmnetwork/' . $this->id . '/1');
                    exit;                    
                }
                
                else if ($this->state == 1)
                    $this->returnView($viewmodel->confirmNetworkSuccess($this->id), true,false);
                
                else 
                    $this->returnView(null, true,false);
            }
        }
        
        protected function edit()
        {
            if ($this->id == $this->userid)
            {
                $viewmodel = new UserModel();
                
                if ($this->state == 0)
                {
                    $this->returnView($viewmodel->editUser($this->userid), true,false);                    
                }
                
                else if ($this->state == 1)
                {
                    $this->returnView($viewmodel->editEmailAction(trim($this->postvalues['data']), $this->userid), false,true);
                }
                
                else if ($this->state == 2)
                {
                    $this->returnView($viewmodel->editPaypalEmailAddressAction(trim($this->postvalues['ppFirstName']),trim($this->postvalues['ppLastName']),trim($this->postvalues['ppEmailAddress']), $this->userid), false,true);      
                }
                
                else if ($this->state == 3)
                {
                    $this->returnView($viewmodel->editNetworkAction(trim($this->postvalues['networkid']),trim($this->postvalues['email']), $this->userid), false,true);      
                }               
                
                else if ($this->state == 4)
                {
                    $this->returnView($viewmodel->editProfilePicture($this->postvalues['picture'],$this->userid), false,true);      
                }
                
                else if ($this->state == 5)
                {
                    $this->returnView($viewmodel->editLocation($this->postvalues['location_id'],$this->userid), false,true);      
                }  
                
                else if ($this->state == 6)
                {
                    $this->returnView($viewmodel->removeCreditCard($this->userid), false,true);      
                }                
            }
        }
        
        protected function addcard()
        {
            $user_model = new UserModel();
            
            if ($this->id == $this->userid && $user_model->checkDebitMethod($this->userid))
            {
                if ($this->state == 0)
                    $this->returnView($user_model->getUserDetails($this->userid), true,false);
                
                else if ($this->state == 1)
                    $this->returnView($user_model->addCard($this->userid, $this->postvalues['uri']), false, true);
            }                
        }        */
}

?>
