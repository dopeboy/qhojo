<?php global $item_thumb_subdir; ?>

<div class="media">
    <a class="pull-left" href="/item/index/<?php echo $transaction['ITEM_ID']; ?>">
        <img src="<?php echo $item_picture_path . $transaction['ITEM_ID'] . $item_thumb_subdir . "/" . $transaction['ITEM_PICTURE_FILENAME'] ?>" class="thumbnail">
    </a>
    <div class="media-body">
        <h4>
            <a href="/item/index/<?php echo $transaction['ITEM_ID']; ?>" class="media-heading"><?php echo substr($transaction['TITLE'],0,21); if (strlen($transaction['TITLE']) > 21) { echo "..."; } ?></a>
        </h4>
        <div>
            $<?php echo $transaction['RATE']; ?> / day
        </div>
        <div>
            <?php $d = new DateTime($transaction["REQ"]['START_DATE']); echo $d->format('m/d'); ?> -  <?php $d = new DateTime($transaction["REQ"]['END_DATE']); echo $d->format('m/d'); ?>             
        </div>
        <div>
            <span class="label label-<?php 
                
                if ($transaction['FINAL_STATE_ID'] == 200)
                    echo "";
                else if($transaction['FINAL_STATE_ID'] == 300) 
                    echo "info";
                else if($transaction['FINAL_STATE_ID'] == 500) 
                    echo "warning"; 
                else if($transaction['FINAL_STATE_ID'] == 650) 
                    echo "important";                 
                else if($transaction['FINAL_STATE_ID'] == 700 || ($transaction['FINAL_STATE_ID'] == 1100 && $lender_view==1) || ($transaction['FINAL_STATE_ID'] == 900 && $lender_view==0)) 
                    echo "important";   
                else if ($transaction['FINAL_STATE_ID'] == 900 || $transaction['FINAL_STATE_ID'] == 1200 || 
                         $transaction['FINAL_STATE_ID'] == 1000 || $transaction['FINAL_STATE_ID'] == 1100)
                {
                    if ($lender_view == 1 && ($transaction['FINAL_STATE_ID'] == 900 || $transaction['FINAL_STATE_ID'] == 1200 || $transaction['FINAL_STATE_ID'] == 1000))
                        echo "success"; 

                    else if ($lender_view == 0 && ($transaction['FINAL_STATE_ID'] == 1100 || $transaction['FINAL_STATE_ID'] == 1200 || $transaction['FINAL_STATE_ID'] == 1000))
                        echo "success";                
                }                                 
                
                ?>">
                <?php echo $transaction["FINAL_STATE_NAME"];?>
            </span>
        </div>
    </div>
</div>