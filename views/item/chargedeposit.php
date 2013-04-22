<title>qhojo - confirm deposit charge</title>

<div id="masterdiv">
    
    <?php if ($this->state == 0) { ?>
    
    <div id="mainheading">Charge deposit - <a href="/item/index/<?php echo $viewmodel['ITEM_ID'];?>"><?php echo $viewmodel['TITLE'] ?></a></div>
    <hr/>
    This action will charge the borrower, <?php echo $viewmodel['BORROWER_FIRST_NAME'] ?>, the full deposit value ($<?php echo $viewmodel['DEPOSIT'] ?>), 
    and transfer it to the lender <?php echo $viewmodel['LENDER_FIRST_NAME'] ?>.
    <br/>
    <br/>
    Press this button if you are absolutely sure you want to commit this action:
    <br/>
    <br/>
    <form id="myForm" action="/item/chargedeposit/<?php echo $viewmodel['ITEM_ID'];?>/1" style="margin: 0">
        <input id="submit" type="submit" value="submit"><img id="loading" src="/img/ajax-loader.gif" style="display:none">
    </form>
    <?php } ?>
</div>
