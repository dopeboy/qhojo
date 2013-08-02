<script src="/js/bootstrap-datepicker.js"></script>
<script src="/js/item/request.js"></script>


<div class="sheet">
    <legend>Rental Request - <a href="#">Canon 7D</a></legend>

    <div class="section">
        <h3>Rental Duration</h3>
        <div class="controls controls-row" >  
            <input class="span2" id="dpd1" type="text" placeholder="Pick a Start Date" data-date="12-02-2012" data-date-format="mm/dd/yyyy">
            <input class="span2" id="dpd2" type="text" placeholder="Pick an End Date" data-date="12-02-2012" data-date-format="mm/dd/yyyy" style="">
        </div>        
    </div>
    
    <div class="section">
        <h3>Cost</h3>
        <div class="" >  
            $<span id="rental-rate">25</span> (Daily Rate) x <soan id="rentalDuration">?</soan> days = $<span id="total">?</span>
        </div>        
    </div>
    
    <div class="section">
        <h3>Security Hold</h3>
        <div class="" >  
            There will be a $1500 hold placed on your credit card for the rental duration. 
        </div>        
    </div>
    
    <button type="submit" class="btn btn-large btn-primary" rel="tooltip" title="first tooltip">Submit Request</button>


    

        

    


</div>

<link rel="stylesheet" href="/css/datepicker.css">


<link rel="stylesheet" href="/css/item/request.css">

