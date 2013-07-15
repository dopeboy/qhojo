<?php

class Picture extends Controller 
{
    protected function upload()
    {
        $viewmodel = new PictureModel();
        
        if ($this->userid == null)
        {
            header('Location: /user/login/null/3');
            exit;
        }
           
        $this->returnView(null, false,false);
    }
    
    protected function handler()
    {
        if ($this->userid == null)
        {
            header('Location: /user/login/null/3');
            exit;
        }
        
        if ($this->state == 0 && !isset($_SESSION['itemid']))
        {
            header('Location: /user/login/null/3');
            exit;            
        }
        
        $viewmodel = new PictureModel();
        
        $this->returnView($viewmodel->handler($_SESSION['itemid'], $this->id, $this->state, $this->userid, $this->postvalues['del'], $this->postvalues['file']), false,true);
    }
}

?>
