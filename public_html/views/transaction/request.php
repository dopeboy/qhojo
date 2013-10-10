<title>Qhojo - Submit Borrow Request</title>

<link rel="stylesheet" href="/css/datepicker.css">
<link rel="stylesheet" href="/css/transaction/request.css">

<?php
    if ($this->state == 2) 
    { 
        $code = $viewmodel["USER"]["NEED_EXTRA_FIELDS"];
        require(dirname(dirname(__FILE__)) . '/embeds/extra_fields.php'); 
    }
?>

<div class="sheet">
    <?php if ($this->state == 0) { ?>
        <form class="form-submit" id="request" action="/transaction/request/<?php echo $viewmodel['ITEM']['ITEM_ID'] ?>/1" method="post">
            <legend>Borrow Request - <a href="/item/index/<?php echo $viewmodel['ITEM']['ITEM_ID'] ?>"><?php echo $viewmodel['ITEM']['TITLE'] ?> </a></legend>
            <div class="section">
                <h3>Borrow Duration</h3>
                    <div class="controls controls-row">
                        <input class="span2" id="date-start" name="date-start" type="text" placeholder="Pick a Start Date" data-date="12-02-2012" data-date-format="mm/dd/yyyy">
                        <input class="span2" id="date-end" name="date-end" type="text" placeholder="Pick an End Date" data-date="12-02-2012" data-date-format="mm/dd/yyyy" style="">
                    </div>

                    <input type="hidden" name="date" id="date" value="" />
            </div>

            <div class="section">
                <h3>Cost</h3>
                <div class="" >  
                    $<span id="rental-rate"><?php echo $viewmodel['ITEM']['RATE'] ?></span> (Daily Rate) x <soan id="rentalDuration">?</soan> days = $<span id="total">?</span>
                </div>        
            </div>

            <div class="section">
                <h3>Security Hold</h3>
                <div class="" >  
                    There will be a $<?php echo $viewmodel['ITEM']['DEPOSIT'] ?> hold placed on your credit card for the borrow duration. 
                </div>        
            </div>

            <div class="section">
                <h3>Message</h3>
                <div class="" >  
                    <textarea class="required" style="width: 500px" name="message" id="message" placeholder="Briefly explain who are you and why you need the gear."></textarea>
                </div>        
            </div>        

            <button type="submit" class="btn btn-large btn-primary">Submit Request</button>
         </form>
    <?php } else if ($this->state == 2) { ?>
        <legend>
            Request Submitted
        </legend>    
        <div>
            <p>Thanks <?php echo $_SESSION["USER"]["FIRST_NAME"] ?>! You have requested <a href="/item/index/<?php echo $viewmodel["ITEM"]["ITEM_ID"] ?>"><?php echo $viewmodel["ITEM"]["TITLE"] ?></a> successfully. 
            We'll notify you as soon as the lender, <a href="/user/index/<?php echo $viewmodel["ITEM"]["LENDER_ID"] ?>"><?php echo $viewmodel["ITEM"]["LENDER_FIRST_NAME"] ?></a>, has responded to your request.</p>
            <?php if ($code == true): ?>
            <p>We noticed you haven't filled out your payment details. To increase the chances of your request getting accepted, please fill them out by clicking the banner at the top of this page.</p>
            <?php else:  ?>
                <p>To track your request on your dashboard, <a href='/user/dashboard#borrowing'>click here</a>.</p>
            <?php endif; ?>
        </div>        
    <?php } ?>
</div>


<script src="/js/bootstrap-datepicker.js"></script>
<script src="/js/transaction/request.js"></script>
