<?php

abstract class Controller 
{
    protected $urlvalues;
    protected $postvalues;
    protected $filevalues;

    protected $action;
    protected $id;
    protected $state;

    protected $item_model;
    protected $user_model;
    protected $transaction_model;
    
    public function __construct($action, $urlvalues, $postvalues, $filevalues, $id, $userid, $state, $admin) 
    {
        $this->action = $action;
        $this->urlvalues = $urlvalues;
        $this->postvalues = $postvalues;
        $this->filevalues = $filevalues;
        $this->id = $id;
        $this->state = $state;
        
        $this->item_model = new ItemModel();
        $this->user_model = new UserModel();
        $this->transaction_model = new TransactionModel();
    }

    public function executeAction() 
    {
        function exception_handler($exception) 
        {                
            // If the exception is a usernotloggedin, save the return URL and redirect them to the signin page
            if (get_class($exception) == "UserNotLoggedInException" && $exception->getMethod() == Method::GET)
                header('Location: /user/signin/null/100?return=' . $_SERVER['REQUEST_URI']);
            
            else if (get_parent_class($exception) == "BaseException" && $exception->getMethod() == Method::GET)
            {                
                error_log($exception);
                $viewmodel = $exception;
                $viewloc = 'views/error.php';
                require('views/main.php');
            }
            
            else
            {
                error_log($exception);
                echo $exception;
            }
        }   

        set_exception_handler('exception_handler'); 

        return $this->{$this->action}();
    }

    protected function returnView($viewmodel, $method) 
    {
        if ($method == Method::GET)
        {
            $viewloc = 'views/' . strtolower(get_class($this)) . '/' . $this->action . '.php'; // This view makes use of $viewmodel
            require('views/main.php');
        }
            
        else if ($method == Method::POST)
            echo $viewmodel;
    }
    
    protected function validateParameter($parameter_value, $parameter_name, $method, $callbacks)
    {
        if (is_array($parameter_value))
            $parameter_value = array_map('trim', $parameter_value);
        else
            $parameter_value = trim($parameter_value);
        
        foreach ($callbacks as $callback)
        {
            call_user_func($callback,$parameter_value,$parameter_name, $method);
        }
        
        return $parameter_value;
    } 
    
    protected function pushReturnURL($url)
    {
        $_SESSION["RETURN_URL"] = $url;
    }
    
    protected function popReturnURL()
    {
        $tmp = $_SESSION["RETURN_URL"];
        unset($_SESSION["RETURN_URL"]);
        return $tmp;
    }
}
?>