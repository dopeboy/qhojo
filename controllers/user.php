<?php

class User extends Controller 
{
	protected function login() 
	{
		$viewmodel = new UserModel();
		$this->returnView($viewmodel->login(), true,false);
	}
        
 	protected function verify() 
	{
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
                $status = $viewmodel->signupLenderAction($this->userid, $this->postvalues['paypalemail'], $this->postvalues['paypalfirstname'], $this->postvalues['paypallastname']);
                
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
                    $this->returnView($viewmodel->submitEditUser($this->postvalues['networkid'], $this->postvalues['networkemail'], $this->userid), false,true);
                }
                
                else if ($this->state == 2)
                {
                     $this->returnView(null, true,false);      
                }
            }
        }
}

?>
