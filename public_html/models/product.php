<?php

class ProductModel extends Model 
{  
    public function getCategories($method)
    {
        $preparedStatement = $this->dbh->prepare('SELECT * FROM CATEGORY_VW ORDER BY DISPLAY_ORDER');
        $preparedStatement->execute();
        return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    }    
    
    public function getBrands($method)
    {
        $preparedStatement = $this->dbh->prepare('SELECT * FROM BRAND_VW ORDER BY NAME');
        $preparedStatement->execute();
        return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    }    
    
    public function getProducts($method)
    {
        $preparedStatement = $this->dbh->prepare('SELECT * FROM PRODUCT_VW ORDER BY PRODUCT_NAME');
        $preparedStatement->execute();
        return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    }        
    
    public function getBrandsForCertainCategory($method, $user_id, $category_id)
    {
        $sqlParameters[":category_id"] =  $category_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM BRAND_VW WHERE BRAND_ID in (SELECT DISTINCT BRAND_ID FROM PRODUCT_VW WHERE CATEGORY_ID=:category_id) ORDER BY NAME');
        $preparedStatement->execute($sqlParameters);
        return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    }      
    
    public function getProductsForCertainBrandAndCategory($method, $user_id, $category_id, $brand_id)
    {
        $sqlParameters[":category_id"] =  $category_id;
        $sqlParameters[":brand_id"] =  $brand_id;
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM PRODUCT_VW WHERE CATEGORY_ID=:category_id AND BRAND_ID=:brand_id ORDER BY PRODUCT_NAME');
        $preparedStatement->execute($sqlParameters);
        return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    }           
    
    public function submitCategory($method, $user_id, $category_name)
    {
        $sqlParameters[":id"] = getRandomID();
        $sqlParameters[":name"] =  $category_name;
        $sqlParameters[":displayorder"] =  null;
        $sqlParameters[":user_id"] =  $user_id;
        $sqlParameters[":date_created"] =  date("Y-m-d H:i:s");;
        $sqlParameters[":active"] =  1;
        
        $preparedStatement = $this->dbh->prepare('INSERT INTO CATEGORY (ID, NAME, DISPLAY_ORDER, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE) VALUES (:id, :name, :displayorder, :user_id, :date_created, :active)');
        $preparedStatement->execute($sqlParameters);
    }
    
    public function editCategory($method, $user_id, $category_name, $category_id)
    {
        $sqlParameters[":id"] = $category_id;
        $sqlParameters[":name"] =  $category_name;
        
        $preparedStatement = $this->dbh->prepare('UPDATE CATEGORY SET NAME=:name WHERE ID=:id');
        $preparedStatement->execute($sqlParameters);
    }    
    
    public function getAllCategories($method)
    {
        $preparedStatement = $this->dbh->prepare('SELECT * FROM CATEGORY ORDER BY DATE_CREATED');
        $preparedStatement->execute();
        return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    }   
    
    public function getCategory($method, $category_id)
    {
        $sqlParameters[":id"] = $category_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM CATEGORY WHERE ID=:id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        return $preparedStatement->fetch(PDO::FETCH_ASSOC);
    }     
    
    public function submitBrand($method, $user_id, $brand_name)
    {
        $sqlParameters[":id"] = getRandomID();
        $sqlParameters[":name"] =  $brand_name;
        $sqlParameters[":displayorder"] =  null;
        $sqlParameters[":user_id"] =  $user_id;
        $sqlParameters[":date_created"] =  date("Y-m-d H:i:s");;
        $sqlParameters[":active"] =  1;
        
        $preparedStatement = $this->dbh->prepare('INSERT INTO BRAND (ID, NAME, DISPLAY_ORDER, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE) VALUES (:id, :name, :displayorder, :user_id, :date_created, :active)');
        $preparedStatement->execute($sqlParameters);
    }
    
