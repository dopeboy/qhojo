<title>Qhojo - Lend</title>

<?php global $item_picture_path;global $user_picture_path;global $user_thumb_subdir;global $stock_user_tn;?>

<link rel="stylesheet" href="/css/item/index.css">
<link rel="stylesheet" href="/css/item/post.css">

<?php
    $code = $viewmodel["USER"]["NEED_EXTRA_FIELDS"];
    require(dirname(dirname(__FILE__)) . '/embeds/extra_fields.php'); 
    
    if ($this->state == 1)
    {
        $title = null;
        $description = null;
        $value = null;
        $rate = null;
        
        // We got a product. Prefill away
        if (isset($viewmodel["PRODUCT"]) && $viewmodel["PRODUCT"] != null)
        {
            $title = $viewmodel["PRODUCT"]["FULL_NAME"];
            $description = $viewmodel["PRODUCT"]["DESCRIPTION"];
            $value = $viewmodel["PRODUCT"]["VALUE"];
            $rate = $viewmodel["PRODUCT"]["RATE"];
        }
    }
?>

<?php

    // If we got a product passed in, let's give a head's up to the user that we've prefilled stuff in 
    if (isset($viewmodel["PRODUCT"]) && $viewmodel["PRODUCT"] != null)
    {
    ?>

    <div class="alert alert-info">
        <strong>Head's up: </strong>
        We pre-filled all the fields in the form below to save you some time. Feel free to change them if you wish.
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>    

    <?php 
    }
?>

<div class="sheet">
    <?php if ($this->state == 0 || $this->state == null) { ?>
    <legend>
        Lend Item - Step 1
    </legend>    
    
    <form class="" id="choose-product" action="/item/post/null/1" method="get">
 
        <div class="product-chooser">
            <select id="category">
                <option value="-1" disabled selected>Select a category</option>    
                <option value="-1" disabled> </option>    
                <?php foreach ($viewmodel['CATEGORY'] as $category)
                {
                ?>
                <option name="category-id" value="<?php echo $category["CATEGORY_ID"]?>"><?php echo $category["NAME"]?></option>
                <?php } ?>
            </select>                    
        </div>
        
        <div class="product-chooser">
            <select id="brand" disabled>
                <option value="-1" disabled selected>Select a brand</option>    
                <option value="-1" disabled> </option>    
     
            </select>   
        </div>
        
        <div class="product-chooser">
            <select name="product-id" id="product" disabled>
                <option value="-1" disabled selected>Select a product</option>    
                <option value="-1" disabled> </option>    
            </select>                    
        </div>
        
        <div id="create-new">
            Don't see your item above? <a href="/item/post/null/1">Create a new listing here</a>.
        </div>
        
        <button id="submit-product" class="btn btn-primary btn-large disabled" type="submit" style="" >Next</button>
    </form>
    
    
    <?php } ?>
    
    <?php if ($this->state == 1) { ?>
        <legend>
            Lend Item - Step 2
        </legend>
        
        <form class="form-submit" id="post" action="/item/post/null/2" method="post">
        
            <div class="sub-section">
                <table id="item-details">
                    <colgroup>
                       <col class="first" span="1" style="">
                       <col class="second" span="1" style="">
                    </colgroup>
                    <tr>
                        <td>Title</td>
                        <td>
                            <input type="text" id="title" name="title" maxlength="100" placeholder="Put in the name of the item here. Include the model and manufacturer." value="<?php echo $title;?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td>
                            <textarea id='description' name="description" rows="8" placeholder="Enter in some descriptive details about the item here."><?php echo $description;?></textarea>                                                  
                        </td>
                    </tr>
                    <tr>
                        <td>Zipcode</td>
                        <td>
                            <input type="text" id="location" name="location" maxlength="5" placeholder="" value="<?php echo $viewmodel["USER"]["ZIPCODE"]?>">
                        </td>
                    </tr>                
                    <tr >
                        <td>Borrow Rate</td>
                        <td class="font-16">
                            
      
                            <span class="tool-tip-container" style="">
                                <a class="help" href="javascript: void(0)" data-toggle="tooltip" data-placement="right" title="This is the daily rate members will be charged when they borrow your item." style="">
                                    <i class="icon-question-sign"></i>
                                </a>
                            </span>
                            
                            <div class="input-prepend  input-append">
                                <span id="add-on" class="add-on"><i class="icon-usd"></i></span>
                                <input class="positive-integer" style=" " type="text" id="borrow-rate" name="borrow-rate" maxlength="5" value="<?php echo $rate;?>">
                                <span class="add-on">/ day</span>
                            </div>
                            
                        </td>
                    </tr>
                    <tr>
                        <td>Hold Amount</td>
                        <td class="font-16">
                            <span class="tool-tip-container">
                                <a class="help" href="javascript: void(0)" data-toggle="tooltip" data-placement="right" title="This is the amount of money your item is worth. In the event of damage or theft, the maximum award you can receive will be this amount.">
                                    <i class="icon-question-sign"></i>
                                </a>
                            </span>
                            <div class="input-prepend  input-append">
                                <span id="add-on" class="add-on"><i class="icon-usd"></i></span>
                                <input class="positive-integer" style="" type="text" id="hold-amount" name="hold-amount" maxlength="5" value="<?php echo $value;?>">
                            </div>
                        </td>
                    </tr>         
                    <tr>
                        <td>
                            Pictures
                        </td>
                        <td>    
                            <div class="" id="item-thumbs">
                            </div>

                            <button data-toggle="modal" tabindex="-1" href="#upload-item-pictures" id="upload-picture-btn" class="btn" type="button" style="">Add/Remove Pictures</button>            
                        </td>
                    </tr>
                </table>                   
            </div>

       
            <?php if ($viewmodel["PRODUCT"]["PRODUCT_ID"] != null) { ?>
                <input type="hidden" name="product-id" value="<?php echo $viewmodel["PRODUCT"]["PRODUCT_ID"]; ?>">
            <?php } ?>
        <button id="post-submit" class="btn btn-primary btn-large" type="submit" style="" >Submit</button>     
    </form>
    
    <div id="upload-item-pictures" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
        <div class="modal-header">
            <button id="close" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="" style="">Upload Item Pictures</h3>
        </div>
        <div class="modal-body text-left" style="">
            <?php require(dirname(dirname(__FILE__)) . '/embeds/picture_upload.php'); ?> 
        </div>
        <div class="modal-footer">
            <button id="done" data-dismiss="modal" class="btn btn-primary">Done</button>
        </div>
    </div>
    
    <?php } else if ($this->state == 3) { ?>
    <legend>
        Item Posted
    </legend>    
    <div>
        <p>Thanks <?php echo $_SESSION["USER"]["FIRST_NAME"] ?>! You have posted your item successfully. </p>
        <p>To view your item, <a href="/item/index/<?php echo $viewmodel["ITEM"]["ITEM_ID"] ?>">click here</a>.</p>
        <p>To track your item in your dashboard, <a href="/user/dashboard#my-items#<?php echo $viewmodel["ITEM"]["ITEM_ID"]?>">click here</a>.</p>
        
    </div>
    <?php } ?>
    
</div>

<script src="/js/item/post.js"></script>
<script src="/js/jquery.numeric.js"></script>
