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
         <?php if ($awaiting_review == 1) { ?>
         <form action="/transaction/review/<?php echo $transaction["TRANSACTION_ID"] ?>">
            <button class="btn" type="submit" style="margin: 0 auto;display: block">Review</button>
         </form>
         <?php }  else { ?>

        <div class="btn-group" style="">
            <button class="btn" data-toggle="dropdown">Share</button>
            <button class="btn dropdown-toggle" data-toggle="dropdown">
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li>
                  <a class="share twitter" text="Just <?php echo $lender_view == 1 ? "loaned" : "borrowed"; ?> a <?php echo $transaction["TITLE"] ?> on @qhojonyc!" href="javascript: void(0);">Twitter</a>
              </li>
            </ul>
        </div>
         
         <?php } ?>
     </td>
 </tr>

<?php } endif; ?>

