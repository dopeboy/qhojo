<?php

abstract class BaseException extends Exception
{
    protected $user_id = null;
    protected $timestamp = null;
    protected $json = null;
    protected $method = null;
    protected $modal_id = null;
    public $message = null;
    
    public function __construct($message, $method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        $this->user_id = $user_id;
        $this->timestamp = new DateTime();
        $this->message = $message;
        $this->method = $method;
        $this->view = $method;
        $this->modal_id = $modal_id;
        $this->json = json_encode(array ("Exception" => get_class($this), "Error" => array("Message" => $this->message, "File" => $this->getFile(), "Line" => $this->getLine(), "User ID" => $this->user_id, "ModalID" => $this->modal_id, "Timestamp" => $this->timestamp)),JSON_PRETTY_PRINT);        
        parent::__construct($message, null, $previous);
        
        global $demo;
        
        // If it's production, let's send an email to support@qhojo.com
        if ($demo == false)
        {
            global $support_email;
            $from = $to = $replyto = $support_email;
            $subject = "EXCEPTION - " . get_class($this);
            $message = $this->printMe();
            
            sendEmail($from, $to, $replyto, $subject, $message);
        }        
    }     
    
    private function printMe()
    {
        $output = "##### START OF EXCEPTION ####";
        $output .= "<pre>" . print_r(json_decode($this->json),true) . "</pre>";
        $output .= "##### END OF EXCEPTION ####";        
        return $output;        
    }
    
    public function __toString()
    {
        if ($this->view == Method::GET)
            return $this->printMe();
        
        else if ($this->view == Method::POST)
            return $this->json;
    }
    
    public function getMethod()
    {
        return $this->method;
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

class InvalidItemRentalRateException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("An invalid item rental rate was supplied. Please try again.", $method, $user_id, $previous, $modal_id);
    }       
}

class InvalidItemHoldValueException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("An invalid item hold value was supplied. Please try again.", $method, $user_id, $previous, $modal_id);
    }       
}

class UserCannotModifyItemException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("You are not the owner of the item and cannot modify it.", $method, $user_id, $previous, $modal_id);
    }       
}

class ItemSubmissionIssueException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("Something went wrong. Your item didn't get posted successfully. Try again.", $method, $user_id, $previous, $modal_id);
    }       
}

class PaypalCommunicationException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("Can't talk to PayPal. Not your fault. We're looking into it.", $method, $user_id, $previous, $modal_id);
    }       
}

class InvalidPaypalCredentialsException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("Your PayPal account either does not exist or is not verified. Please try again.", $method, $user_id, $previous, $modal_id);
    }       
}

class PhoneVerificationCodeException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("Unable to send text message to confirm phone number. Please wait 24 hours before trying again.", $method, $user_id, $previous, $modal_id);
    }       
}

class TwilioSendTextMessageException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("Issue with text message service.", $method, $user_id, $previous, $modal_id);
    }       
}

class PhoneVerificationInvalidCodeException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("The supplied verification code does not match the one sent by the system. ", $method, $user_id, $previous, $modal_id);
    }       
}

class PendingTransactionException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("The transaction could not be sent to the pending state because the user is not a lender of it or the transaction is already in the pending state.", $method, $user_id, $previous, $modal_id);
    }       
}

class InvalidEdgeException extends BaseException
{
    public function __construct($method, $user_id = 0, $state_a, $state_b, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("Invalid edge: " . $state_a . '->' . $state_b, $method, $user_id, $previous, $modal_id);
    }       
}

class InvalidPendingTransaction extends BaseException
{
    public function __construct($method, $user_id = 0, $transaction_id, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("This transaction is not in the pending state. Transaction ID: " . $transaction_id, $method, $user_id, $previous, $modal_id);
    }       
}

class AddCreditCardException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("Error adding your credit card to the system.", $method, $user_id, $previous, $modal_id);
    }       
}

class AcceptRequestException extends BaseException
{
    public function __construct($method, $user_id = 0, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("There was an error with accepting this transaction.", $method, $user_id, $previous, $modal_id);
    }       
}

class InvalidReservedTransaction extends BaseException
{
    public function __construct($method, $user_id = 0, $transaction_id, Exception $previous = null, $modal_id = null) 
    {
        parent::__construct("This transaction is not in the reserved state. Transaction ID: " . $transaction_id, $method, $user_id, $previous, $modal_id);
    }       
}
?>