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
            // TODO: use $demo to give a prettier error message for production. Use templated error pages
            error_log($exception);
            echo $exception;
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
        $parameter_value = trim($parameter_value);
        
        foreach ($callbacks as $callback)
        {
            call_user_func($callback,$parameter_value,$parameter_name, $method);
        }
        
        return $parameter_value;
    } 
}
?>
