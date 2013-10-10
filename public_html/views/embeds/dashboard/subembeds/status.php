<?php if ($current == 0)  { ?>

<div class="statuses">
    <?php 

       $requested = $reserved = $exchanged = $returned = $reviewed = '0.1';
        global $item_thumb_subdir; 
        
       foreach ($transaction['HIST'] as $key=>$detail)
       {            
           if ($detail['STATE_B_ID'] == 200)
               $requested = '1';                    
           else if ($detail['STATE_B_ID'] == 300)
               $reserved = '1';
           else if ($detail['STATE_B_ID'] == 500)
               $exchanged = '1';
           else if ($detail['STATE_B_ID'] == 700)
               $returned = '1';
           else if ($lender_view == 1)
           {
               if ($detail['STATE_B_ID'] == 900 || $detail['STATE_B_ID'] == 1200)
                   $reviewed = '1'; 
           }
           
           else
           {
               if ($detail['STATE_B_ID'] == 1000 || $detail['STATE_B_ID'] == 1100)
                   $reviewed = '1';                
           }
       } 
       
//       $exchanged_color = ""; 
//       $returned_color = "";
//       
//       if($transaction['FINAL_STATE_ID'] == 300) 
//       {
//           $exchanged = 1;
//           $exchanged_color = "red";
//       }
//       
//       else if($transaction['FINAL_STATE_ID'] == 500) 
//       {
//           $returned = 1;
//           $returned_color = "red";           
//       }
       
//       else if($transaction['FINAL_STATE_ID'] == 700) 
//       {
//           $reviewed = 1;
//           $color = "red";           
//       }

     ?>

   <i class="icon-envelope icon-2x" style="opacity: <?php echo $requested;?>"></i>
   <i class="icon-calendar icon-2x" style="opacity: <?php echo $reserved;?>"></i>
   <i class="icon-play icon-2x" style="opacity: <?php echo $exchanged;?>; color: <?php //echo $exchanged_color;?>"></i>
   <i class="icon-rotate-left icon-2x" style="opacity: <?php echo $returned;?>; color: <?php //echo $returned_color;?>"></i>
   <i class="icon-comment icon-2x" style="opacity: <?php echo $reviewed;?>; color: <?php //echo $color;?>"></i>
</div>

<?php } ?>

<?php if ($current == 0)  { ?>
    <div id="hist-<?php echo $transaction['TRANSACTION_ID']; ?>" tid="<?php echo $transaction['TRANSACTION_ID']; ?>" class="<?php if ($current == 0 && $awaiting_review == 0) { ?> collapse out <?php } ?>">
        <ul class="actions-list">

          <?php 

            foreach ($transaction['HIST'] as $key=>$detail)
            {
                // Skip pending
                if ($detail['STATE_A_ID'] != 250)
                {
                   $d = new DateTime($detail['ENTRY_DATE']);
                   echo "<li>" . ($key == count($transaction['HIST'])-1 ? "<strong>" : "") . $detail['SUMMARY'] . ' (<em>' . $d->format('m/d - g:i A') . '</em>)' . ($key == count($transaction['HIST'])-1 ? "</strong>" : "") . "</li>";
                }
            } 

           ?>
        </ul> 


    </div>

        <?php if ($awaiting_review == 0) { ?>
        <a id="collapse-link-<?php echo $transaction['TRANSACTION_ID']; ?>" class="show-hide-history" href="javascript: void(0)" data-toggle="collapse" data-target="#hist-<?php echo $transaction['TRANSACTION_ID']; ?>">
            [+] Show History
        </a>
        <?php } ?>
<?php } ?>

