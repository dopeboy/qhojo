<?php

class Picture extends Controller 
{
    protected function upload()
    {
        $viewmodel = new PictureModel();
     
        if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == null || $this->state == 0))
            $viewmodel->uploadItemPictures($method,$this->id, $_SESSION["USER"]["USER_ID"]);
        
        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 1))
            $viewmodel->uploadUserPictures($_SESSION["USER"]["USER_ID"]);
        
        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 2))
            $viewmodel->uploadDamagedItemPictures($method,$this->id, $_SESSION["USER"]["USER_ID"]);        
    }
    
    protected function delete()
    {
        $viewmodel = new PictureModel();
        
        if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == null || $this->state == 0))
            $viewmodel->deleteItemPicture($method,$this->id, $_SESSION["USER"]["USER_ID"]);
        
        else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 1))
            $viewmodel->deleteUserPicture($_SESSION["USER"]["USER_ID"]);
        
        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 2))
            $viewmodel->deleteDamagedItemPictures($method,$this->id, $_SESSION["USER"]["USER_ID"]);           
    }
//        
//    protected function upload()
//    {
//        $viewmodel = new PictureModel();
//        
//        if ($this->userid == null)
//        {
//            header('Location: /user/login/null/3');
//            exit;
//        }
//           
//        $this->returnView(null, false);
//    }
//    
//    protected function handler()
//    {
//        if ($this->userid == null)
//        {
//            header('Location: /user/login/null/3');
//            exit;
//        }
//        
//        if ($this->state == 0 && !isset($_SESSION['itemid']))
//        {
//            header('Location: /user/login/null/3');
//            exit;            
//        }
//        
//        $viewmodel = new PictureModel();
//        
//        $this->returnView($viewmodel->handler($_SESSION['itemid'], $this->id, $this->state, $this->userid, $this->postvalues['del'], $this->postvalues['file']),true);
//    }
}

?>
