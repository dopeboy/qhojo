<?php

class Loader 
{
	private $controller;
	private $action;
	private $urlvalues;
	private $postvalues;
        private $filevalues;
        private $sessionvalues;
	private $id;
        private $state;
	private $userid;
        private $admin;

	//store the URL values on object creation
	public function __construct($urlvalues, $postvalues, $filevalues, $sessionvalues) 
	{
		$this->urlvalues = $urlvalues;
		$this->postvalues = $postvalues;
                $this->filevalues = $filevalues;
                $this->sessionvalues = $sessionvalues;

                $this->controller = $this->IsNullOrEmptyString($this->urlvalues['controller']) == true ?  "item" :  $this->urlvalues['controller'];
                $this->action = $this->IsNullOrEmptyString($this->urlvalues['action']) == true ?  "search" :  $this->urlvalues['action'];
                $this->id = $this->IsNullOrEmptyString($this->urlvalues['id']) == true ?  "" :  $this->urlvalues['id'];
                $this->state = $this->IsNullOrEmptyString($this->urlvalues['state']) == true ?  "" :  $this->urlvalues['state'];
                $this->userid = $this->IsNullOrEmptyString($this->sessionvalues['userid']) == true ?  null :  $this->sessionvalues['userid'];            
		$this->admin = $this->IsNullOrEmptyString($this->sessionvalues['admin']) == true ?  null :  $this->sessionvalues['admin'];
                
		error_log("controller: " . $this->controller);
		error_log("action: " . $this->action);		
		error_log("id: " . $this->id);	
                error_log("state: " . $this->state);	
                error_log("userid: " . $this->userid);
                error_log("admin: " . $this->admin);
	}

	//establish the requested controller as an object
	public function createController() 
	{
		return new $this->controller($this->action,$this->urlvalues, $this->postvalues, $this->filevalues, $this->id, $this->userid, $this->state, $this->admin);
	}

        public function IsNullOrEmptyString($question)
        {
            return (!isset($question) || trim($question)==='');
        }
}

?>
