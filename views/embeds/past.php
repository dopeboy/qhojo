<?php 

global $item_picture_path, $user_picture_path; 
if (empty($transactions) || count($transactions) == 0): ?> <tr><td colspan="5"><i>No requests</i></td></tr>

<?php 

else: foreach($transactions as $key=>$transaction) 
{     
?>

<tr>
    <td>
        <div class="media">
            <a class="pull-left" href="/item/index/<?php echo $transaction['ITEM_ID']; ?>">
                <img src="<?php echo $item_picture_path . $transaction['ITEM_ID'] . "/" . $transaction['ITEM_PICTURE_FILENAME'] ?>" class="thumbnail">
            </a>
            <div class="media-body">
                <h4>
                    <a href="/item/index/<?php echo $transaction['ITEM_ID']; ?>" class="media-heading"><?php echo $transaction['TITLE']; ?></a>
                </h4>
                <div>
                    $<?php echo $transaction['RATE']; ?> / day
                </div>
                <div>
                    <?php echo $transaction["REQ"]['START_DATE']; ?> - <?php echo $transaction["REQ"]['END_DATE']; ?>
                </div>
                <div>
                    <span class="label label-<?php echo $transaction['FINAL_STATE_ID'] == 900 ? "success" : "inverse"; ?>">
                        <?php echo $transaction["FINAL_STATE_NAME"];?>
                    </span>
                </div>
            </div>
        </div>
     </td>
    <td>$<?php echo $transaction["REQ"]['TOTAL']; ?></td>
    <td><a href="/user/index/<?php echo $transaction['BORROWER_ID']; ?>"><?php echo $transaction['BORROWER_NAME']; ?></a></td>
    <td>
         <div class="statuses">
             <?php 
             
                $reserved = $exchanged = $returned = $reviewed = 'muted';
                
                foreach ($transaction['HIST'] as $key=>$detail)
                {            
                    if ($detail['STATE_B_ID'] == 300)
                        $reserved = '';
                    else if ($detail['STATE_B_ID'] == 500)
                        $exchanged = '';
                    else if ($detail['STATE_B_ID'] == 700)
                        $returned = '';
                    else if ($detail['STATE_B_ID'] == 900)
                        $reviewed = ''; 
                } 
                
              ?>
             
            <i class="icon-calendar <?php echo $reserved;?> icon-2x" ></i>
            <i class="icon-play <?php echo $exchanged;?> icon-2x" ></i>
            <i class="icon-rotate-left <?php echo $returned;?> icon-2x" ></i> 
            <i class="icon-comment <?php echo $reviewed;?> icon-2x"></i>
            <?php if (!empty($reviewed)) { ?>
             <button class="btn leave-feedback pull-right">Leave Feedback</button>
             <?php } ?>
         </div>
         <ul class="actions-list">
             <?php 
             
                $cnt = 0;
                while (!empty($transaction['HIST']))
                {
                    $detail = array_pop($transaction['HIST']);
                    $d = new DateTime($detail['ENTRY_DATE']);
                    echo "<li " . ($cnt != 0 ? "class=\"muted\"" : "") . ">" . $detail['SUMMARY'] . ' (<em>' . $d->format('m/d - g:i A') . '</em>)'. "</li>";
                    ++$cnt;
                } 
                
              ?>
         </ul>
     </td>


</tr>

<?php } endif; ?>

