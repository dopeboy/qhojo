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
                    $this->validateParameter($this->postvalues['comments'],"Comments",$method,array('Validator::isNotNullAndNotEmpty', 'Validator::isValidReviewComment'))
            );
            
            $this->returnView(json_encode(array("URL" => "/transaction/review/" . $this->id . "/2")), $method);    
        }
        
        else if (($method = Method::GET) && User::userSignedIn($method) && $this->state == 2)
            $this->returnView($this->transaction_model->reviewSubmitted($method, $_SESSION["USER"]["USER_ID"], $this->validateParameter($this->id,"Transaction ID",$method,array('Validator::isNotNullAndNotEmpty'))), $method);
    }
}

?>
