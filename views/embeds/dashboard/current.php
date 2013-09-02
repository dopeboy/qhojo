<?php 

global $item_picture_path, $user_picture_path; 
if (empty($transactions) || count($transactions) == 0): ?> <tr><td colspan="5"><i>No transactions</i></td></tr>

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
        <?php require(dirname(dirname(__FILE__)) . '/dashboard/subembeds/status.php'); ?> 
     </td>
     <td>
         <div class="text-center">
            <div class="btn-group" style="">
                <button class="btn">Menu</button>
                <button class="btn dropdown-toggle" data-toggle="dropdown">
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                   <?php if ($transaction['FINAL_STATE_ID'] == 300) { ?>
                  <li>
                      <a data-toggle="modal" tabindex="-1" href="#cancel-<?php echo $transaction['TRANSACTION_ID']; ?>">Cancel</a>
                  </li>
                  <?php } ?>
                  <li>
                      <a data-toggle="modal" tabindex="-1" href="#contact-<?php echo $transaction['TRANSACTION_ID']; ?>">Contact</a>
                  </li>
                </ul>
            </div>
         </div>
         
        <div id="cancel-<?php echo $transaction['TRANSACTION_ID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Cancel <?php echo $lender_view == 1 ? $transaction['BORROWER_FIRST_NAME'] : $transaction['LENDER_FIRST_NAME']; ?>'s request - <a href="/item/index/<?php echo $transaction['ITEM_ID']; ?>"><?php echo $transaction['TITLE']; ?></a></h3>
            </div>
            <div id="mb-<?php echo $transaction['TRANSACTION_ID']; ?>" class="modal-body text-left">
                <label>
                    <p>Are you sure?</p>
                </label>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button class="btn btn-primary">Yes</button>
            </div>
        </div>  
         
        <div id="contact-<?php echo $transaction['TRANSACTION_ID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Message <?php echo $lender_view == 1 ? $transaction['BORROWER_FIRST_NAME'] : $transaction['LENDER_FIRST_NAME']; ?> - <a href="/item/index/<?php echo $transaction['ITEM_ID']; ?>"><?php echo $transaction['TITLE']; ?></a></h3>
            </div>
            <div class="modal-body text-left" style="">
                <p>
                Hey <?php echo $lender_view == 1 ? $transaction['BORROWER_FIRST_NAME'] : $transaction['LENDER_FIRST_NAME']; ?>,
                </p>
                <div>
                    <textarea rows="3" placeholder="Put your message here." style=""></textarea>
                    <p>-<?php echo $lender_view == 1 ? $transaction['LENDER_FIRST_NAME'] : $transaction['BORROWER_FIRST_NAME']; ?></p>            
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-primary">Send Message</button>
            </div>
        </div>          
         
     </td>
 </tr>

<?php } endif; ?>

