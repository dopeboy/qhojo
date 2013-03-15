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

	protected function reserve() 
	{
            if ($this->userid == null)
            {
                header('Location: /user/login/null/1');
                exit;
            }
            
            else
            {
                $viewmodel = new ItemModel();

                // view the reservation
                if ($this->state==0)
                {
                    $this->returnView($viewmodel->reserve($this->id), true,false);
                }

                // submit the reservation
                else if ($this->state==1)
                {
                    $this->returnView($viewmodel->submitReservation($this->id,$this->userid,$this->postvalues['duration'], $this->postvalues['message'], new UserModel()), false,true);        
                }

                // reservation submitted sucessfully
                else if ($this->state==2)
                {
                    $this->returnView($viewmodel->reservationComplete($this->id), true,false);
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

//	protected function returnItem() 
//	{
//		$item_model = new ItemModel();
//		$this->returnView($item_model->returnItem($this->postvalues['itemid']), false,true);
//	}
//
//	protected function receive() 
//	{
//		$item_model = new ItemModel();
//		$this->returnView($item_model->receive($this->postvalues['itemid']), false,true);
//	}

	protected function post() 
	{
            if ($this->userid == null)
            {
                header('Location: /user/login/null/3');
                exit;
            }
            
            $item_model = new ItemModel();
            
            if ($this->state == 0)
            {    
		$this->returnView($item_model->post($this->userid,new UserModel(),new LocationModel()), true,false);
            }
            
            else if ($this->state == 1)
            {
                $this->returnView($item_model->submitPost($this->userid,$this->postvalues['title'],$this->postvalues['rate'],$this->postvalues['deposit'],$this->postvalues['description'],$this->postvalues['locationid'],$this->filevalues), false,true);
            }
            
            else if ($this->state == 2)
            {
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
                $this->returnView($item_model->submitFeedback($this->userid,$this->postvalues['itemid'], $this->postvalues['rating']),false,true);
            
            else if ($this->state == 2)
                $this->returnView($item_model->feedbackComplete($this->id),true,false);
        }        
}

?>
