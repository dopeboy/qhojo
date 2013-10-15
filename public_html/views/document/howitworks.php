<?php 

global $lender_number, $borrower_number;

$formatted_lender_number =  substr($lender_number, 0, 3) . '-' . substr($lender_number, 3,3) . '-' . substr($lender_number, 6,4);
$formatted_borrower_number =  substr($borrower_number, 0, 3) . '-' . substr($borrower_number, 3,3) . '-' . substr($borrower_number, 6,4);

?>

<link rel="stylesheet" href="/css/document/howitworks.css">

<title>Qhojo - How it works / FAQ</title>

<div id='hiw' class="sheet">
    <h1 class="text-center">How it works</h1>
    <hr>

            
    <div id="myCarousel" class="carousel slide" style='margin: 0px 60px' data-interval='' data-pause="hover">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
            <li data-target="#myCarousel" data-slide-to="3"></li>
        </ol>

        <div class='row-fluid'>
            <div id='' class='span2'>
                <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
            </div>

            <div id='' class='span8'>
                <div class="carousel-inner" style=''>
                    <div class="active item">
                        <img class='text-center' src='/img/Step1.jpg'>
                        <div style='margin-top: 20px'>
                            <h3 class="media-heading">Step 1: Search</h3>
                            Search for gear to borrow.
                        </div>
                    </div>
                    <div class="item">
                        <img class='text-center' src='/img/Step2.jpg'>
                        <div style='margin-top: 20px'>
                            <h3 class="media-heading">Step 2: Request</h3>
                            <div class='borrower-hiw'>
                                <h5>If you're a borrower...</h5>
                                <p>Once you found the gear you want, submit a request to borrow it for a specific duration.</p>
                            </div>
                            
                            <div class='lender-hiw'>
                                <h5>If you're a lender...</h5>
                                <p>Sort through the borrow requests you have received and approve one. If you decide to change your mind later on, you can cancel your transaction up until 24 hours before the start of the borrow duration.</p>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <img class='text-center' src='/img/Step3.jpg'>
                        <div style='margin-top: 20px'>
                            <h3 class="media-heading">Step 3: Pick up</h3>
                            <div class='borrower-hiw'>
                                <h5>If you're a borrower...</h5>
                                <p>Schedule a time and place with the lender to pick up the item. Once you meet them, verify that the item is in the same condition as listed on Qhojo. Then, <span class='step'>(1)</span> text your confirmation code to our number: <a href="tel:<?php echo $borrower_number; ?>"><?php echo $formatted_borrower_number; ?></a>. We'll place a hold on your credit card for the hold amount.</p>
                            </div>
                            
                            <div class='lender-hiw'>
                                <h5>If you're a lender...</h5>
                                <p><span class='step'>(2)</span> You'll receive a text message from us confirming that the borrower is OK with starting the transaction. At this point, <span class='step'>(3)</span> you can hand the item over to the borrower.</p>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <img class='text-center' src='/img/Step4.jpg'>
                        <div style='margin-top: 20px'>
                            <h3 class="media-heading">Step 4: Drop off</h3>
                            <div class='borrower-hiw'>
                                <h5>If you're a borrower...</h5>
                                    <p>Let the borrower inspect the item. <span class='step'>(2)</span> Once you receive a text message from us confirming that the lender is OK with the returned item, <span class='step'>(3)</span> return the item to them. We will also release the hold on your credit card and charge you for the borrow duration.</p>
                            </div>
                            
                            <div class='lender-hiw'>
                                <h5>If you're a lender...</h5>
                                
                            <p>
                                Inspect the item to make sure it is OK. Then, <span class='step'>(1)</span> text your confirmation code to our number: <a href="tel:<?php echo $lender_number;?>"><?php echo $formatted_lender_number; ?></a>. We'll send you the money you are owed minus a <a href="/document/fees">small transaction fee</a> to your PayPal account. 
                            </p>   
                            
                            <p>
                                In the event that there is damage, do not send the text message. Instead, <a href='#damage'>submit a damage report</a>.
                            </p>                                
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>

            <div id='' class='span2'>
                <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
            </div>
            
        </div>

    </div>  
            
</div>

<div id="lender-protection" class="sheet">
    <h1 class="text-center">Lender Protection Policy</h1>
    <hr>
    <div class='row-fluid'>
        <div id='faq-content' class='span10 offset1'>    
            sdfsdfsdf
        </div>
    </div>
</div>

<div id='faq' class="sheet">
    <h1 class="text-center">FAQ</h1>
    <hr>
    <div class='row-fluid'>
        <div id='faq-content' class='span10 offset1'>
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
                    As of now, the maximum hold value we allow is $2500.
                </p>            
            </div>

        </div>
    </div>
</div>


<script src="/js/document/howitworks.js"></script>

