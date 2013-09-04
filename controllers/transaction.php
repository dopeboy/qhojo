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
    
}

?>
