<head>
	<script type="text/javascript" src="/js/item/reserve.js"></script>
</head>

<div id="masterdiv">
    <?php if ($this->state == 0) { ?>    
        <form id="myForm" action="/item/reserve/<?php echo $viewmodel[0]['ITEM_ID'] ?>/1" method="post" style="padding:0px; margin: 0px">
            <div id="mainheading">
                Reserve - <a href="/item/index/<?php echo $viewmodel[0]['ITEM_ID'];?>"><?php echo $viewmodel[0]['TITLE'];?></a>
            </div> 
            <hr/>
            <div class="subheading">
                Rental Details
            </div> 
            <br/>
            <div style="display:inline-block">
            
                
                <div id="price" style="background-color: rgba(207,207,207, .5);height:230px;padding: 15px; float: left; width: 284px">
                  <div>
                        rental rate
                        <div style="font-size: 250%">$<?php echo $viewmodel[0]['RATE']?> / day</div>
                    </div>
                    <br/>
                    <div>
                        deposit
                        <div style="font-size: 250%">$<?php echo $viewmodel[0]['DEPOSIT']?></div>
                    </div>
                    <br/>
                    <div>   
                        reservation fee
                        <div style="font-size: 250%">$1</div>
                    </div>          
                </div>     
           </div>
            
            <br/>
            <br/>
            <div class="subheading" style="">
                Duration
            </div>
            <br/>
            <div style="width:100%">
                How many days do you want to rent the item for?
                <br/>
                <br/>
                <div style=" max-width:100%; display:table;">
                    <select id="duration" name="duration">
                      <option value="1" >1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                    </select> days
                </div>
            </div>
            <br/>
            <div class="subheading" style="">
                Message
            </div>     
            <br/>
            Send a message to <?php echo $viewmodel[0]['LENDER_FIRST_NAME']; ?> to discuss where and when you plan to pick up the item.
            <br/>
            <br/>
            <div style=" max-width:100%; display:table;">
                Hi <?php echo $viewmodel[0]['LENDER_FIRST_NAME']; ?>,
                <br/>
                <textarea name="message" cols="35" rows="5"></textarea>
                <br/>
                -<?php echo $_SESSION['firstname']  ?>
            </div>
            <br/>
            <div class="subheading" style="">
                Agreement
            </div>       
            <br/>
            <div style=" padding: 15px; background-color: rgba(207,207,207, .5);">
                due now
                <div style="font-size:150%">
                    $1
                </div>
                <br/>
                due after item is returned
                <div style="font-size:150%">
                    $<span id="total">21</span> (rental fee x rent duration)
                </div>
                <br/>
                due if and only if item isn't returned within rental duration
                <div style="font-size:150%">
                    $<?php echo $viewmodel[0]['DEPOSIT']?>
                </div>                         
            </div>
            <br/>
            <input type="checkbox" name="agreement">I agree to (1) pay the fees outlined above and (2) to the terms and conditions of qhojo inc.
            <br/>
            <br/>
            <div style="margin:0px auto; max-width:100%; display:table;">
                <input type="submit" >
            </div>
           
        </form>
    
    <?php  } else if ($this->state == 2) { ?>
    <div id="mainheading">
        Congratulations!
    </div> 
    <hr/>
    Your item, <a href="/item/index/<?php echo $viewmodel[0]['ITEM_ID'];?>"><?php echo $viewmodel[0]['TITLE'];?></a>,  has been reserved.
    <br/>
    <br/>
    <div class="subheading">
        ...now what do I do?
    </div>
    <br/>
    1) Agree with the lender on a time and place to meet via email.
    <br/>
    2) Download the qhojo app onto your phone. When you guys meet, you will need to confirm to the lender through the app that you are ready to borrow the item.
    <br/>
    <br/>
    <div class="subheading">
        Still confused?
    </div>
    <br/>
    Check out our how-to guide here.
    <?php } ?>
</div>