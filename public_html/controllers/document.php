<?php

class Document extends Controller 
{    
    protected function splash()
    {
        // If the user is already signed in, take them to the index
        if (User::isUserSignedIn())
        {
            $method = Method::GET;
            header('Location: /document/index'); 
        }   
        
        else
        {
            $method = Method::NAKED;
            $this->returnView(null, $method);           
        }
    }
    
    protected function index()
    {
        $method = Method::GET;
        User::userSignedIn($method);
        $this->returnView($this->item_model->getThreeLatestItems($method), $method);        
    }
    
    protected function fees()
    {
        $method = Method::GET;
        User::userSignedIn($method);
        $this->returnView(null, $method);        
    }    
    
    protected function contact()
    {
        $method = Method::GET;
        User::userSignedIn($method);
        $this->returnView(null, $method);        
    }   
    
    protected function about()
    {
        $method = Method::GET;
        $this->returnView(null, $method);        
    }         
    
    protected function howitworks()
    {
        $method = Method::GET;
        $this->returnView(null, $method);        
    }    
    
    protected function legal()
    {
        $method = Method::GET;
        User::userSignedIn($method);
        $this->returnView(null, $method);        
    }       
}

?>
