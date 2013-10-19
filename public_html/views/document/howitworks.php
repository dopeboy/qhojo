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

    <div class='row-fluid'>
        <div class='offset1 span10'>
                <div id="myCarousel" class="carousel slide" data-pause='hover'>
                  <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                    <li data-target="#myCarousel" data-slide-to="3"></li>
                  </ol>
                  <!-- Carousel items -->
                  <div class="carousel-inner">
                    <div class="active item"><img src='/img/Slide1.jpg'></div>
                    <div class="item"><img src='/img/Slide2.jpg'></div>
                    <div class="item"><img src='/img/Slide3.jpg'></div>
                    <div class="item"><img src='/img/Slide4.jpg'></div>
                  </div>
                  <!-- Carousel nav -->
                  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
                </div>  
        </div>
    </div>
    

            
</div>

<div id="lender-protection" class="sheet">
    <h1 class="text-center">Lender Protection Policy</h1>
    <hr>
    <div class='row-fluid'>
        <div class='span10 offset1'>
            <img id='shield' src='/img/blue-security-shield.svg' style=''>
            <div id='lp-text' style=''>
                Whatever happens to the item, we've got you covered. In the event of damage or theft, we will reimburse you up to the full amount that you specified when listing the item. 
            </div>
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
            
            <div class='question'>
                <h4>What's the longest I can borrow an item for?</h4>
                <p>
                    You can borrow an item up to 7 days.
                </p>            
            </div>            

        </div>
    </div>
</div>


<script src="/js/document/howitworks.js"></script>

