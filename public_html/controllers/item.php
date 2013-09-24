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
}

?>
