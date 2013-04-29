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
                    header('Location: /user/login/null/1');
                    exit;
                }
                
                else
                {
                    $_SESSION['userid']  = $userid;
                    $_SESSION['firstname']  = $firstname;
                    $_SESSION['lastname']  = $lastname;
                    $_SESSION['admin'] = $viewmodel->isAdmin($userid);
                    
                    if ($_SESSION['referrer'] != null)
                        header('Location: ' . $_SESSION['referrer']);
                    else
                        header('Location: /item/main/');
                                        
                    $_SESSION['referrer'] = null;
                    exit;
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
            
            // --- Extra signup
            
            else if ($this->state == 2)
            {
                $this->returnView($viewmodel->extraSignup($this->userid), true,false);
            }
            
            // Express checkout
            else if ($this->state == 3)
            {
                $viewmodel->paypalExpressCheckout();
            }
            
            // Paypal billing agreement created where id=token
            else if ($this->state == 4 && $this->id != null)
            {
                $this->returnView($viewmodel->extraSignup($this->userid), true,false);
            }
            
            else if ($this->state == 5)
            {
                $status = $viewmodel->signupExtra($this->userid, $this->postvalues['phonenumber'],$this->postvalues['file'], $this->postvalues['token'], $this->postvalues['networkemail'], $this->postvalues['networkid']);
                
                if ($status == 0)
                {
                    $this->returnView($_SESSION['referrer'], false,true);
                    $_SESSION['referrer'] = null;
                }
                
                else
                    $this->returnView(-1, false,true);
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
