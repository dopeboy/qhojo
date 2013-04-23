
<title>qhojo - fees</title>

<div id="masterdiv">
    <div id="mainheading">
        Fees
    </div>
    <hr/>
    <div class="subheading">
    For lenders
    </div>
    <div class="subcontent">
        <b><?php global $transaction_fee_variable, $transaction_fee_fixed; echo $transaction_fee_variable*100?>% + $<?php echo number_format($transaction_fee_fixed,2); ?></b> off each successful rental transaction.
    </div>
    <br/>
    <div class="subheading">
    For borrowers
    </div>    
    <div class="subcontent">
        No fees.
    </div>
</div>