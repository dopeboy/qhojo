<title>Qhojo - Pending Request</title>

<?php 
$transaction = reset($viewmodel); // first element     
?>


<div class="sheet" id="pending">
    
    <?php if ($this->state == 1) { ?>
    <legend>
        Pending - <a href="/item/index/<?php echo $transaction['ITEM_ID'] ?>"><?php echo $transaction["TITLE"] ?></a>
    </legend>

    <p><?php echo $_SESSION["USER"]["FIRST_NAME"] ?>, you have successfully accepted <?php echo $transaction['BORROWER_FIRST_NAME'] ?>'s request to rent your item, <a href="/item/index/<?php echo $transaction['ITEM_ID'] ?>"><?php echo $transaction["TITLE"] ?></a>. </p>
    
    <p>Before your item can be formally reserved, <?php echo $transaction['BORROWER_FIRST_NAME'] ?> must complete their user profile. 
    <?php echo $transaction['BORROWER_FIRST_NAME'] ?> has 24 hours to do this until this transaction is cancelled. We've sent an email to the both of you communicating this message as a reminder.
    </p>
    <p>
        You will be notified by email once <?php echo $transaction['BORROWER_FIRST_NAME'] ?> has completed their user profile. Click <a href="/user/dashboard/">here</a> to return to your dashboard.
    </p>
    <?php } ?>
</div>
    

<!--<link rel="stylesheet" href="/css/transaction/review.css">
<script src="/js/transaction/review.js"></script>-->
