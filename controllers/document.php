<?php

class Document extends Controller 
{    
    protected function index()
    {
        $view = true;
        $this->returnView($this->item_model->getThreeLatestItems($view), $view);        
    }
}

?>
