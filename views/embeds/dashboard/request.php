<?php 

global $item_picture_path, $user_picture_path; 
if (empty($transactions) || count($transactions) == 0): ?> <tr><td colspan="5"><i>No requests</i></td></tr>

<?php 

else: foreach($transactions as $key=>$transaction) 
{     
?>

<tr>
    <td>
        <?php require(dirname(dirname(__FILE__)) . '/dashboard/subembeds/item.php'); ?> 
    </td>
    <td>
        <?php require(dirname(dirname(__FILE__)) . '/dashboard/subembeds/total.php'); ?> 
    </td>
    <td>
        <?php require(dirname(dirname(__FILE__)) . '/dashboard/subembeds/user.php'); ?> 
    </td>
    <td>
        <?php echo $transaction["REQ"]['MESSAGE']; ?>
        <div style="margin-top: 10px">
            <?php $d = new DateTime($transaction["REQ"]['RECEIVED_DATE']); echo "<em>" . ($lender_view == 1 ? "Received " : "Sent ") . $d->format('m/d g:i A') . "</em>"; ?>
        </div>
    </td>
    <td>
        <div class="text-center">
            <div class="btn-group">
                <button class="btn">Menu</button>
                <button class="btn dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" style="">
                    <li><a tabindex="-1" href="#">Accept</a></li>
                    <li><a data-toggle="modal" tabindex="-1" href="#reject-<?php echo $transaction['TRANSACTION_ID']; ?>">Reject</a></li>
                </ul>
                <div id="reject-<?php echo $transaction['TRANSACTION_ID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>Reject <?php echo $lender_view == 1 ? $transaction['BORROWER_FIRST_NAME'] : $transaction['LENDER_FIRST_NAME']; ?>'s request - <a href="/item/index/<?php echo $transaction['ITEM_ID']; ?>"><?php echo $transaction['TITLE']; ?></a></h3>
                    </div>
                    <div id="mb-<?php echo $transaction['TRANSACTION_ID']; ?>" class="modal-body text-left">
                        <label>
                            <p>Specify a reason for rejecting this request:</p>
                        </label>

                        <form id="form-<?php echo $transaction['TRANSACTION_ID']; ?>" >
                            <div class="reject-options" style="">
                                <?php foreach($viewmodel["REJECT_OPTIONS"] as $key=>$option) { ?>

                                <label class="radio">
                                    <input type="radio" name="<?php echo $transaction['TRANSACTION_ID']; ?>" id="<?php echo $transaction['TRANSACTION_ID'] . '_' . $option['REJECT_OPTION_ID']; ?>" value="<?php echo $option['REJECT_OPTION_ID']; ?>" >
                                    <?php echo $option['REJECT_DESCRIPTION']; ?>
                                </label>                                        

                                <?php } ?>
                            </div>    
                            <textarea id="txtarea-<?php echo $transaction['TRANSACTION_ID']; ?>" name="sdfsdf" rows="3" placeholder="Optional: Fill out a message here to specify more detail." style=""></textarea>   
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </div>                            
            </div>
        </div>
    </td>
</tr>

<?php } endif; ?>

