<?php

class Transaction extends Controller 
{
    protected function review()
    {       
        if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == null || $this->state == 0))
            $this->returnView($this->transaction_model->review($method, $_SESSION["USER"]["USER_ID"], $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty'))), $method);
        
        else if (($method = Method::POST) && User::userSignedIn($method) && $this->state == 1)
        {
            $this->transaction_model->submitReview
            (
                    $method,
                    $_SESSION["USER"]["USER_ID"],
                    $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty')),
                    $this->validateParameter($this->postvalues['rating'],"Rating",$method,array('Validator::isNotNullAndNotEmpty', 'Validator::isValidReviewRating')),
                    $this->validateParameter($this->postvalues['comments'],"Comments",$method,array('Validator::isNotNullAndNotEmpty'))
            );
            
            $this->returnView(json_encode(array("URL" => "/transaction/review/" . $this->id . "/2")), $method);    
        }
        
        else if (($method = Method::GET) && User::userSignedIn($method) && $this->state == 2)
            $this->returnView($this->transaction_model->reviewSubmitted($method, $_SESSION["USER"]["USER_ID"], $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty'))), $method);
    }
    
    // 0 = withdraw by borrower, 1 = cancel by lender
    protected function reject()
    {
        if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 0 || $this->state == 1))
        {
            $this->transaction_model->rejectRequest
            (
                $method, 
                $_SESSION["USER"]["USER_ID"], 
                $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->state,"Source",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['reject-option'],"Reject Option",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['reason'],"Reject Reason",$method,array())                    
            );
            
            $this->returnView(json_encode(array("TransactionID" => $this->id, "Action" => "REJECT", "Source" => $this->state == 0 ? "borrowing" : "lending")), $method); 
        }
    }
    
    // 0 = Cancel by borrower, 1 = cancel by lender
    protected function cancel()
    {
        if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 0 || $this->state == 1))
        {
            $this->transaction_model->cancelRequest
            (
                $method, 
                $_SESSION["USER"]["USER_ID"], 
                $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->state,"Source",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['cancel-option'],"Cancel Option",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['reason'],"Cancel Reason",$method,array())                               
            );
            
            $this->returnView(json_encode(array("TransactionID" => $this->id, "Action" => "CANCEL", "Source" => $this->state == 0 ? "borrowing" : "lending")), $method); 
        }        
    } 
    
    protected function accept()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && $this->state == 0)
        {
//            if (lender fields not ready)
//                    redirect lender to extra signup page
//
//            else if (borrower fields not ready)
//                    explain to lender what is going on
//                    dispatch email
//                    move transaction to pending state
//
//            else
//                    move transaction to reserved state
//                    send emails
  
            $user_model = new UserModel();
            
            if (($status = $user_model->userNeedsExtraFields($_SESSION["USER"]["USER_ID"])))
            {
                //$this->pushReturnURL();
                header('Location: /user/extrasignup/null/0' . '?return=' . $_SERVER['REQUEST_URI']);
            }
            
            // Find the borrower ID on the current transaction
            else if (($status = $user_model->userNeedsExtraFields($this->transaction_model->getBorrowerOfRequest($method, $_SESSION["USER"]["USER_ID"], $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty'))))))
                header('Location: /transaction/pending/' . $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty')) . '/0');
            
            else
            {
                $this->transaction_model->acceptRequest
                (
                    $method, 
                    $_SESSION["USER"]["USER_ID"], 
                    $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty'))
                );     
                
                  header('Location: /transaction/accept/' . $this->id . '/1');
            }
        }
        
        else if (($method = Method::GET) && User::userSignedIn($method) && $this->state == 1)
        {
            $this->returnView($this->transaction_model->acceptRequestSuccess($method, $_SESSION["USER"]["USER_ID"], $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty'))), $method);  
        }            
    }
    
    protected function pending()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && $this->state == 0)
        {
            $this->transaction_model->pending
            (
                $method, 
                $_SESSION["USER"]["USER_ID"], 
                $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty'))
            );
            
            header('Location: /transaction/pending/' . $this->id . '/1');
        } 
        
        else if (($method = Method::GET) && User::userSignedIn($method) && $this->state == 1)
        {
            $this->returnView($this->transaction_model->pendingView($method, $_SESSION["USER"]["USER_ID"], $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty'))), $method); 
        }            
    }
    
    protected function borrowerconfirm()
    {
        if (($method = Method::POST) && $this->state==0 && $this->validateParameter($this->id,"ID",$method,array('Validator::isNotNullAndNotEmpty'))==8295106)
        {    
            $this->transaction_model->borrowerConfirm
            (
                $method,
                $this->validateParameter($this->postvalues['Body'],"Text Message Body",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['From'],"Text Message Phone Number",$method,array('Validator::isNotNullAndNotEmpty'))
            );

            $this->returnView("Great Success", $method);
        }
    }
    
    protected function lenderconfirm()
    {
        if (($method = Method::POST) && $this->state==0 && $this->validateParameter($this->id,"ID",$method,array('Validator::isNotNullAndNotEmpty'))==4856915)
        {    
            $this->transaction_model->lenderConfirm
            (
                $method,
                $this->validateParameter($this->postvalues['Body'],"Text Message Body",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['From'],"Text Message Phone Number",$method,array('Validator::isNotNullAndNotEmpty'))
            );

            $this->returnView("Great Success", $method);
        }
    }    
    
}

?>
