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
        private $log;

	//store the URL values on object creation
	public function __construct($urlvalues, $postvalues, $filevalues, $sessionvalues, $log) 
	{
            $this->urlvalues = $urlvalues;
            $this->postvalues = $postvalues;
            $this->filevalues = $filevalues;
            $this->sessionvalues = $sessionvalues;
            $this->log = $log;

            $this->controller = !isset($this->urlvalues['controller']) || trim($this->urlvalues['controller'])==='' ?  "document" :  $this->urlvalues['controller'];
            $this->action = !isset($this->urlvalues['action']) || trim($this->urlvalues['action'])==='' ?  "index" :  $this->urlvalues['action'];
            $this->id = !isset($this->urlvalues['id']) || trim($this->urlvalues['id'])==='' ?  "" :  $this->urlvalues['id'];
            $this->state = !isset($this->urlvalues['state']) || trim($this->urlvalues['state'])==='' ?  "" :  $this->urlvalues['state'];
            $this->userid = !isset($this->urlvalues['userid']) || trim($this->urlvalues['userid'])==='' ?  null :  $this->sessionvalues['userid'];     
            $this->admin = !isset($this->urlvalues['admin']) || trim($this->urlvalues['admin'])==='' ?  null :  $this->sessionvalues['admin'];

            $log->logInfo("controller: " . $this->controller);
            $log->logInfo("action: " . $this->action);		
            $log->logInfo("id: " . $this->id);	
            $log->logInfo("state: " . $this->state);	
            $log->logInfo("userid: " . $this->userid);
            $log->logInfo("admin: " . $this->admin);
	}

	//establish the requested controller as an object
	public function createController() 
	{
            return new $this->controller($this->action,$this->urlvalues, $this->postvalues, $this->filevalues, $this->id, $this->userid, $this->state, $this->admin);
	}
}

?>
