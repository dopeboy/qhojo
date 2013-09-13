<script src="/js/bootstrap-datepicker.js"></script>
<script src="/js/item/request.js"></script>
<link rel="stylesheet" href="/css/datepicker.css">
<link rel="stylesheet" href="/css/item/request.css">

<?php
    $code = $viewmodel["USER"]["NEED_EXTRA_FIELDS"];
    require(dirname(dirname(__FILE__)) . '/embeds/extra_fields.php'); 
?>

<div class="sheet">
    <?php if ($this->state == 0) { ?>
        <form class="form-submit" id="request" action="/item/request/<?php echo $viewmodel['ITEM']['ITEM_ID'] ?>/1" method="post">
            <legend>Rental Request - <a href="/item/index/<?php echo $viewmodel['ITEM']['ITEM_ID'] ?>"><?php echo $viewmodel['ITEM']['TITLE'] ?> </a></legend>
            <div class="section">
                <h3>Rental Duration</h3>
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
                    There will be a $<?php echo $viewmodel['ITEM']['DEPOSIT'] ?> hold placed on your credit card for the rental duration. 
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
            Thanks <?php echo $_SESSION["USER"]["FIRST_NAME"] ?>! You have requested <a href="/item/index/<?php echo $viewmodel["ITEM_ID"] ?>"><?php echo $viewmodel["TITLE"] ?></a> successfully. 
            We'll notify you as soon as the lender, <a href="/user/index/<?php echo $viewmodel["LENDER_ID"] ?>"><?php echo $viewmodel["LENDER_FIRST_NAME"] ?></a>, has responded to your request.
        </div>        
    <?php } ?>
</div>



