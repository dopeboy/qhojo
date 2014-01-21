<?php

class Product extends Controller 
{  
    protected function getbrandsforcategory()
    {
        if (($method = Method::POST) && ($this->state == 0 || $this->state == null))
        {
            $rows = $this->product_model->getBrandsForCertainCategory
            (
                $method, 
                $_SESSION["USER"]["USER_ID"],
                $this->validateParameter($this->postvalues['category-id'],"Category ID",$method,array('Validator::isNotNullAndNotEmpty'))
            );

            $this->returnView(json_encode($rows), $method);
        }        
    }    
    
    protected function getproductsforcategoryandbrand()
    {
        if (($method = Method::POST) && ($this->state == 0 || $this->state == null))
        {
            $rows = $this->product_model->getProductsForCertainBrandAndCategory
            (
                $method, 
                $_SESSION["USER"]["USER_ID"],
                $this->validateParameter($this->postvalues['category-id'],"Category ID",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['brand-id'],"Brand ID",$method,array('Validator::isNotNullAndNotEmpty'))
            );

            $this->returnView(json_encode($rows), $method);
        }        
    }      
    
    protected function admin()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 0 || $this->state == null))
        {
            $this->returnView(null, $method);
        }        
    }
    
    protected function addcategory()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 0 || $this->state == null))
        {
            $this->returnView(null, $method);
        }      
        
        else if (($method = Method::GET) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 1))
        {
            $this->returnView($this->product_model->getCategory($method, $this->id), $method);
        }              
        
        else if (($method = Method::POST) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 2))
        {
            $this->product_model->submitCategory
            (
                $method, 
                $_SESSION["USER"]["USER_ID"],
                $this->validateParameter($this->postvalues['name'],"Category Name",$method,array('Validator::isNotNullAndNotEmpty'))
            );
            
            $this->returnView(json_encode(array("URL" => "/product/listcategories")), $method); 
        }          
        
        else if (($method = Method::POST) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 3))
        {
            $this->product_model->editCategory
            (
                $method, 
                $_SESSION["USER"]["USER_ID"],
                $this->validateParameter($this->postvalues['name'],"Category Name",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->id,"Category ID",$method,array('Validator::isNotNullAndNotEmpty'))                    
            );
            
            $this->returnView(json_encode(array("URL" => "/product/listcategories")), $method); 
        }          
    }    
    
    protected function listcategories()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 0 || $this->state == null))
        {
            $this->returnView($this->product_model->getAllCategories($method, $_SESSION["USER"]["USER_ID"]), $method);
        }        
    }     
    
    protected function addbrand()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 0 || $this->state == null))
        {
            $this->returnView(null, $method);
        }      
        
        else if (($method = Method::GET) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 1))
        {
            $this->returnView($this->product_model->getBrand($method, $this->id), $method);
        }              
        
        else if (($method = Method::POST) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 2))
        {
            $this->product_model->submitBrand
            (
                $method, 
                $_SESSION["USER"]["USER_ID"],
                $this->validateParameter($this->postvalues['name'],"Brand Name",$method,array('Validator::isNotNullAndNotEmpty'))
            );
            
            $this->returnView(json_encode(array("URL" => "/product/listbrands")), $method); 
        }          
        
        else if (($method = Method::POST) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 3))
        {
            $this->product_model->editBrand
            (
                $method, 
                $_SESSION["USER"]["USER_ID"],
                $this->validateParameter($this->postvalues['name'],"Brand Name",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->id,"Brand ID",$method,array('Validator::isNotNullAndNotEmpty'))                    
            );
            
            $this->returnView(json_encode(array("URL" => "/product/listbrands")), $method); 
        }          
    }    
    
    protected function listbrands()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 0 || $this->state == null))
        {
            $this->returnView($this->product_model->getAllBrands($method, $_SESSION["USER"]["USER_ID"]), $method);
        }        
    }  
    
    protected function addproduct()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 0 || $this->state == null))
        {
            $this->returnView($this->product_model->addProductView($method), $method);
        }      
        
        else if (($method = Method::GET) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 1))
        {
            $this->returnView($this->product_model->getProduct($method, $this->id), $method);
        }              
        
        else if (($method = Method::POST) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 2))
        {
            $this->product_model->submitProduct
            (
                $method, 
                $_SESSION["USER"]["USER_ID"],
                $this->validateParameter($this->postvalues['product_id'],"Product ID",$method,array('Validator::isNotNullAndNotEmpty')),                    
                $this->validateParameter($this->postvalues['cat-id'],"Category ID",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['brand-id'],"Product Name",$method,array('Validator::isNotNullAndNotEmpty')),                                        
                $this->validateParameter($this->postvalues['name'],"Product Name",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['description'],"Product Desc",$method,array('Validator::isNotNullAndNotEmpty')),                    
                $this->validateParameter($this->postvalues['value'],"Product Value",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['rate'],"Product Rate",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['url'],"Product URL",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['file'],"Product Pictures",$method,array('Validator::isNotNullAndNotEmpty'))                    
            );
            
            $this->returnView(json_encode(array("URL" => "/product/listproducts")), $method); 
        }          
        
        else if (($method = Method::POST) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 3))
        {
            $this->product_model->editProduct
            (
                $method, 
                $_SESSION["USER"]["USER_ID"],
                $this->validateParameter($this->id,"Product ID",$method,array('Validator::isNotNullAndNotEmpty')),                    
                $this->validateParameter($this->postvalues['cat-id'],"Category ID",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['brand-id'],"Product Name",$method,array('Validator::isNotNullAndNotEmpty')),                                        
                $this->validateParameter($this->postvalues['name'],"Product Name",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['description'],"Product Desc",$method,array('Validator::isNotNullAndNotEmpty')),                    
                $this->validateParameter($this->postvalues['value'],"Product Value",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['rate'],"Product Rate",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['url'],"Product URL",$method,array('Validator::isNotNullAndNotEmpty')),
                $this->validateParameter($this->postvalues['file'],"Product Pictures",$method,array('Validator::isNotNullAndNotEmpty'))                    
            );
            
            $this->returnView(json_encode(array("URL" => "/product/listproducts")), $method); 
        }          
    }    
    
    protected function listproducts()
    {
        if (($method = Method::GET) && User::userSignedIn($method) && User::userIsAdmin($method) && ($this->state == 0 || $this->state == null))
        {
            $this->returnView($this->product_model->getAllProducts($method, $_SESSION["USER"]["USER_ID"]), $method);
        }        
    }    
}

?>
