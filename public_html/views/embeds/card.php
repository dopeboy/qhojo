<link rel="stylesheet" href="/css/embeds/card.css">

<?php global $item_picture_path, $user_picture_path, $item_card_subdir, $user_card_subdir, $stock_user_card;?>
<?php if (!isset($viewmodel["ITEMS"]) || count($viewmodel["ITEMS"]) == 0): ?> <i>No items found</i>
<?php else: foreach($viewmodel["ITEMS"] as $key=>$item) { ?>

    <?php if ($key%3 == 0) { ?> 
        <div class="row-fluid text-center search-row" style="">
    <?php } ?>
            
         <div class="span4" style="">
             <div class="card <?php if ($key%3==0) {echo "left"; } else if ($key%3==1) {echo "middle";} else if ($key%3==2) {echo "right";} ?>">
                 <div class="item-image" style="background-image: url('<?php echo $item_picture_path . $item['ITEM_ID'] . $item_card_subdir . "/" . $item['ITEM_PICTURE_FILENAME'] ?>')">
                     <a href="/item/index/<?php echo $item['ITEM_ID'];?>" class="fill-div">
                        <div class="item-price">
                            $<?php echo $item['RATE']; ?> 
                            <hr/>
                            per day
                        </div>
                        <div class="item-title" >
                           <?php echo substr($item['TITLE'],0,21); if (strlen($item['TITLE']) > 21) { echo "..."; } ?>
                        </div> 
                     </a>
                 </div>
                 <div class="row-fluid text-center item-info">
                     <div class="span4 item-lender">
                          <img class="lender-picture img-circle" src="<?php echo $item['PROFILE_PICTURE_FILENAME'] == null ? $stock_user_card : $user_picture_path . $item['LENDER_ID'] . "/" . $user_card_subdir . '/' . $item['PROFILE_PICTURE_FILENAME'] ?>">
                     </div>
                     <div class="span8 text-left item-details" style="">
                         <div class="descriptor" rel="tooltip" data-toggle="tooltip" data-placement="right" title="Location" style=""><i class="icon-map-marker"></i> <?php echo $item['LOCATION'] ?></div>
                         <div class="descriptor" rel="tooltip" data-toggle="tooltip" data-placement="right" title="Response Time" style=""><i class="icon-time"></i> 
                            <?php $response_time = $item["RESPONSE_TIME_IN_MINUTES"]; ?>
                            <?php require(dirname(dirname(__FILE__)) . '/embeds/response_time.php'); ?>                          
                         </div>
                         <div class="descriptor" rel="tooltip" data-toggle="tooltip" data-placement="right" title="Number of Transactions" style=""><i class="icon-check"></i> <?php echo $item['COMPLETED_TRANSACTION_CNT'] ?></div>
                     </div>                      
                 </div>                 
             </div>
         </div>            
            
    <?php if ($key%3 == 2 || $key == count($viewmodel["ITEMS"])-1) { ?> 
        </div> <!-- row --!>
    <?php } ?>            

<?php } endif; ?>