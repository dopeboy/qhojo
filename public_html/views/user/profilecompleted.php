<title>Qhojo - Profile Complete</title>

<div class="sheet" id="" style="">

    <legend>Profile Completed</legend>

    <p>
        Thank you <?php echo $_SESSION["USER"]["FIRST_NAME"] ?>. Your profile is now complete. 
    </p>
    
    <?php if ($this->state == 0) { ?>
    <p>
        To go back to your user profile, <a href="/user/index/<?php echo $_SESSION["USER"]["USER_ID"] ?>">click here</a>.
    </p>
    
    <p>
        To continue browsing, <a href="/item/search">click here</a>.
    </p>
    <?php } else if ($this->state == 1) { ?>
    
    <p>
        You had one or more pending transactions that are now complete. To see the new changes, <a href="/user/dashboard#borrowing">click here</a> to go back to your dashboard.
    </p>
    
    <?php } ?>
</div>