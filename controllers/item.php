<?php

class Item extends Controller 
{
	protected function index() 
	{
		$viewmodel = new ItemModel();
		$this->returnView($viewmodel->index($this->id), true,false);
	}

	protected function main() 
	{
		$viewmodel = new ItemModel();
		$this->returnView($viewmodel->main(null), true,false);
	}
        
        protected function test()
        {
		$viewmodel = new ItemModel();
		$this->returnView($viewmodel->test(), false,false);            
        }
        
	protected function search() 
	{
		$viewmodel = new ItemModel();
		$this->returnView($viewmodel->search($this->id), true,false);
	}

	protected function request() 
	{
            $usermodel = new UserModel();
            
            if ($this->userid == null)
            {
                header('Location: /user/login/null/2');
                exit;
            }
            
            else if ($usermodel->checkExtra($this->userid))
            {
                header('Location: /user/signup/null/2');
                $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
                exit;                
            }
            
            else
            {
                $viewmodel = new ItemModel();

                // view the request
                if ($this->state==0)
                {
                    $this->returnView($viewmodel->request($this->id), true,false);
                }

                // submit the request
                else if ($this->state==1)
                {
                    $this->returnView($viewmodel->submitRequest($this->id,$this->userid,$this->postvalues['duration'], $this->postvalues['message']), false,true);        
                }

                // request submitted sucessfully
                else if ($this->state==2)
                {
                    $this->returnView($viewmodel->requestComplete($this->id), true,false);
                }                 
            }                     
	}
        
        protected function confirm()
        {
            $viewmodel = new ItemModel();
            
            if ($this->state==0)
            {
                $this->returnView($viewmodel->borrowerConfirm($this->postvalues['Body'],$this->postvalues['From']), false,false);
                //$viewmodel->borrowerConfirm($this->postvalues['Body'],$this->postvalues['From']);
            }
            
            else if ($this->state==1)
                $this->returnView($viewmodel->lenderConfirm($this->postvalues['Body'],$this->postvalues['From']), false,false);
        }

	protected function post() 
	{
            $usermodel = new UserModel();
            $item_model = new ItemModel();
            
            if ($this->userid == null)
            {
                header('Location: /user/login/null/3');
                exit;
            }
            
            else if ($usermodel->checkExtra($this->userid))
            {
                header('Location: /user/signup/null/2');
                $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
                exit;                
            }            
            
            else  if ($this->state == 0)
            {    
                $_SESSION['itemid'] = getItemID();
		$this->returnView($item_model->post($this->userid,new UserModel(),new LocationModel()), true,false);
            }
            
            else if ($this->state == 1)
            {
                $this->returnView($item_model->submitPost($_SESSION['itemid'],$this->userid,$this->postvalues['title'],$this->postvalues['rate'],$this->postvalues['deposit'],$this->postvalues['description'],$this->postvalues['locationid'],$this->postvalues['file']), false,true);
            }
            
            else if ($this->state == 2)
            {
                $_SESSION['itemid'] = null;
                $this->returnView($item_model->postComplete($this->userid, $this->id), true, false);
            }
	}
        
        protected function feedback()
        {
            if ($this->userid == null)
            {
                header('Location: /user/login/null/2');
                exit;
            }
            
            $item_model = new ItemModel();
            
            if ($this->state == 0)
		$this->returnView($item_model->feedback($this->id), true,false);
            
            else if ($this->state == 1)
                $this->returnView($item_model->submitFeedback($this->userid,$this->postvalues['itemid'], $this->postvalues['rating'], $this->postvalues['comments']),false,true);
            
            else if ($this->state == 2)
                $this->returnView($item_model->feedbackComplete($this->id),true,false);
        }
        
        protected function accept()
        {
            if ($this->userid == null)
            {
                header('Location: /user/login/null/2');
                exit;
            }
            
            $item_model = new ItemModel();
            $this->returnView($item_model->accept($this->id), true, false);
        }
        
        protected function ignore()
        {
            if ($this->userid == null)
            {
                header('Location: /user/login/null/2');
                exit;
            }
            
            $item_model = new ItemModel();
            $this->returnView($item_model->ignore($this->id),false,true);
        }        
}

?>
