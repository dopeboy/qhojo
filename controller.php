<?php

abstract class Controller 
{
    protected $urlvalues;
    protected $postvalues;
    protected $filevalues;

    protected $action;
    protected $id;
    protected $userid;
    protected $state;
    protected $admin;

    protected $item_model;
    protected $user_model;
    
    public function __construct($action, $urlvalues, $postvalues, $filevalues, $id, $userid, $state, $admin) 
    {
        $this->action = $action;
        $this->urlvalues = $urlvalues;
        $this->postvalues = $postvalues;
        $this->filevalues = $filevalues;
        $this->id = $id;
        $this->userid = $userid;
        $this->state = $state;
        $this->admin = $admin;
        
        $this->item_model = new ItemModel();
        $this->user_model = new UserModel();
    }

    public function executeAction() 
    {
        return $this->{$this->action}();
    }

    protected function returnView($viewmodel, $fullview, $ajaxcall) 
    {
        if ($ajaxcall)
            require('views/echo.php');

        else
        {
            $viewloc = 'views/' . strtolower(get_class($this)) . '/' . $this->action . '.php';

            if ($fullview) 
                require('views/main.php');

            else 
                require($viewloc);
        }
    }
}
?>
