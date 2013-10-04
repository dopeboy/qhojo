<title>Qhojo - Accept Request</title>

<?php 
$transaction = reset($viewmodel); // first element     
?>

<div class="sheet" id="accept">

<?php if ($this->state == 1) { ?>

    <legend>
        Reserved - <a href="/item/index/<?php echo $transaction['ITEM_ID'] ?>"><?php echo $transaction["TITLE"] ?></a>
    </legend>

    <p><?php echo $_SESSION["USER"]["FIRST_NAME"] ?>, you have successfully accepted <?php echo $transaction['BORROWER_FIRST_NAME'] ?>'s request to rent your item, <a href="/item/index/<?php echo $transaction['ITEM_ID'] ?>"><?php echo $transaction["TITLE"] ?></a>. </p>
    
    <p>Your confirmation code is <strong><?php echo $transaction["RESERVATION"]["CONFIRMATION_CODE"] ?></strong>. Hang on to it as you'll need it later on. Please check your email for further instructions.</p>
    
    <p>You can also go to your <a href='/user/dashboard'>dashboard</a> to track your transaction.</p>
    
<?php } ?>
    
</div>
    

