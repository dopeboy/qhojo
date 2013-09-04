<?php

abstract class BaseException extends Exception
{
    protected $user_id = null;
    protected $timestamp = null;
    protected $json = null;
    protected $method = null;
    protected $modal_id = null;
    
    public function __construct($message, $method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        $this->user_id = $user_id;
        $this->timestamp = new DateTime();
        $this->message = $message;
        $this->view = $method;
        $this->modal_id = $modal_id;
        $this->json = json_encode(array ("Error" => array("Message" => $this->message, "File" => $this->getFile(), "Line" => $this->getLine(), "User ID" => $this->user_id, "ModalID" => $this->modal_id, "Timestamp" => $this->timestamp)),JSON_PRETTY_PRINT);        
        parent::__construct($message, null, $previous);
    }     
    
    public function __toString()
    {
        if ($this->view == Method::GET)
        {
            $output = "##### START OF EXCEPTION ####";
            $output .= "<pre>" . print_r(json_decode($this->json),true) . "</pre>";
            $output .= "##### END OF EXCEPTION ####";        
            return $output;
        }
        
        else if ($this->view == Method::POST)
            return $this->json;
    }      
}

class InvalidLoginException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("Invalid email address or password. Please try again.", $method, $user_id, $previous);
    }    
}

class UserNotLoggedInException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("You must be signed in to commit that action.", $method, $user_id, $previous);
    }    
}

class UserAlreadyLoggedInException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("You cannot already be signed in and commit this action. Please sign out and try again.", $method, $user_id, $previous);
    }    
}

class UserWithEmailAlreadyExists extends BaseException  
{
    public function __construct($email, $method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("User with the following email already exists: " . $email, $method, $user_id, $previous);
    }    
}

class InvalidZipcodeException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("The supplied zipcode is invalid. Please try again.", $method, $user_id, $previous);
    }    
}

class InvalidEmailException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("The supplied email address is invalid.", $method, $user_id, $previous);
    }    
}

class RequiredParameterMissingException extends BaseException
{
    public function __construct($parameter_name, $method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("The following parameter was not specified or empty: " . $parameter_name, $method, $user_id, $previous);
    }     
}

class InvalidReviewAttemptException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("Either you weren't involved in the transaction or you've already reviewed it!", $method, $user_id, $previous);
    }     
}

class ReviewSubmissionFailureException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("Something went wrong with the review you just submitted. We'll take a look.", $method, $user_id, $previous);
    }     
}

class DatabaseException extends BaseException
{
    public function __construct($message, $method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct($message, $method, $user_id, $previous);
    }       
}

class InvalidDateException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("Invalid date supplied. Please try again.", $method, $user_id, $previous);
    }       
}

class InvalidNameException extends BaseException
{
    public function __construct($parameter_name, $method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("The following name field is invalid: " . $parameter_name, $method, $user_id, $previous);
    }     
}

class InvalidPassword extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("The supplied password is invalid. Please try again.", $method, $user_id, $previous);
    }     
}

class InvalidReviewRatingException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("An invalid review rating was supplied. Please try again.", $method, $user_id, $previous);
    }     
}

class UserHasAlreadyRequestedItemException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("You've already requested this item.", $method, $user_id, $previous);
    }     
}

class RequestInactiveItemException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("You can't request an inactive item.", $method, $user_id, $previous);
    }     
}

class RentalDurationExceedsLimitException extends BaseException
{
    public function __construct($max, $method, $user_id = 0, Exception $previous = null) 
    {
        parent::__construct("You can't request to rent an item for that long. Please choose a duration under " . $max . " days", $method, $user_id, $previous);
    }       
}

class RejectRequestException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("The request has already been rejected or withdrawn.", $method, $user_id, $previous, $modal_id);
    }       
}

class CancelRequestException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("The request has already been cancelled. Please cancel out of this window.", $method, $user_id, $previous, $modal_id);
    }       
}


?>