    public function editBrand($method, $user_id, $brand_name, $brand_id)
    {
        $sqlParameters[":id"] = $brand_id;
        $sqlParameters[":name"] =  $brand_name;
        
        $preparedStatement = $this->dbh->prepare('UPDATE BRAND SET NAME=:name WHERE ID=:id');
        $preparedStatement->execute($sqlParameters);
    }    
    
    public function getAllBrands($method)
    {
        $preparedStatement = $this->dbh->prepare('SELECT * FROM BRAND ORDER BY DATE_CREATED');
        $preparedStatement->execute();
        return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    }   
    
    public function getBrand($method, $brand_id)
    {
        $sqlParameters[":id"] = $brand_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM BRAND WHERE ID=:id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        return $preparedStatement->fetch(PDO::FETCH_ASSOC);
    }   
    
    public function addProductView($method)
    {
        $preparedStatement = $this->dbh->prepare('SELECT * FROM CATEGORY_VW');
        $preparedStatement->execute();
        $row["CATEGORY"] =  $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM BRAND_VW');
        $preparedStatement->execute();
        $row["BRAND"] =  $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;
    }       
                        
    public function submitProduct($method, $user_id, $product_id, $category_id, $brand_id, $name, $description, $value, $rate, $url, $files)
    {
        $sqlParameters[":id"] = $product_id;
        $sqlParameters[":cat_id"] = $category_id;
        $sqlParameters[":brand_id"] = $brand_id;
        $sqlParameters[":name"] =  $name;
        $sqlParameters[":description"] =  $description;
        $sqlParameters[":value"] =  $value;
        $sqlParameters[":rate"] =  $rate;
        $sqlParameters[":url"] =  $url;
        $sqlParameters[":displayorder"] =  null;
        $sqlParameters[":user_id"] =  $user_id;
        $sqlParameters[":date_created"] =  date("Y-m-d H:i:s");
        $sqlParameters[":active"] =  1;
        
        $preparedStatement = $this->dbh->prepare('INSERT INTO PRODUCT (ID, CATEGORY_ID, BRAND_ID, NAME, DESCRIPTION, VALUE, RATE, URL, DISPLAY_ORDER, CREATED_BY_USER_ID, DATE_CREATED, ACTIVE) VALUES (:id, :cat_id, :brand_id, :name, :description, :value, :rate, :url, :displayorder, :user_id, :date_created, :active)');
        $preparedStatement->execute($sqlParameters);
        
        // Handle the files
        $sqlParameters[] = array();
        $datafields = array('ID' => '', 'PRODUCT_ID' => '', 'FILENAME' => '', 'PRIMARY_FLAG' => '', 'CREATED_BY_USER_ID' => '', "DATE_CREATED" => '', 'ACTIVE' => '');

        foreach ($files as $key=>$file)
        {
            $data[] = array('ID' => getRandomID(), 'PRODUCT_ID' => $product_id, 'FILENAME' => $file, 'PRIMARY_FLAG' => $key == 0 ? 1 : 0, 'CREATED_BY_USER_ID' => $user_id, 'DATE_CREATED' => date("Y-m-d H:i:s"), 'ACTIVE' => 1);
        }

        $insert_values = array();
        foreach($data as $d)
        {
            $question_marks[] = '('  . $this->placeholders('?', sizeof($d)) . ')';
            $insert_values = array_merge($insert_values, array_values($d));
        }

        $sql = "INSERT INTO PRODUCT_IMAGE (" . implode(",", array_keys($datafields) ) . ") VALUES " . implode(',', $question_marks);

        $this->dbh->beginTransaction();
        $stmt = $this->dbh->prepare ($sql);
        $stmt->execute($insert_values);
        $this->dbh->commit();          
    }
    
