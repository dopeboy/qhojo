<?php

class Item extends Controller 
{
    protected function search() 
    {
        $method = Method::GET;
        User::userSignedIn($method);
        
        $this->returnView($this->item_model->search
        (
            $method,                    
            !empty($this->urlvalues['query']) ? trim($this->urlvalues['query']) : null,
            !empty($this->urlvalues['location']) ? trim($this->urlvalues['location']) : null,
            !empty($this->urlvalues['user_id']) ? trim($this->urlvalues['user_id']) : null,
            !empty($this->urlvalues['page']) ? trim($this->urlvalues['page']) : null,
            $_SESSION["USER"]["USER_ID"]
        ),
        $method);
    }

    protected function index() 
    {
        $method = Method::GET;
        User::userSignedIn($method);
        $this->returnView($this->item_model->index($method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : null, $this->validateParameter($this->id,"Item ID",$method,array('Validator::isNotNullAndNotEmpty'))),$method);
    } 

    protected function post()
    {
        // 0
        if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == null || $this->state == 0))
        {
            $this->returnView($this->item_model->prepost($method, $_SESSION["USER"]["USER_ID"]), $method);            
        }
        
        // 1
        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 1))
        {
            $_SESSION["ITEM"]["ITEM_ID"] = $this->item_model->getUnusedRandomID($method);
            $this->returnView($this->item_model->post($method, $_SESSION["USER"]["USER_ID"], $_SESSION["ITEM"]["ITEM_ID"], isset($this->urlvalues["product-id"]) ? $this->urlvalues["product-id"] : null), $method); 
        }
         
        // 2
        else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 2))
        {
            $this->item_model->submitPost
            (
                $method, 
                $_SESSION["USER"]["USER_ID"],
                $_SESSION["ITEM"]["ITEM_ID"],
                $this->validateParameter($this->postvalues['title'],"Item Title",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['description'],"Item Description",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['hold-amount'],"Hold Amount",$method,array('Validator::isNotNullAndNotEmpty', 'Validator::isValidItemHoldValue')),
                $this->validateParameter($this->postvalues['borrow-rate'],"Rental Rate",$method,array('Validator::isNotNullAndNotEmpty', 'Validator::isValidItemRentalRate')),
                $this->validateParameter($this->postvalues['location'],"Item Zipcode",$method,array('Validator::isNotNullAndNotEmpty', 'Validator::isValidZipcode')),
                $this->validateParameter($this->postvalues['file'],"Item Pictures",$method,array('Validator::isNotNullAndNotEmpty')),
                isset($this->postvalues["product-id"]) ? $this->postvalues["product-id"] : null                    
            );
            
            // Clear ITEM_ID in session
            $item_id = $_SESSION["ITEM"]["ITEM_ID"];
            unset($_SESSION["ITEM"]["ITEM_ID"]);
            
            $this->returnView(json_encode(array("URL" => "/item/post/{$item_id}/3")), $method); 
        }

        // 3
        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 3))
            $this->returnView($this->item_model->postSubmitted($method, $this->validateParameter($this->id,"Item ID",$method,array('Validator::isNotNullAndNotEmpty')), $_SESSION["USER"]["USER_ID"]), $method);             
    }
    
    protected function deactivate()
    {
        if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 0))
        {
            $this->item_model->deactivateItem
            (
                $method, 
                $_SESSION["USER"]["USER_ID"],
                $this->validateParameter($this->id,"Item ID",$method,array('Validator::isNotNullAndNotEmpty'))
            );

            $this->returnView(json_encode(array("Status" => "SUCCESS", "Action" => "DEACTIVATE", "ItemID" => $this->id)), $method);
        }        
    }
    
    protected function activate()
    {
        if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 0))
        {
            $this->item_model->activateItem
            (
                $method, 
                $_SESSION["USER"]["USER_ID"],
                $this->validateParameter($this->id,"Item ID",$method,array('Validator::isNotNullAndNotEmpty'))
            );

            $this->returnView(json_encode(array("Status" => "SUCCESS", "Action" => "ACTIVATE", "ItemID" => $this->id)), $method);
        }        
    }    
}

?>
