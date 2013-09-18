<?php

class Item extends Controller 
{
	protected function search() 
	{
            $method = Method::GET;
            
            $this->returnView($this->item_model->search
            (
                $method,                    
                !empty($this->urlvalues['query']) ? trim($this->urlvalues['query']) : null,
                !empty($this->urlvalues['location']) ? trim($this->urlvalues['location']) : null,
                !empty($this->urlvalues['user_id']) ? trim($this->urlvalues['user_id']) : null,
                !empty($this->urlvalues['page']) ? trim($this->urlvalues['page']) : null
            ),
            $method);
	}
        
        protected function index() 
	{
            $method = Method::GET;
            $this->returnView($this->item_model->index($method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : null, $this->validateParameter($this->id,"Item ID",$method,array('Validator::isNotNullAndNotEmpty'))),$method);
	}
        
	protected function request() 
	{
            if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == null || $this->state == 0))
                $this->returnView($this->item_model->request($method, $_SESSION["USER"]["USER_ID"], $this->validateParameter($this->id,'Item ID',$method,array('Validator::isNotNullAndNotEmpty'))), $method);
            
            else if (($method = Method::POST) && User::userSignedIn($method) && $this->state == 1)
            {
                $this->item_model->submitRequest
                (
                    $method, 
                    $this->validateParameter($this->id,"Item ID",$method,array('Validator::isNotNullAndNotEmpty')),
                    $_SESSION["USER"]["USER_ID"],
                    $this->validateParameter($this->postvalues['date-start'],"Start Date",$method,array('Validator::isNotNullAndNotEmpty', 'Validator::isValidDate')),
                    $this->validateParameter($this->postvalues['date-end'],"End Date",$method,array('Validator::isNotNullAndNotEmpty', 'Validator::isValidDate')),
                    $this->validateParameter($this->postvalues['message'],"Message",$method,array('Validator::isNotNullAndNotEmpty'))
                );    
                
                $this->returnView(json_encode(array("URL" => "/item/request/" . $this->id . "/2")), $method);    
            }
            
            else if (($method = Method::GET) && User::userSignedIn($method) && $this->state == 2)
                $this->returnView($this->item_model->requestSubmitted($method, $this->validateParameter($this->id,"Item ID",$method,array('Validator::isNotNullAndNotEmpty')), $_SESSION["USER"]["USER_ID"]), $method); 
        } 
        
        protected function post()
        {
            if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == null || $this->state == 0))
            {
                $this->returnView($this->item_model->post($method, $_SESSION["USER"]["USER_ID"]), $method);            
            }
            
            // [title] => dsf\n    [hold] => 12\n    [rate] => 12\n    [location] => 12345\n    [zipcode] => 12345\n
            else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 1))
            {
                $this->item_model->submitPost
                (
                    $method, 
                    $_SESSION["USER"]["USER_ID"],
                    $this->validateParameter($this->postvalues['item_id'],"Item ID",$method,array('Validator::isNotNullAndNotEmpty')),
                    $this->validateParameter($this->postvalues['title'],"Item Title",$method,array('Validator::isNotNullAndNotEmpty')),
                    $this->validateParameter($this->postvalues['description'],"Item Description",$method,array('Validator::isNotNullAndNotEmpty')),
                    $this->validateParameter($this->postvalues['hold'],"Hold Amount",$method,array('Validator::isNotNullAndNotEmpty', 'Validator::isValidItemHoldValue')),
                    $this->validateParameter($this->postvalues['rate'],"Rental Rate",$method,array('Validator::isNotNullAndNotEmpty', 'Validator::isValidItemRentalRate')),
                    $this->validateParameter($this->postvalues['location'],"Item Zipcode",$method,array('Validator::isNotNullAndNotEmpty', 'Validator::isValidZipcode')),
                    $this->validateParameter($this->postvalues['file'],"Item Pictures",$method,array('Validator::isNotNullAndNotEmpty'))
                );
                
                $this->returnView(json_encode(array("URL" => "/item/post/" . $this->postvalues['item_id'] . "/2")), $method);
            }
            
            else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 2))
                $this->returnView($this->item_model->postSubmitted($method, $this->validateParameter($this->id,"Item ID",$method,array('Validator::isNotNullAndNotEmpty')), $_SESSION["USER"]["USER_ID"]), $method);             
        }
        
        
       
        
	/*protected function index() 
	{
		$viewmodel = new ItemModel();
		$this->returnView($viewmodel->index($this->id, $this->userid), true,false);
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
              //  $this->returnView($viewmodel->testest($this->postvalues["uri"]), false,false);
		//$viewmodel->testest('B%2d95E05095G29611425', false, true);
                $this->returnView($viewmodel->testest(), false,true);
               // $viewmodel->paypalDoReferenceTransaction(10,'B%2d95E05095G29611425');
        }
        
	protected function search() 
	{
            $viewmodel = new ItemModel();
            $this->returnView($viewmodel->search
            (
                (isset($this->urlvalues['query'])) ? $this->urlvalues['query'] : null,
                (isset($this->urlvalues['borough'])) ? $this->urlvalues['borough'] : null,
                (isset($this->urlvalues['neighborhood'])) ? $this->urlvalues['neighborhood'] : null,
                (isset($this->urlvalues['tag'])) ? $this->urlvalues['tag'] : null
            ),
            true,false);
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
                $this->returnView($item_model->submitPost($_SESSION['itemid'],$this->userid,$this->postvalues['title'],$this->postvalues['rate'],$this->postvalues['deposit'],$this->postvalues['description'],$this->postvalues['locationid'],$this->postvalues['file'], $this->postvalues['tags']), false,true);
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
            {
                header('Location: /document/error');
                exit;                
            }
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
        }*/
}

?>
