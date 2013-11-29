<?php

?>

<div class="sheet">
    <legend>All Brands</legend>
    
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

            foreach($viewmodel as $brand) 
            {     
            ?>
            <tr id="<?php echo $brand['ID']; ?>">
                <td>
                    <a href="/product/addbrand/<?php echo $brand['ID']; ?>/1"><?php echo $brand["NAME"]; ?></a> 
                </td>
                <td>
                    <?php echo $brand["DISPLAY_ORDER"]; ?>  
                </td>
                <td>
                    <?php echo $brand["CREATED_BY_USER_ID"]; ?>  
                </td>
                <td>
                    <?php echo $brand["DATE_CREATED"]; ?>  
                 </td>                 
                <td>
                    <?php echo $brand["ACTIVE"]; ?>  
                 </td>                      
            </tr>

            <?php } ?>
        </tbody>
    </table>   
        
</div>

<script src="/js/product/admin.js"></script>