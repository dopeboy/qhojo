<?php

?>

<div class="sheet">
    <legend>All Products</legend>
    
    <table class="table table-bordered table-striped" style="">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Description</th>
                <th>Value</th>
                <th>Rate</th>
                <th>URL</th>
                <th>Created by User ID</th>
                <th>Date Created</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            <?php 

            foreach($viewmodel as $product) 
            {     
            ?>
            <tr id="<?php echo $product['ID']; ?>">
                <td>
                    <a href="/product/addproduct/<?php echo $product['ID']; ?>/1"><?php echo $product["NAME"]; ?></a> 
                </td>
                <td>
                    <?php echo $product["CATEGORY_NAME"]; ?>  
                </td>
                <td>
                    <?php echo $product["BRAND_NAME"]; ?>  
                </td>
                <td>
                    <?php echo $product["DESCRIPTION"]; ?>  
                 </td>                 
                <td>
                    <?php echo $product["VALUE"]; ?>  
                 </td>     
                <td>
                    <?php echo $product["RATE"]; ?>  
                 </td>       
                <td>
                    <?php echo $product["URL"]; ?>  
                 </td>       
                <td>
                    <?php echo $product["CREATED_BY_USER_ID"]; ?>  
                 </td>  
                <td>
                    <?php echo $product["DATE_CREATED"]; ?>  
                 </td>  
                <td>
                    <?php echo $product["ACTIVE"]; ?>  
                 </td>                   
            </tr>

            <?php } ?>
        </tbody>
    </table>   
        
</div>

<script src="/js/product/admin.js"></script>