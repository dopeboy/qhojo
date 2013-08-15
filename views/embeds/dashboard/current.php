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
                  <li><a tabindex="-1" href="#">Cancel</a></li>
                  <?php } ?>
                  <li><a tabindex="-1" href="#">Contact</a></li>
                </ul>
            </div>
         </div>
     </td>
 </tr>

<?php } endif; ?>

