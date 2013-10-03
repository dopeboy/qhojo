<?php 

global $lender_number, $borrower_number;

$formatted_lender_number =  substr($lender_number, 0, 3) . '-' . substr($lender_number, 3,3) . '-' . substr($lender_number, 6,4);
$formatted_borrower_number =  substr($borrower_number, 0, 3) . '-' . substr($borrower_number, 3,3) . '-' . substr($borrower_number, 6,4);

?>

<title>Qhojo - How it works / FAQ</title>

<div id='hiw' class="sheet">
    <h1 class="text-center">How it works</h1>
    <hr>
    <table id="steps" class="table table-striped">
        <thead >
            <tr  >
                <th style="" class="lender">
                <h2>Lender</h2>
                </th>
                <th class="step">
                    <h2>Step</h2>
                </th>
                <th class="borrower">
                    <h2>Borrower</h2>
                </th>
            </tr>
        </thead>
        
        <tbody>
            <tr style='height: 10px'>
                
            </tr>
            <tr style="" class="">
                <td class="left-cell" style="">
                    <div class="tag left" style="">
                        <div class="block" style="">
                            <h3>List Item</h3>
                            <p>
                                Specify a borrow rate, a hold value, and toss in some descriptive details. Then post it on Qhojo (for free!).
                            </p>   
                        </div>
                        <div class="arrow" style=""></div>
                    </div>
                </td>
                <td class="middle-cell">
                    <h1>1</h1>
                </td>
                <td  class="right-cell">                   
                </td>
            </tr>  
            
            <tr style="" class="">
                <td class="left-cell" style="">
              
                </td>
                <td class="middle-cell">
                    <h1>2</h1>
                </td>
                <td class="right-cell">
                    <div class="tag right">
                        <div class="arrow"></div>
                        <div class="block">
                            <h3>Request Item</h3>
                            <p>
                                Search for an item to borrow. Once you find it, submit a request to borrow it for a specific duration.
                            </p>      
                        </div>
                    </div>
                </td>
            </tr>
            
            <tr style="" class="">
                <td class="left-cell" style="">
                    <div class="tag left" style="">
                        <div class="block" style="">
                            <h3>Approve Request</h3>
                            <p>
                                Sort through the borrow requests you have received and approve one. If you decide to change your mind later on, you can cancel your transaction up until 24 hours before the start of the borrow duration.
                            </p>   
                        </div>
                        <div class="arrow" style=""></div>
                    </div>
                </td>
                <td class="middle-cell">
                    <h1>3</h1>
                </td>
                <td  class="right-cell">                   
                </td>
            </tr>      
            
            <tr style="" class="">
                <td class="left-cell" style="">
              
                </td>
                <td class="middle-cell">
                    <h1>4</h1>
                </td>
                <td class="right-cell">
                    <div class="tag right">
                        <div class="arrow"></div>
                        <div class="block">
                            <h3>Pick Up</h3>
                            <p>
                                Schedule a time and place with the lender to pick up the item. Once you meet them, verify that the item is in the same condition as listed on Qhojo. Then, text your confirmation code to our number: <a href="tel:<?php echo $borrower_number; ?>"><?php echo $formatted_borrower_number; ?></a>. We'll place a hold on your credit card for the hold amount.
                            </p>      
                        </div>
                    </div>
                </td>
            </tr>     
            
            <tr style="" class="">
                <td class="left-cell" style="">
                    <div class="tag left" style="">
                        <div class="block" style="">
                            <h3>Release Item</h3>
                            <p>
                                You'll receive a text message from us confirming that the borrower is OK with starting the transaction. At this point, you can hand the item over to the borrower.
                            </p>   
                        </div>
                        <div class="arrow" style=""></div>
                    </div>
                </td>
                <td class="middle-cell">
                    <h1>5</h1>
                </td>
                <td  class="right-cell">                   
                </td>
            </tr>         
            
            <tr style="" class="">
                <td class="left-cell" style="">
              
                </td>
                <td class="middle-cell">
                    <h1>6</h1>
                </td>
                <td class="right-cell">
                    <div class="tag right">
                        <div class="arrow"></div>
                        <div class="block">
                            <h3>Use Item</h3>
                            <p>
                                Rock out with your borrowed gear! 
                            </p>
                            <p>
                               Also schedule a time and place with the lender to return the item on the due date. Don't be late or else you'll be subject to our <a href='#late'>late policy</a>.
                            </p>
                        </div>
                    </div>
                </td>
            </tr>      
            
            <tr style="" class="">
                <td class="left-cell" style="">
                    <div class="tag left" style="">
                        <div class="block" style="">
                            <h3>Verify Item</h3>
                            <p>
                                Inspect the item to make sure it is OK. Then, text your confirmation code to our number: <a href="tel:<?php echo $lender_number;?>"><?php echo $formatted_lender_number; ?></a>. We'll send you the money you are owed minus a <a href="/document/fees">small transaction fee</a> to your PayPal account. 
                            </p>   
                            
                            <p>
                                In the event that there is damage, do not send the text message. Instead, <a href='#damage'>submit a damage report</a>.
                            </p>
                        </div>
                        <div class="arrow" style=""></div>
                    </div>
                </td>
                <td class="middle-cell">
                    <h1>7</h1>
                </td>
                <td  class="right-cell">                   
                </td>
            </tr>
            
            <tr style="" class="">
                <td class="left-cell" style="">
              
                </td>
                <td class="middle-cell">
                    <h1>8</h1>
                </td>
                <td class="right-cell">
                    <div class="tag right">
                        <div class="arrow"></div>
                        <div class="block">
                            <h3>Return Item</h3>
                            <p>
                                Once you receive a text message from us confirming that the lender is OK with the returned item, return the item to them. We will also release the hold on your credit card and charge you for the borrow duration.
                            </p>
                        </div>
                    </div>
                </td>
            </tr>              
        

        </tbody>
    </table>
    
