<?php

class Document extends Controller 
{    
    protected function index()
    {
        $this->returnView($this->item_model->getThreeLatestItems(), true,false);        
    }
}

?>