<?php  if ($current == 1) { ?>
    <div id='next-step-<?php echo $transaction['TRANSACTION_ID']; ?>' tid="<?php echo $transaction['TRANSACTION_ID']; ?>">
    
        <?php
        
        global $borrower_number, $lender_number;
        
        $formatted_lender_number =  substr($lender_number, 0, 3) . '-' . substr($lender_number, 3,3) . '-' . substr($lender_number, 6,4);
        $formatted_borrower_number =  substr($borrower_number, 0, 3) . '-' . substr($borrower_number, 3,3) . '-' . substr($borrower_number, 6,4);
        
        if ($lender_view == 0)
        {
            if ($transaction['FINAL_STATE_ID'] == 300)
            {
            ?>
        
            <table class='next-step'>
                <tr class="entry">  
                    <td class="left" style=''><i class="icon-exclamation-sign icon-2x red" style=""></i></td>
                    <td class="right" style=''><?php echo "Meet with " . $transaction['LENDER_FIRST_NAME'] . " on " . date("m/d", strtotime($transaction["REQ"]["START_DATE"])) . " and inspect the item. If it is OK, then text your confirmation code (<strong>" . $transaction["RESERVATION"]["CONFIRMATION_CODE"] . "</strong>) to our Qhojo number: " . $formatted_borrower_number; ?></td>
                </tr>
                <tr class="explanation">
                    <td class="left" style=''></td>
                    <td class="right" style=''> <small><a class="why" href="javascript: void(0);" data-toggle="tooltip" title="By sending this text message, you confirm to us and the lender that you are OK with the state of the item and are ready to borrow it. This also tells us to put the hold on your credit card." data-trigger="hover" data-placement="right">Why do I have to do this?</a></small></td>
                </tr>
            </table>
            
            <?php 
            }

            else if ($transaction['FINAL_STATE_ID'] == 500)
            {
            ?>
        
            <table class='next-step'>
                <tr class="entry">  
                    <td class="left" style=''><i class="icon-exclamation-sign icon-2x red" style=""></i></td>
                    <td class="right" style=''><?php echo "Meet with " . $transaction['LENDER_FIRST_NAME'] . " on " . date("m/d", strtotime($transaction["REQ"]["END_DATE"])) . " and wait for them to text us. Once we have received their text, we will text you at which point you can return the item to " . $transaction['LENDER_FIRST_NAME'] . ". <strong>Do not return the item until you have received our text.</strong>" ?></td>
                </tr>
                <tr class="explanation">
                    <td class="left" style=''></td>
                    <td class="right" style=''> <small><a class="why" href="javascript: void(0);" data-toggle="tooltip" title="It's important to wait for our text message because we need to confirm that the lender is OK with the returned item. This also tells us to release the hold on your credit card." data-trigger="hover" data-placement="right">Why do I have to do this?</a></small></td>
                </tr>
            </table>
            
            <?php 
            }
        }

        else if ($lender_view == 1)
        {
            if ($transaction['FINAL_STATE_ID'] == 300)
            {
            ?>
        
            <table class='next-step'>
                <tr class="entry">  
                    <td class="left" style=''><i class="icon-exclamation-sign icon-2x red" style=""></i></td>
                    <td class="right" style=''><?php echo "Meet with " . $transaction['BORROWER_FIRST_NAME'] . " on " . date("m/d", strtotime($transaction["REQ"]["START_DATE"])) . " and wait for them to text message us. Once they have, we will text message you when to release the item to them. <strong>Do not release the item until you have received our text.</strong>"?></td>
                </tr>
                <tr class="explanation">
                    <td class="left" style=''></td>
                    <td class="right" style=''> <small><a class="why" href="javascript: void(0);" data-toggle="tooltip" title="It's important to wait for our text message to you because we need to confirm that the borrower is OK with starting the transaction and taking on the hold on their credit card. This ensures you are protected under our Lender Protection Policy." data-trigger="hover" data-placement="right">Why do I have to do this?</a></small></td>
                </tr>
            </table>
            
            <?php 
            }

            else if ($transaction['FINAL_STATE_ID'] == 500)
            {
            ?>
        
            <table class='next-step'>
                <tr class="entry">  
                    <td class="left" style=''><i class="icon-exclamation-sign icon-2x red" style=""></i></td>
                    <td class="right" style=''><?php echo "Meet with " . $transaction['BORROWER_FIRST_NAME'] . " on " . date("m/d", strtotime($transaction["REQ"]["END_DATE"])) . " and inspect the item. If it is OK, then text your confirmation code (<strong>" . $transaction["RESERVATION"]["CONFIRMATION_CODE"] . "</strong>) to our Qhojo number: " . $formatted_lender_number; ?></td>
                </tr>
                <tr class="explanation">
                    <td class="left" style=''></td>
                    <td class="right" style=''> <small><a class="why" href="javascript: void(0);" data-toggle="tooltip" title="By sending this text message, you confirm to us and the borrower that you are OK with the state of the returned item and are ready to close the transaction. This also tells us to release the hold on the borrower's credit card." data-trigger="hover" data-placement="right">Why do I have to do this?</a></small></td>
                </tr>
            </table>
            
            
            <?php 
            }
        }
        
        ?>
    </div>
<?php } ?>



