<?php global $demo; ?>

<div class="sheet">
    <legend>
        Oops
    </legend>
    <p>
        <?php echo $viewmodel->message; ?>
    </p>
    <?php if ($demo) { ?>
    <p>Following only shows in non-production environments to help with debugging:</p>
    <p><? if ($demo) echo $viewmodel; ?></p>
    <?php } ?>
</div>