<title>Qhojo - Post</title>

<?php global $item_picture_path;global $user_picture_path;global $user_thumb_subdir;global $stock_user_tn;?>

<link rel="stylesheet" href="/css/item/index.css">
<link rel="stylesheet" href="/css/item/post.css">

<?php
    $code = $viewmodel["USER"]["NEED_EXTRA_FIELDS"];
    require(dirname(dirname(__FILE__)) . '/embeds/extra_fields.php'); 
?>

<div class="sheet">
    <?php if ($this->state == 0) { ?>
    <form class="form" id="post" action="/item/post/null/1" method="post">
        <legend>
            <div class="non-editable" id="title">
                <span id="title" class="value"></span>    
                <a href="javascript:void(0);">
                    <i class="icon-pencil" id="title" style=""></i>
                </a>
            </div>

            <input type="text" class="input-block-level editable" placeholder="Put in the name of the item here. Include the model and manufacturer." id="title" name="title">

        </legend>
        <div class="row-fluid">
            <div class="span9">
                <div class="row-fluid" id="visuals">
                    <div class="span3 text-center section" id="item-thumbs">

                    </div>

                    <div class="span9 text-center" id="item-picture">
                        <div id="add-pictures" style="">
                            <button data-toggle="modal" tabindex="-1" href="#upload-item-pictures" id="upload-picture-btn" class="btn btn-success btn-large btn-block" type="button" style="">Upload Pictures</button>                                
                        </div>                             
                    </div>       
                </div>

                <div id="details">
                    <div class="subsection" id="description">
                        <h3>Description</h3>

                        <textarea id="description" name="description" class="editable" placeholder="Enter in some descriptive details about the item here." style=""></textarea>

                        <div class="non-editable">
                            <p>
                                <span id="description" class="value"></span> 
                                <a href="javascript:void(0);">
                                    <i class="icon-pencil icon-2x" id="description" style="margin-left: 10px"></i>
                                </a>                            
                            </p>
                        </div>
                    </div>

                    <div class="subsection" id="hold-policy">
                        <h3>Hold Policy</h3>

                        A $<input type="text" class="input-block-level editable positive-integer" placeholder="Enter item amount" id="hold" name="hold" style=""><span id="hold-policy" class="non-editable value"></span>  

                        hold will be placed on the borrower's credit card at the start of the rental period.
                        <a href="javascript:void(0);">
                            <i class="icon-pencil icon-2x" id="hold" style=""></i>
                        </a>  
                    </div>                      

                    <div class="subsection" id="reviews">
                        <h3>Reviews</h3>
                        <i>No reviews yet</i>
                     </div> 
                </div>
            </div>

            <div class="span3 side-panel">    
                <div class="section split action">


                    <h2 class="rental-rate" style="">$</h2>
                    <input type="text" class="input-block-level editable positive-integer" placeholder="Rate" id="rate" name="rate" style="">
                    <h2  class="rental-rate" style="">&nbsp;/ day                                     
                    </h2>

                    <div class="non-editable">
                        <h2 class="text-center" id="rental-rate" style="">$<span id="rental-rate" class="value"></span> / day
                            <a href="javascript:void(0);">
                                <i class="icon-pencil" id="rate" style=""></i>
                            </a>    
                        </h2>
                    </div>

                    <button id="rentlink" class="btn btn-success btn-large btn-block" type="button" >Borrow</button>     
                </div>

                <div class="section split text-center" id="lender">
                    <a href="/user/index/<?php echo $viewmodel['USER']['USER_ID']?>">
                        <img id="lender-picture" class="img-circle" src="<?php echo $viewmodel['USER']['PROFILE_PICTURE_FILENAME'] == null ? $stock_user_tn : $user_picture_path . $viewmodel['USER']['USER_ID'] . $user_thumb_subdir . "/" . $viewmodel['USER']['PROFILE_PICTURE_FILENAME'] ?>">
                    </a>
                    <h2 class="" style="">
                        <a href="/user/index/<?php echo $viewmodel['USER']['USER_ID']?>"><?php echo $viewmodel['USER']['NAME']; ?></a>
                    </h2>

                    <button id="contact-btn" class="btn btn-primary btn-large btn-block" type="button" >Contact</button>         
                </div>

                <div class="section split map">
                    <input type="hidden" id="location" name="location" value="<?php echo $viewmodel['USER']['ZIPCODE']?>" style=""/>

                    <div id="map" class="non-editable" style="">    
                        <div id="map_canvas" style=""></div>    
                        <a href="javascript:void(0);">
                            <i class="icon-pencil icon-2x " id="zipcode" style=""></i>
                        </a>  
                    </div>

                    <input type="text" class="input-block-level editable positive-integer" placeholder="Enter zipcode" id="zipcode" name="zipcode" value="<?php echo $viewmodel['USER']['ZIPCODE']?>" style="">

                </div>            
            </div>
        </div>  

        <button id="post-submit" class="btn btn-primary btn-large btn-block" type="submit" style="" >Submit</button>     
        <input type="hidden" id="item_id" name="item_id" value="<?php echo $viewmodel["ITEM"]["ITEM_ID"]; ?>">
    </form>
    
    <div id="upload-item-pictures" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
        <div class="modal-header">
            <button id="close" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Upload Item Pictures</h3>
        </div>
        <div class="modal-body text-left" style="">
            <?php require(dirname(dirname(__FILE__)) . '/embeds/picture_upload.php'); ?> 
        </div>
        <div class="modal-footer">
            <button id="done" data-dismiss="modal" class="btn btn-primary">Done</button>
        </div>
    </div>
    
    <?php } else if ($this->state == 2) { ?>
    <legend>
        Item Listed
    </legend>    
    <div>
        Thanks <?php echo $_SESSION["USER"]["FIRST_NAME"] ?>! You have submitted your item <a href="/item/index/<?php echo $viewmodel["ITEM"]["ITEM_ID"] ?>"><?php echo $viewmodel["ITEM"]["TITLE"] ?></a> successfully. You're all done! 
    </div>
    <?php } ?>
    
</div>

<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATBCUDSJrOMyO4sm1-r8ooIjByWnZaYeA&sensor=false">
</script> 


<script src="/js/item/post.js"></script>
<script src="/js/jquery.numeric.js"></script>
