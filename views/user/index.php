<?php 

global $item_picture_path, $user_picture_path;
$date = new DateTime($viewmodel["USER"]["JOIN_DATE"]);
$join_date = $date->format('F Y');

?>

<div class="sheet">
    <legend><?php echo $viewmodel["USER"]["NAME"]?></legend>
    
    <div class="row-fluid">
        <div class="span4">
            <div class="section split" id="user-general">
                <div class="text-center">
                    <img id="user-picture" class="img-circle" src="<?php echo $user_picture_path . $viewmodel["USER"]["USER_ID"] . "/" . $viewmodel["USER"]["PROFILE_PICTURE_FILENAME"] ?>">    
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
                                        ' item' . ($viewmodel["USER"]["COMPLETED_TRANSACTION_CNT"] > 1 ? 's' : ''); ?>
                                        
                                        <a href="/item/search?user_id=<?php echo $viewmodel["USER"]["USER_ID"];?>">listed</a>
                                  <?php endif; ?> 
                    </ul>                    
                          
            </div>
            
            <div class="section split" id="user-verifications">
                <h4>Verifications</h4>
                  <ul class="icons-ul" style="">
                    <li>
                          <i class="icon-li icon-phone"></i>
                            <?php if ($viewmodel["USER"]["PHONE_VERIFIED"] == 0) { ?>
                                <span class="text-error">Phone verified</span>
                            <?php } else { ?>
                                <span class="text-success">Phone verified</span>
                            <?php } ?>
                    </li>
                    <li>
                        <i class="icon-li icon-credit-card"></i> 
                            <?php if (empty($viewmodel["USER"]["BP_PRIMARY_CARD_URI"]) == 0) { ?>
                                <span class="text-error">Credit card not verified</span>
                            <?php } else { ?>
                                <span class="text-success">Credit card verified</span>
                            <?php } ?>                            
                    </li>                 
                    <li>
                        <i class="icon-li icon-usd"></i>
                            <?php if (empty($viewmodel["USER"]["PAYPAL_EMAIL_ADDRESS"]) == 0) { ?>
                                <span class="text-error">PayPal not verified</span>
                            <?php } else { ?>
                                <span class="text-success">PayPal verified</span>
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
                    <?php echo $viewmodel["USER"]["BLURB"]?>
                </div>
            </div>
            <div id="reviews">
                <div id="reviews-from-lenders">
                    <legend id="reviews-lenders-header" style="">Reviews of <?php echo $viewmodel["USER"]["FIRST_NAME"]?> (<?php echo count($viewmodel["REVIEWS_OF_ME"]);?>)</legend>
                    
                    <?php $viewmodel["ITEM_REVIEWS"] = $viewmodel["REVIEWS_OF_ME"]; ?>
                    <?php require(dirname(dirname(__FILE__)) . '/embeds/review.php'); ?> 
                    
                </div>  
                
                <div id="reviews-from-borrowers" style="">
                    <legend id="reviews-borrowers-header" style="">Reviews by <?php echo $viewmodel["USER"]["FIRST_NAME"]?> (<?php echo count($viewmodel["REVIEWS_BY_ME"]);?>)</legend>
                    
                    <?php $viewmodel["ITEM_REVIEWS"] = $viewmodel["REVIEWS_BY_ME"]; ?>
                    <?php require(dirname(dirname(__FILE__)) . '/embeds/review.php'); ?>       
                    
                </div> 
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/css/user/index.css">
<script src="/js/user/index.js"></script>