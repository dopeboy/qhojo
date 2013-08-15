<div class="statuses">
    <?php 

       $requested = $reserved = $exchanged = $returned = $reviewed = '0.1';

       foreach ($transaction['HIST'] as $key=>$detail)
       {            
           if ($detail['STATE_B_ID'] == 200)
               $requested = '1';                    
           if ($detail['STATE_B_ID'] == 300)
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

     ?>

   <i class="icon-envelope icon-2x" style="opacity: <?php echo $requested;?>"></i>
   <i class="icon-calendar icon-2x" style="opacity: <?php echo $reserved;?>"></i>
   <i class="icon-play icon-2x" style="opacity: <?php echo $exchanged;?>"></i>
   <i class="icon-rotate-left icon-2x" style="opacity: <?php echo $returned;?>"></i> 
   <i class="icon-comment icon-2x" style="opacity: <?php echo $reviewed;?>"></i>
</div>
<div id="hist-<?php echo $transaction['TRANSACTION_ID']; ?>" tid="<?php echo $transaction['TRANSACTION_ID']; ?>" class="<?php if ($current == 0 && $awaiting_review == 0) { ?> collapse out <?php } ?>">
    <ul class="actions-list">
      <?php 

         foreach ($transaction['HIST'] as $key=>$detail)
         {
             $d = new DateTime($detail['ENTRY_DATE']);
             echo "<li>" . ($key == count($transaction['HIST'])-1 ? "<strong>" : "") . $detail['SUMMARY'] . ' (<em>' . $d->format('m/d - g:i A') . '</em>)' . ($key == count($transaction['HIST'])-1 ? "</strong>" : "") . "</li>";
         } 

       ?>
    </ul>  
</div>
<?php if ($current == 0 && $awaiting_review == 0) { ?>
    <a id="collapse-link-<?php echo $transaction['TRANSACTION_ID']; ?>" class="show-hide-history" href="javascript: void(0)" data-toggle="collapse" data-target="#hist-<?php echo $transaction['TRANSACTION_ID']; ?>">
        [+] Show History
    </a>
<?php } ?>
