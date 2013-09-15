<?php if ($code != 0 && User::isUserSignedIn()) { ?>

<div class="alert alert-info">
    <strong>Reminder: </strong>
    Hey <?php echo $_SESSION["USER"]["FIRST_NAME"] ?>, you still need to complete your user profile. Click <a href="/user/extrasignup/null/0?return=<?php echo $_SERVER['REQUEST_URI']; ?>">here</a> to do it.
     <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<?php } ?>

    