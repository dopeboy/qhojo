<?php

class Document extends Controller 
{    
    protected function index()
    {
        $method = Method::GET;
        $this->returnView($this->item_model->getThreeLatestItems($method), $method);        
    }
    
    protected function fees()
    {
        $method = Method::GET;
        $this->returnView(null, $method);        
    }    
    
    protected function contact()
    {
        $method = Method::GET;
        $this->returnView(null, $method);        
    }       
}

?>
