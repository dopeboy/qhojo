<?php if ($code != 0 && User::isUserSignedIn()) { ?>

<div class="alert extra-fields-banner">
    <strong>Reminder: </strong>
    Hey <?php echo $_SESSION["USER"]["FIRST_NAME"] ?>, you still need to complete your payment details. Click <a href="/user/completeprofile/null/0?return=<?php echo $_SERVER['REQUEST_URI']; ?>">here</a> to do it.
     <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<?php } ?>

    