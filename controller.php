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

	public function __construct($action, $urlvalues, $postvalues, $filevalues, $id, $userid, $state) 
	{
		$this->action = $action;
		$this->urlvalues = $urlvalues;
		$this->postvalues = $postvalues;
                $this->filevalues = $filevalues;
		$this->id = $id;
		$this->userid = $userid;
                $this->state = $state;
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
			{
                            // If user is logged in 
                            if ($this->userid != null)
                            {
                                $user = new UserModel();
                                $cc = $user->getRequestCount($this->userid);
                            }
				
                            require('views/maintemplate.php');
			} 

			else 
			{
				require($viewloc);
			}
		}
	}
}
?>