</div>

<div id='faq' class="sheet">
    <h1 class="text-center">FAQ</h1>
    <hr>
    <div style='padding: 0px 40px'>
        <div class='row-fluid'>
            <div id='faq-content' class='span12'>
                <div class='question'>
                    <h4>What is Qhojo?</h4>
                    <p>
                        Qhojo (pronounced co-joe) is a peer-to-peer platform for borrowing and lending photo and video gear.
                    </p>
                </div>
                
                <div class='question'>
                    <h4>What can I borrow or lend on Qhojo?</h4>
                    <p>
                        Camera bodies, lenses, flashes, and lighting equipment are some examples of the kinds of items you can find on Qhojo. 
                    </p>
                </div>
                
                <div class='question'>
                    <h4>I want to lend something but I'm afraid someone might steal it.</h4>
                    <p>
                        When you list an item on Qhojo, you get to specify the value of the item. When someone borrows your item, we issue a hold on their credit card for the value you specified. If the borrower runs off with the item, the credit card hold is captured and the funds are transferred to you.
                    </p>  
                </div>
                
                <div class='question' id='damage'>
                    <h4>I want to lend something but I'm afraid someone might damage it.</h4>
                    <p>
                        When you list an item on Qhojo, you get to specify the value of the item. When someone borrows your item, we issue a hold on their credit card for the value you specified. 
                    </p>
                    
                    <p>
                        If the item has been seriously damaged, the lender or borrower can submit a damage report through the dashboard. We will work with the both of you to resolve the situation. If we determine the item needs repair, those costs will come out of the hold issued on the borrower's credit card.
                    </p>                    
                </div>
                
                <div class='question' id='late'>
                    <h4>What happens if a borrower returns the item late?</h4>
                    <p>
                        They will be charged double the borrow rate for each day the item is late.
                    </p>            
                </div>
                
                <div class='question'>
                    <h4>What is the maximum hold value I can specify when listing an item?</h4>
                    <p>
                        The maximum hold value you can specify is $2500.
                    </p>            
                </div>
                
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/css/document/howitworks.css">