    public function editProduct($method, $user_id, $product_id, $category_id, $brand_id, $name, $description, $value, $rate, $url, $files)
    {
        $sqlParameters[":id"] = $product_id;
        $sqlParameters[":cat_id"] = $category_id;
        $sqlParameters[":brand_id"] = $brand_id;
        $sqlParameters[":name"] =  $name;
        $sqlParameters[":description"] =  $description;
        $sqlParameters[":value"] =  $value;
        $sqlParameters[":rate"] =  $rate;
        $sqlParameters[":url"] =  $url;

        $preparedStatement = $this->dbh->prepare('UPDATE PRODUCT SET CATEGORY_ID=:cat_id, BRAND_ID=:brand_id, NAME=:name, DESCRIPTION=:description, VALUE=:value, RATE=:rate, URL=:url WHERE ID=:id');
        $preparedStatement->execute($sqlParameters);
        
        // Let's deal with the pictures now
        // We're going to invalidate all the current active pictures for this product. 
        $sqlParameters = array();
        $sqlParameters[":product_id"] = $product_id;
        $preparedStatement = $this->dbh->prepare('UPDATE PRODUCT_IMAGE SET ACTIVE=0 WHERE PRODUCT_ID=:product_id');
        $preparedStatement->execute($sqlParameters);
        
        // Then we're going to insert all the incoming pictures
        $sqlParameters[] = array();
        $datafields = array('ID' => '', 'PRODUCT_ID' => '', 'FILENAME' => '', 'PRIMARY_FLAG' => '', 'CREATED_BY_USER_ID' => '', "DATE_CREATED" => '', 'ACTIVE' => '');

        foreach ($files as $key=>$file)
        {
            $data[] = array('ID' => getRandomID(), 'PRODUCT_ID' => $product_id, 'FILENAME' => $file, 'PRIMARY_FLAG' => $key == 0 ? 1 : 0, 'CREATED_BY_USER_ID' => $user_id, 'DATE_CREATED' => date("Y-m-d H:i:s"), 'ACTIVE' => 1);
        }

        $insert_values = array();
        foreach($data as $d)
        {
            $question_marks[] = '('  . $this->placeholders('?', sizeof($d)) . ')';
            $insert_values = array_merge($insert_values, array_values($d));
        }

        $sql = "INSERT INTO PRODUCT_IMAGE (" . implode(",", array_keys($datafields) ) . ") VALUES " . implode(',', $question_marks);

        $this->dbh->beginTransaction();
        $stmt = $this->dbh->prepare ($sql);
        $stmt->execute($insert_values);
        $this->dbh->commit();                  
    }    
    
    public function getAllProducts($method)
    {
        $preparedStatement = $this->dbh->prepare('SELECT p.*, b.NAME as BRAND_NAME, c.NAME as CATEGORY_NAME FROM PRODUCT p INNER JOIN BRAND b on p.BRAND_ID=b.ID INNER JOIN CATEGORY c on p.CATEGORY_ID=c.ID ORDER BY DATE_CREATED');
        $preparedStatement->execute();
        return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    }   
    
    public function getProduct($method, $brand_id)
    {
        $sqlParameters[":id"] = $brand_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM PRODUCT WHERE ID=:id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM CATEGORY_VW');
        $preparedStatement->execute();
        $row["CATEGORY"] =  $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        $preparedStatement = $this->dbh->prepare('SELECT * FROM BRAND_VW');
        $preparedStatement->execute();
        $row["BRAND"] =  $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;        
    }    
    
    public function getActiveProductByID($method, $product_id)
    {
        $sqlParameters[":id"] = $product_id;
        $preparedStatement = $this->dbh->prepare('SELECT * FROM PRODUCT_VW WHERE PRODUCT_ID=:id LIMIT 1');
        $preparedStatement->execute($sqlParameters);
        return $preparedStatement->fetch(PDO::FETCH_ASSOC);        
    }
    
    private function placeholders($text, $count=0, $separator=",")
    {
        $result = array();
        if($count > 0){
            for($x=0; $x<$count; $x++){
                $result[] = $text;
            }
        }

        return implode($separator, $result);
    }        
}

?>
