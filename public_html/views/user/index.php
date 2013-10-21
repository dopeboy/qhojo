<title>Qhojo - User Profile</title>

<?php 

global $item_picture_path, $user_picture_path;
$date = new DateTime($viewmodel["USER"]["JOIN_DATE"]);
$join_date = $date->format('F Y');

global $stock_user_big;
?>

<?php

    if ($_SESSION["USER"]["USER_ID"] == $viewmodel["USER"]["USER_ID"])
    {
        $code = $viewmodel["USER"]["NEED_EXTRA_FIELDS"];
        require(dirname(dirname(__FILE__)) . '/embeds/extra_fields.php'); 
    }
?>

<link rel="stylesheet" href="/css/user/index.css">

<div class="sheet">

    <legend><?php echo $viewmodel["USER"]["NAME"];?>
        <div class='pull-right' style='margin-top: -11px;'>
            <?php if ($_SESSION["USER"]["USER_ID"] == $viewmodel["USER"]["USER_ID"] && ($this->state == 0 || $this->state == null)) { ?>
                <a href='/user/index/<?php echo $this->id; ?>/1' id="edit-user" class="btn btn-primary btn-large" style="" >Edit Profile</a> 
            <?php } else if ($_SESSION["USER"]["USER_ID"] == $viewmodel["USER"]["USER_ID"] && ($this->state == 1)) {  ?>
                <a href='/user/index/<?php echo $this->id; ?>/0' id="edit-user" class="btn btn-primary btn-large" style="" >Done Editing</a>
            <?php } ?>
        </div>
    </legend>
    
    <div class="row-fluid">
        <div class="span4">
            <div class="section split" id="user-general">
                <div class="text-center" id="profile-picture-container" style="">
                    <img style id="user-picture" class="img-circle" src="<?php echo $viewmodel["USER"]["PROFILE_PICTURE_FILENAME"] == null ? $stock_user_big : $user_picture_path . $viewmodel["USER"]["USER_ID"] . "/" . $viewmodel["USER"]["PROFILE_PICTURE_FILENAME"] ?>">    
                    <?php if ($this->state == 1) { ?>
                    <div id="upload-profile-picture-container" style ="">
                        <a data-toggle="modal" href="#upload-profile-picture" class="pull-right"  >
                            <i class="icon-pencil icon-3x" id="pp" style=""></i>
                        </a>
                    </div>
                    <?php } ?>
                </div>
                
                <h4 class=""><?php echo $viewmodel["USER"]["NAME"]?></h4>

                <ul class="icons-ul" style="">
                  <li><i class="icon-li icon-calendar"></i> Member since <?php echo $join_date?></li>
                  <li><i class="icon-li icon-map-marker"></i><?php echo $viewmodel["USER"]["LOCATION"]?></li>
                </ul>                    
                         
            </div>   
            
            <div class="section split" id="user-activity">
                <h4>Activity</h4>
             
                    <ul class="icons-ul">
                        <li>
                            <i class="icon-li icon-time"></i> 
                            <?php $response_time = $viewmodel["USER"]["RESPONSE_TIME_IN_MINUTES"]; ?>
                            <?php require(dirname(dirname(__FILE__)) . '/embeds/response_time.php'); ?> 
                        </li>
                        <li>
                            <i class="icon-li icon-check"></i> 
                            <?php if ($viewmodel["USER"]["COMPLETED_TRANSACTION_CNT"] == 0): 
                                    echo "No transactions"; 
                                  else: 
                                    echo $viewmodel["USER"]["COMPLETED_TRANSACTION_CNT"] . 
                                        ' transaction' . ($viewmodel["USER"]["COMPLETED_TRANSACTION_CNT"] > 1 ? 's' : ''); 
                                  endif; ?>
                        </li>
                        <li>
                            <i class="icon-li icon-pushpin"></i> 
                            <?php if ($viewmodel["NUM_ITEMS_POSTED"] == 0): 
                                    echo "No items listed"; 
                                  else: 
                                    echo $viewmodel["NUM_ITEMS_POSTED"] . 
                                        ' item' . ($viewmodel["NUM_ITEMS_POSTED"] > 1 ? 's' : ''); ?>
                                        
                                        <a href="/item/search?user_id=<?php echo $viewmodel["USER"]["USER_ID"];?>">listed</a>
                                  <?php endif; ?> 
                    </ul>                    
                          
            </div>
            
            <div class="section split" id="user-verifications">
                <h4>Verifications <?php if ($_SESSION["USER"]["USER_ID"] == $viewmodel["USER"]["USER_ID"] && ($viewmodel["USER"]["PHONE_VERIFIED"] == null || $viewmodel["USER"]["PAYPAL_EMAIL_ADDRESS"] == null || $viewmodel["USER"]["BP_PRIMARY_CARD_URI"]== null)) { ?><a href='/user/completeprofile/null/0' class="btn btn-mini btn-primary pull-right" type="button" style=''>Complete Profile</a><?php }?></h4> 
                  <ul class="icons-ul" style="">
                    <li>
                          <i class="icon-li icon-phone"></i>
                            <?php if ($viewmodel["USER"]["PHONE_VERIFIED"] == null) { ?>
                                <span class="text-error">Phone not verified</span> 
                            <?php } else { ?>
                                <span class="text-success">Phone verified</span>
                            <?php } ?>
                    </li>
                    <li>
                        <i class="icon-li icon-usd"></i>
                            <?php if ($viewmodel["USER"]["PAYPAL_EMAIL_ADDRESS"] == null) { ?>
                                <span class="text-error">PayPal not verified</span>
                            <?php } else { ?>
                                <span class="text-success">PayPal verified</span> 
                            <?php } ?>                         
                    </li>                       
                    <li>
                        <i class="icon-li icon-credit-card"></i> 
                            <?php if ($viewmodel["USER"]["BP_PRIMARY_CARD_URI"]== null) { ?>
                                <span class="text-error">Credit card not verified</span>
                            <?php } else { ?>
                                <span class="text-success">Credit card verified</span>
                            <?php } ?>                            
                    </li>                 
                </ul>
            </div>
            
        </div>
        <div class="span8">
            <div id="blurb" class="" style="">
                <div id="top" style="">
                    <h4 style="">Hi! I'm <?php echo $viewmodel["USER"]["FIRST_NAME"]?></h4>
                </div>
                <div id="bottom" style="">
                    <span id="blurb-content">
                        <?php echo $viewmodel["USER"]["BLURB"] == null ? "<i>No blurb yet</i>" : $viewmodel["USER"]["BLURB"]?>
                    </span>
                    <?php if ($this->state == 1) { ?>
                        <a data-toggle="modal" href="#edit-blurb-1">
                            <i class="icon-pencil icon-2x" id="title" style=""></i>
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div id="reviews">
                <div id="reviews-from-lenders">
                    <legend id="reviews-lenders-header" style="">Reviews (<?php echo count($viewmodel["REVIEWS_OF_ME"]);?>)</legend>
                    
                    <?php $viewmodel["ITEM_REVIEWS"] = $viewmodel["REVIEWS_OF_ME"]; $show_item = 1; ?>
                    <?php require(dirname(dirname(__FILE__)) . '/embeds/review.php'); ?> 
                    
                </div>  
            </div>
        </div>
    </div>
</div>

<?php if ($this->state == 1) { ?>

<div id="edit-blurb-1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="alert-error-modal-1" class="alert alert-error alert-modal" style="">
        <strong>Error: </strong>
        <span id="error-message-modal-1"></span><button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Edit Blurb</h3>
    </div>
    <form class="form-submit" id="blurb-1" action="/user/edit/<?php echo $this->id; ?>/100" method="post">
        <div id="mb-1" class="modal-body text-left">
            <textarea class='required' id='edit-blurb' name="blurb" rows="3" placeholder="Tell us about yourself." style=""><?php echo $viewmodel["USER"]["BLURB"]; ?></textarea>                          
        </div>
        <div id="mf-1" class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-primary">Submit</button>
        </div>
    </form>
</div> 

<div id="upload-profile-picture" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
    <div class="modal-header">
        <button id="close" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Upload Profile Picture</h3>
        Note: Please upload your actual picture.
    </div>
    <div class="modal-body text-left" style="">
        <?php require(dirname(dirname(__FILE__)) . '/embeds/picture_upload.php'); ?> 
    </div>
    <div class="modal-footer">
        <button id="done" data-dismiss="modal" class="btn btn-primary">Done</button>
    </div>
</div>  

<script src="/js/user/index.js"></script>

<?php } ?>
