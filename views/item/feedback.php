<div class="sheet">
    <legend>Feedback - <a href="#">Canon 7D</a></legend>
    <h3>Item Details</h3>
    <div class="row-fluid" style="margin-bottom: 20px">
        <div class="span3">
            <img src="/img/7d_big.png">
        </div>
        <div class="span9">
            <p class="transaction-details"><strong>Lender</strong>: Manish</p> 
            <p class="transaction-details"><strong>Rental Rate</strong>: $25 / day</p>
            <p class="transaction-details"><strong>From</strong>: 07/25</p>
            <p class="transaction-details"><strong>To</strong>: 07/27</p>
        </div>
    </div>
    
    <h3>Rate this transaction</h3>
    <div id="rate-transaction" style="margin-bottom: 20px">
        <input type="hidden" name="option" value="" id="btn-input" />
        <div class="btn-group" data-toggle="buttons-radio">  
          <button id="btn-one" type="button" data-toggle="button" name="option" value="1" class="btn btn-primary"><i class="icon-thumbs-up"></i></button>
          <button id="btn-two" type="button" data-toggle="button" name="option" value="2" class="btn btn-primary"><i class="icon-thumbs-down"></i></button>
        </div>
    </div>
    
    <h3>Tell us more</h3>
    <div id="comment-transaction" style="margin-bottom: 20px">
        <input type="hidden" name="option" value="" id="btn-input" />
        <textarea style="width: 400px" placeholder="How was the lender? How was the item?"></textarea>
    </div>
    
    <button type="submit" class="btn btn-large btn-primary" rel="tooltip" title="first tooltip">Submit Feedback</button>
</div>

<link rel="stylesheet" href="/css/item/feedback.css">
<script src="/js/item/feedback.js"></script>