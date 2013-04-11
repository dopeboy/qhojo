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
                    
                    header('Location: /item/main/');
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

                session_destroy();
                
                $this->returnView(0, false,false);
        }
        
        protected function signup()
        {
            $viewmodel = new UserModel();
            
            if ($this->state == 0)
                $this->returnView($viewmodel->signup(), true,false);
            
            else if ($this->state == 1)
            {
                $userid = $viewmodel->signupAction($this->postvalues['emailaddress'],$this->postvalues['password'], $this->postvalues['name']);
                if ($userid != -1)
                    $_SESSION['userid']  = $userid;               
                $this->returnView($userid, false,true);
            }
            
            else if ($this->state == 2)
            {
                $this->returnView(null, true,false);
            }
            
            else if ($this->state == 3)
            {
                $status = $viewmodel->signupExtra($this->userid, $this->postvalues['phonenumber'],$this->postvalues['file']);
                
                if ($status == 0)
                {
                    $_SESSION['firstname'] = $this->postvalues['firstname'];
                    $this->returnView($_SESSION['referrer'], false,true);
                }
                
                else
                    $this->returnView(-1, false,true);
            }
        }
}

?>
