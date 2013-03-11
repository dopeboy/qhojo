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

	//store the URL values on object creation
	public function __construct($urlvalues, $postvalues, $filevalues, $sessionvalues) 
	{
		$this->urlvalues = $urlvalues;
		$this->postvalues = $postvalues;
                $this->filevalues = $filevalues;
                $this->sessionvalues = $sessionvalues;

                $this->controller = $this->IsNullOrEmptyString($this->urlvalues['controller']) == true ?  "item" :  $this->urlvalues['controller'];
                $this->action = $this->IsNullOrEmptyString($this->urlvalues['action']) == true ?  "main" :  $this->urlvalues['action'];
                $this->id = $this->IsNullOrEmptyString($this->urlvalues['id']) == true ?  "-1" :  $this->urlvalues['id'];
                $this->state = $this->IsNullOrEmptyString($this->urlvalues['state']) == true ?  "-1" :  $this->urlvalues['state'];
                $this->userid = $this->IsNullOrEmptyString($this->sessionvalues['userid']) == true ?  null :  $this->sessionvalues['userid'];            
		
		error_log("controller: " . $this->controller);
		error_log("action: " . $this->action);		
		error_log("id: " . $this->id);	
                error_log("state: " . $this->state);	
                error_log("userid: " . $this->userid);
	}

	//establish the requested controller as an object
	public function createController() 
	{
		return new $this->controller($this->action,$this->urlvalues, $this->postvalues, $this->filevalues, $this->id, $this->userid, $this->state);
	}

        public function IsNullOrEmptyString($question)
        {
            return (!isset($question) || trim($question)==='');
        }
}

?>
