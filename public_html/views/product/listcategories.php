<?php

?>

<div class="sheet">
    <legend>All Categories</legend>
    
    <table class="table table-bordered table-striped" style="">
        <thead>
            <tr>
                <th>Name</th>
                <th>Display Order</th>
                <th>Created by User ID</th>
                <th>Dated Created</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            <?php 

            foreach($viewmodel as $category) 
            {     
            ?>
            <tr id="<?php echo $category['ID']; ?>">
                <td>
                    <a href="/product/addcategory/<?php echo $category['ID']; ?>/1"><?php echo $category["NAME"]; ?></a> 
                </td>
                <td>
                    <?php echo $category["DISPLAY_ORDER"]; ?>  
                </td>
                <td>
                    <?php echo $category["CREATED_BY_USER_ID"]; ?>  
                </td>
                <td>
                    <?php echo $category["DATE_CREATED"]; ?>  
                 </td>                 
                <td>
                    <?php echo $category["ACTIVE"]; ?>  
                 </td>                      
            </tr>

            <?php } ?>
        </tbody>
    </table>   
        
</div>

<script src="/js/product/admin.js"></script>