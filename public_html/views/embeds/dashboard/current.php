<?php 

global $item_picture_path, $user_picture_path; 
if (empty($transactions) || count($transactions) == 0): ?> <tr><td colspan="5"><i>No transactions</i></td></tr>

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
        <?php require(dirname(dirname(__FILE__)) . '/dashboard/subembeds/status.php'); ?> 
     </td>
     <td>
        <div class="btn-group" style="">
            <button class="btn" data-toggle="dropdown">Menu</button>
            <button class="btn dropdown-toggle" data-toggle="dropdown">
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
               <?php if ($transaction['FINAL_STATE_ID'] == 300) { ?>
              <li>
                  <a data-toggle="modal" tabindex="-1" href="#cancel-<?php echo $transaction['TRANSACTION_ID']; ?>">Cancel</a>
              </li>
              <?php } ?>
               <?php if ($transaction['FINAL_STATE_ID'] == 500 || $transaction['FINAL_STATE_ID'] == 650) { ?>
              <li>
                  <a data-toggle="modal" tabindex="-1" href="/transaction/reportdamage/<?php echo $transaction['TRANSACTION_ID']; ?>">Report Damage</a>
              </li>
              <?php } ?>                  
              <li>
                  <a data-toggle="modal" tabindex="-1" href="#contact-modal-<?php echo $transaction['TRANSACTION_ID']; ?>">Contact</a>
              </li>
            </ul>
        </div>
        
         
        <div id="cancel-<?php echo $transaction['TRANSACTION_ID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div id="alert-error-modal-<?php echo $transaction['TRANSACTION_ID']; ?>" class="alert alert-error alert-modal" style="">
                <strong>Error: </strong>
                <span id="error-message-modal-<?php echo $transaction['TRANSACTION_ID']; ?>"></span>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Cancel <?php echo $lender_view == 1 ? $transaction['BORROWER_FIRST_NAME'] . '\'s' : "your" ?> request - <a href="/item/index/<?php echo $transaction['ITEM_ID']; ?>"><?php echo $transaction['TITLE']; ?></a></h3>
            </div>
            <form class="form-submit" id="cancel-<?php echo $lender_view == 1 ? 'lender' : 'borrower' ?>" action="/transaction/cancel/<?php echo $transaction['TRANSACTION_ID']; ?>/0" method="post">
                <div id="mb-<?php echo $transaction['TRANSACTION_ID']; ?>" class="modal-body text-left">
                   
                    <?php if ($lender_view == 1) { ?>
                    <p>Specify a reason for canceling this request:</p>
                    <?php } else { ?> 
                    <p>Specify a reason for canceling your request:</p>
                    <?php } ?>
                    
                    <div class="cancel-options" style="">
                        <?php foreach($viewmodel["CANCEL_OPTIONS"] as $key=>$option) { ?>
                        <div class="radio">
                            <input class="required" type="radio" name="cancel-option" id="<?php echo $transaction['TRANSACTION_ID'] . '_' . $option['CANCEL_OPTION_ID']; ?>" value="<?php echo $option['CANCEL_OPTION_ID']; ?>" >
                            <?php echo $option['CANCEL_DESCRIPTION']; ?>
                        </div>                                        
                        <?php } ?>
                    </div>
                    <textarea id="txtarea-<?php echo $transaction['TRANSACTION_ID']; ?>" name="reason" rows="3" placeholder="Optional: Fill out a message here to specify more detail." style=""></textarea>                          
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    <button class="btn btn-primary">Submit</button>
                </div>
            </form>
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