<?php

class Picture extends Controller 
{
    protected function upload()
    {
        $viewmodel = new PictureModel();
     
        if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == null || $this->state == 0))
            $viewmodel->uploadItemPictures($method,$_SESSION["ITEM"]["ITEM_ID"], $_SESSION["USER"]["USER_ID"]);
        
        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 1))
            $viewmodel->uploadUserPictures($_SESSION["USER"]["USER_ID"]);
        
        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 2))
            $viewmodel->uploadDamagedItemPictures($method,$this->id, $_SESSION["USER"]["USER_ID"]);      
        
        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 3))
            $viewmodel->uploadProductPictures($method,$this->id, $_SESSION["USER"]["USER_ID"]);            
    }
    
    protected function delete()
    {
        $viewmodel = new PictureModel();
        
        if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == null || $this->state == 0))
            $viewmodel->deleteItemPicture($method,$_SESSION["ITEM"]["ITEM_ID"], $_SESSION["USER"]["USER_ID"]);
        
        else if (($method = Method::POST) && User::userSignedIn($method) && ($this->state == 1))
            $viewmodel->deleteUserPicture($_SESSION["USER"]["USER_ID"]);
        
        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 2))
            $viewmodel->deleteDamagedItemPictures($method,$this->id, $_SESSION["USER"]["USER_ID"]);     
        
        else if (($method = Method::GET) && User::userSignedIn($method) && ($this->state == 3))
            $viewmodel->deleteProductPicture($method,$this->id, $_SESSION["USER"]["USER_ID"]);           
    }
}

?>
