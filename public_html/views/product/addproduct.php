<title>Qhojo - Product Admin</title>

<?php 

$name = null;
$description = null;
$value = null;
$rate = null;
$url = null;
$target  = 2;
$product_id = getRandomID();

// Edit mode
if ($this->state == 1)
{
    $name = $viewmodel["NAME"];
    $description = $viewmodel["DESCRIPTION"];
    $value = $viewmodel["VALUE"];
    $rate = $viewmodel["RATE"];
    $url = $viewmodel["URL"];    
    $target = 3;
    $product_id = $this->id;
}


?>

<div class="sheet">
    
    <legend>
        <?php if ($this->state == 1) { ?> Edit <?php } else { ?> Add <?php } ?> Product
    </legend>    
    
    <form class="form-submit" id="add-product" autocomplete="off" action="/product/addproduct/<?php echo $this->id == null ? "null" : $this->id; ?>/<?php echo $target;?>" method="post">	
        
        <div class="control-group">
            <select id="cat-id" name="cat-id">
                <option value="0" selected="selected">Select a category</option>
                <?php foreach ($viewmodel["CATEGORY"] as $category) { ?>
                <option value="<?php echo $category["CATEGORY_ID"]?>" <?php echo $this->state == 1 && $viewmodel["CATEGORY_ID"] == $category["CATEGORY_ID"] ? 'selected="selected"' : ""?>><?php echo $category["NAME"];?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="control-group">
            <select id="brand-id" name="brand-id">
                <option value="0" selected="selected">Select a brand</option>
                <?php foreach ($viewmodel["BRAND"] as $brand) { ?>
                <option value="<?php echo $brand["BRAND_ID"]?>" <?php echo $this->state == 1 && $viewmodel["BRAND_ID"] == $brand["BRAND_ID"] ? 'selected="selected"' : ""?>><?php echo $brand["NAME"];?></option>
                <?php } ?>
            </select>
        </div>           
        
        <div class="control-group">
            <input type="text" class="input" placeholder="Name" id="name" name="name" value='<?php echo $name;?>'>
        </div>        
        
        <div class="control-group">
            <textarea type="text" placeholder="Description" id="description" name="description" rows='10'><?php echo $description;?></textarea>
        </div>     
        <br/>
        
        <div class="control-group">
            <input type="text" class="input" placeholder="Value" id="value" name="value" value='<?php echo $value;?>'>
        </div>     
        
        <div class="control-group">
            <input type="text" class="input" placeholder="Rate" id="rate" name="rate" value='<?php echo $rate;?>'>
        </div>     
        
        <div class="control-group">
            <input type="text" class="input" placeholder="URL" id="url" name="url" value='<?php echo $url;?>'>
        </div>             
        
        <button data-toggle="modal" tabindex="-1" href="#upload-product-pictures" id="upload-picture-btn" class="btn btn-large" type="button" style="">Add/Remove Pictures</button>            

        <div class="" id="item-thumbs">
        </div>               
        
        <br/>
        <br/>
        
        <div class="control-group">
            <button type="submit" class="btn btn-large btn-primary" rel="tooltip" title="">Submit</button>
        </div>  
        
        <input type="hidden" id="product_id" name="product_id" value="<?php echo $product_id;?>">
        
    </form>  
    
</div>

<div id="upload-product-pictures" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
    <div class="modal-header">
        <button id="close" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="" style="">Upload Product Pictures</h3>
    </div>
    <div class="modal-body text-left" style="">
        <?php require(dirname(dirname(__FILE__)) . '/embeds/picture_upload.php'); ?> 
    </div>
    <div class="modal-footer">
        <button id="done" data-dismiss="modal" class="btn btn-primary">Done</button>
    </div>
</div>  

<script src="/js/product/admin.js"></script>
<script src="/js/product/addproduct.js"></script>