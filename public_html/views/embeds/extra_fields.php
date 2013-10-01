<?php if ($code != 0 && User::isUserSignedIn()) { ?>

<div class="alert extra-fields-banner">
    <strong>Reminder: </strong>
    Hey <?php echo $_SESSION["USER"]["FIRST_NAME"] ?>, you still need to <a href="/user/completeprofile/null/0?return=<?php echo $_SERVER['REQUEST_URI']; ?>">complete your payment details.</a>
     <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<?php } ?>

    