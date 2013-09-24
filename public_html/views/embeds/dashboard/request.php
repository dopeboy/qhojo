<?php 

global $item_picture_path, $user_picture_path; 

if (empty($transactions) || count($transactions) == 0): ?> <tr><td colspan="5"><i>No requests</i></td></tr>

<?php 

else: foreach($transactions as $key=>$transaction) 
{     
?>

<tr id="<?php echo $transaction['TRANSACTION_ID']; ?>">
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
        <div class="message-received">
            <?php $d = new DateTime($transaction["REQ"]['RECEIVED_DATE']); echo "<em>" . ($lender_view == 1 ? "Received " : "Sent ") . $d->format('m/d g:i A') . "</em>"; ?>
        </div>
    </td>
    <td>
        <div class="btn-group">            
                <button class="btn">Menu</button>
                <button class="btn dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" style="">
                    <?php if ($lender_view == 1 && $pending == 0) { ?>
                        <li><a data-toggle="modal" tabindex="-1" href="#accept-<?php echo $transaction['TRANSACTION_ID']; ?>">Accept</a></li>
                        <li><a data-toggle="modal" tabindex="-1" href="#reject-<?php echo $transaction['TRANSACTION_ID']; ?>">Reject</a></li>
                    <?php } 
                    else if ($lender_view == 0 && $pending == 0) { ?>
                        <li><a data-toggle="modal" tabindex="-1" href="#reject-<?php echo $transaction['TRANSACTION_ID']; ?>">Withdraw</a></li>
                    <?php } 
                    else if ($lender_view == 0 && $pending == 1) { ?>
                        <li><a data-toggle="modal" tabindex="-1" href="/user/extrasignup/null/0">Complete Profile</a></li>
                    <?php } ?>       
                        <li><a data-toggle="modal" tabindex="-1" href="#contact-modal-<?php echo $transaction['TRANSACTION_ID']; ?>">Contact</a></li>
                </ul>
        </div>
        
        <div id="reject-<?php echo $transaction['TRANSACTION_ID']; ?>" class="modal hide fade" role="dialog"  >
            <div id="alert-error-modal-<?php echo $transaction['TRANSACTION_ID']; ?>" class="alert alert-error alert-modal" style="">
                <strong>Error: </strong>
                <span id="error-message-modal-<?php echo $transaction['TRANSACTION_ID']; ?>"></span>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>
                    <?php if ($lender_view == 1) { ?>
                        Reject <?php echo $transaction['BORROWER_FIRST_NAME']; ?>'s request - <a href="/item/index/<?php echo $transaction['ITEM_ID']; ?>"><?php echo $transaction['TITLE']; ?></a>
                    <?php } else { ?>
                        Withdraw your request - <a href="/item/index/<?php echo $transaction['ITEM_ID']; ?>"><?php echo $transaction['TITLE']; ?></a>
                    <?php } ?>
                </h3>
            </div>
            <form class="form-submit" id="reject" action="/transaction/reject/<?php echo $transaction['TRANSACTION_ID']; ?>/<?php echo $lender_view; ?>" method="post">
                <div id="mb-<?php echo $transaction['TRANSACTION_ID']; ?>" class="modal-body text-left">

                    <?php if ($lender_view == 1) { ?>
                        <p>Specify a reason for rejecting this request:</p>
                    <?php } else { ?> 
                        <p>Specify a reason for withdrawing your request:</p>
                    <?php } ?>

                    <div class="reject-options" style="">
                        <?php foreach($viewmodel["REJECT_OPTIONS"] as $key=>$option) { ?>

                        <label class="radio">
                            <input class="required" type="radio" name="reject-option" id="<?php echo $transaction['TRANSACTION_ID'] . '_' . $option['REJECT_OPTION_ID']; ?>" value="<?php echo $option['REJECT_OPTION_ID']; ?>" >
                            <?php echo $option['REJECT_DESCRIPTION']; ?>
                        </label>                                                              

                        <?php } ?>
                    </div>    
                    <textarea id="txtarea-<?php echo $transaction['TRANSACTION_ID']; ?>" name="reason" rows="3" placeholder="Optional: Fill out a message here to specify more detail." style=""></textarea>   
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>     

        <div id="accept-<?php echo $transaction['TRANSACTION_ID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Accept <?php echo $lender_view == 1 ? $transaction['BORROWER_FIRST_NAME'] : $transaction['LENDER_FIRST_NAME']; ?>'s request - <a href="/item/index/<?php echo $transaction['ITEM_ID']; ?>"><?php echo $transaction['TITLE']; ?></a></h3>
            </div>

            <div id="mb-<?php echo $transaction['TRANSACTION_ID']; ?>" class="modal-body text-left">
                <p>Click on "Yes" to confirm.</p>
            </div>
            <div class="modal-footer">
                <form id="accept" action="/transaction/accept/<?php echo $transaction["TRANSACTION_ID"] ?>/0" method="get">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    <button type="submit" class="btn btn-primary">Yes</button>
                </form>
            </div>
        </div>    
        
        <?php 
            $receipient_full_name = $lender_view == 1 ? $transaction['BORROWER_NAME'] : $transaction['LENDER_NAME'];
            $receipient_first_name =  $lender_view == 1 ? $transaction['BORROWER_FIRST_NAME'] : $transaction['LENDER_FIRST_NAME'];
            $sender_first_name = $_SESSION["USER"]["FIRST_NAME"];
            $title = "Message {$receipient_full_name} about {$transaction['TITLE']}";
            $sender_user_id =  $_SESSION["USER"]["USER_ID"];
            $receipient_user_id =  $lender_view == 1 ? $transaction['BORROWER_ID'] : $transaction['LENDER_ID'];
            $entity_type = 'TRANSACTION';
            $entity_id = $transaction['TRANSACTION_ID'];
        ?>        
        <?php require(dirname(dirname(__FILE__)) . '/contact.php'); ?>         
</td>
</tr>

<?php } endif; ?>

