<?php

class TagModel extends Model 
{
    public function getAllTags()
    {
        $preparedStatement = $this->dbh->prepare('select * from TAG');
        $preparedStatement->execute();
        $rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

        return $rows;	        
    }
    
    public function getTagsForItem($itemid)
    {
        $sqlParameters[":itemid"] =  $itemid;
        $preparedStatement = $this->dbh->prepare('select TAG_NAME from TAG_VW where ITEM_ID=:itemid');
        $preparedStatement->execute($sqlParameters);
        $rows = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

        return $rows;	           
    }
}

?>
