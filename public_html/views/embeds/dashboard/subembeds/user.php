<a href="/user/index/<?php echo $lender_view==1 ? $transaction['BORROWER_ID'] : $transaction['LENDER_ID']; ?>">
    <?php echo $lender_view==1 ? $transaction['BORROWER_NAME'] :  $transaction['LENDER_NAME'];?>
</a>