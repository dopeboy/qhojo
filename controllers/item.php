<?php

class Item extends Controller 
{
	protected function index() 
	{
		$viewmodel = new ItemModel();
		$this->returnView($viewmodel->index($this->id, $this->userid), true,false);
	}

	protected function main() 
	{
		$viewmodel = new ItemModel();
		$this->returnView($viewmodel->main(null), true,false);
	}
        
        protected function test()
        {
		$viewmodel = new ItemModel();
               // $viewmodel->paypalDoReferenceTransaction(10,'B2d66A51794KM8357618');
                //$viewmodel->test();
                $this->returnView($viewmodel->test(), true,false);
        }
        
        protected function testest()
        {
		$viewmodel = new ItemModel();
                $this->returnView($viewmodel->testest($this->postvalues["uri"]), false,false);
		#$viewmodel->testest('B%2d95E05095G29611425');
               // $viewmodel->paypalDoReferenceTransaction(10,'B%2d95E05095G29611425');
        }
        
	protected function search() 
	{
            $viewmodel = new ItemModel();

            if ($this->state == 0)
                $this->returnView($viewmodel->searchForItem($this->id), true,false);
                
            else if ($this->state == 1)
                 $this->returnView($viewmodel->searchByLocation($this->id), true,false); // Neighborhood
            
            else if ($this->state == 2)
                $this->returnView($viewmodel->searchByBorough($this->id), true,false);
	}

	protected function request() 
	{
            $usermodel = new UserModel();
            
            if ($this->userid == null)
            {
                $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
                header('Location: /user/login/null/2');
                exit;
            }
            
            else if ($usermodel->checkExtraFields($this->userid))
            {
                header('Location: /user/signup/null/2');
                $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
                exit;                
            }
            
            else if ($usermodel->checkDebitMethod($this->userid))
            {
                header('Location: /user/signup/null/4');
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
            
            if ($this->state==0 && $this->id=8295106)
                $this->returnView($viewmodel->borrowerConfirm($this->postvalues['Body'],$this->postvalues['From']), false,false);
            
            else if ($this->state==1 && $this->id=4856915)
                $this->returnView($viewmodel->lenderConfirm($this->postvalues['Body'],$this->postvalues['From']), false,false);
        }

	protected function post() 
	{
            $usermodel = new UserModel();
            $item_model = new ItemModel();
            
            if ($this->userid == null)
            {
                header('Location: /user/login/null/3');
                $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
                exit;
            }
            
            else if ($usermodel->checkExtraFields($this->userid))
            {
                header('Location: /user/signup/null/2');
                $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
                exit;                
            }    
            
            else if ($usermodel->checkCreditMethod($this->userid))
            {
                header('Location: /user/signup/null/6');
                $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
                exit;                    
            }
            
            else  if ($this->state == 0)
            {    
                $_SESSION['itemid'] = getRandomID();
		$this->returnView($item_model->post($this->userid,new UserModel(),new LocationModel()), true,false);
            }
            
            else if ($this->state == 1)
            {
                $this->returnView($item_model->submitPost($_SESSION['itemid'],$this->userid,$this->postvalues['title'],$this->postvalues['rate'],$this->postvalues['deposit'],$this->postvalues['description'],$this->postvalues['locationid'],$this->postvalues['file']), false,true);
            }
            
            else if ($this->state == 2)
            {
                $_SESSION['itemid'] = null;
                $this->returnView($item_model->postComplete( $this->id), true, false);
            }
	}
        
        protected function feedback()
        {
            if ($this->userid == null)
            {
                $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
                header('Location: /user/login/null/2');
                exit;
            }
            
            // If the current user is the borrowoer && borrower_to_l fb is empty
            // else if the current user is the lender && lend_to_b fb is empty
            $item_model = new ItemModel();
            $item = $item_model->getItemDetails($this->id);
            
            if ($this->state == 0 && ($item["LENDER_ID"] == $this->userid && $item["LENDER_TO_BORROWER_STARS"] == null))
                $this->returnView($item_model->feedback($this->id), true,false);

            else if ($this->state == 1 && ($item["BORROWER_ID"] == $this->userid && $item["BORROWER_TO_LENDER_STARS"] == null))
                $this->returnView($item_model->feedback($this->id), true,false);

            else if ($this->state == 2)
                $this->returnView($item_model->submitFeedback($this->userid,$this->postvalues['itemid'], $this->postvalues['rating'], $this->postvalues['comments']),false,true);

            else if ($this->state == 3)
                $this->returnView($item_model->feedbackComplete($this->id),true,false);                
            
            else
                $this->returnView("Error", true,false);
        }
        
        protected function accept()
        {
            if ($this->userid == null)
            {
                header('Location: /user/login/null/2');
                exit;
            }
            
            $item_model = new ItemModel();
            
            if ($this->state == 0)
            {
                $this->returnView($item_model->submitAcceptance($this->id, $this->userid), false, true);
            }
            
            else if ($this->state == 1)
            {
                $this->returnView($item_model->acceptSuccess($this->id), true, false);
            }
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
        
        protected function delete()
        {
            if ($this->userid == null)
            {
                header('Location: /user/login/null/2');
                exit;
            }
            
            $item_model = new ItemModel();
            
            $item = $item_model->getItemDetails($this->id);
            if ($item['ITEM_STATE_ID'] != 0 || $this->userid != $item["LENDER_ID"])
            {
                header('Location: /document/error/');
                exit;                
            }
            
            if ($this->state == 0)
                $this->returnView($item_model->delete($this->id), true, false);

            else if ($this->state == 1)
                $this->returnView($item_model->deleteAction($this->id),false,true);
            
            else if ($this->state == 2)
                $this->returnView(null,true,false);
        }
        
        protected function chargedeposit()
        {
            if ($this->admin == 1 && $this->id != null)
            {
                $item_model = new ItemModel();
                
                if ($this->state == 0)
                    $this->returnView($item_model->chargeDeposit($this->id), true, false);
                else if ($this->state == 1)
                    $this->returnView($item_model->chargeDepositAction($this->id), false, true);       
                else if ($this->state == 2)
                    $this->returnView($item_model->chargeDepositComplete($this->id), true, false);       
            }
        }
}

?>
