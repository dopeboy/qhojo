<title>Qhojo - Accept Request</title>

<?php 
$transaction = reset($viewmodel); // first element     
?>

<div class="sheet" id="accept">

<?php if ($this->state == 1) { ?>

    <legend>
        Reserved - <a href="/item/index/<?php echo $transaction['ITEM_ID'] ?>"><?php echo $transaction["TITLE"] ?></a>
    </legend>

    <p><?php echo $_SESSION["USER"]["FIRST_NAME"] ?>, you have successfully accepted <?php echo $transaction['BORROWER_FIRST_NAME'] ?>'s request to borrow your item, <a href="/item/index/<?php echo $transaction['ITEM_ID'] ?>"><?php echo $transaction["TITLE"] ?></a>. </p>
    
    <p>We've sent both you and <?php echo $transaction['BORROWER_FIRST_NAME'] ?> an email. Please check it for further instructions.</p>
    
    <p>To track your transaction on your dashboard, <a href='/user/dashboard#lending#<?php echo $transaction['TRANSACTION_ID'] ?>'>click here</a>.</p>
    
<?php } ?>
    
</div>
    

