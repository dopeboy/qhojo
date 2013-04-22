<head>
<!--	<script type="text/javascript" src="/js/item/feedback.js"></script>-->
        <script type="text/javascript" src="/js/jquery.rating.pack.js"></script>
<!--         <link rel="stylesheet" type="text/css" href="/css/item/feedback.css" media="screen" />  -->
         <link rel="stylesheet" type="text/css" href="/css/jquery.rating.css" media="screen" />  
</head>

<title>qhojo - Rental Request Accepted</title>

<div id="masterdiv">
    
    <div id="mainheading">
        Rental Request Accepted
    </div>
    <hr/>
    Congratulations <?php echo $viewmodel['LENDER_FIRST_NAME'] ?>! You have accepted <?php echo $viewmodel['BORROWER_FIRST_NAME'] ?>'s rental request. Your item, <a href="/item/index/<?php echo $viewmodel['ITEM_ID'];?>"><?php echo $viewmodel['TITLE'];?></a>, is now reserved for <u><?php echo $viewmodel['DURATION'];?> day(s)</u>. 
    <br/><br/>
    Your confirmation code is: <b><?php echo $viewmodel['CONFIRMATION_CODE'] ?></b>. You'll need this later on (we've emailed this whole page to you so don't worry about writing it down).
    <br/><br/>
    <div class="subheading">
        Next steps
    </div>
    <br/>
    1) Over email, arrange to meet with <?php echo $viewmodel['BORROWER_FIRST_NAME'] ?>. Here's <?php echo $viewmodel['BORROWER_FIRST_NAME'] ?>'s email address for reference: <a href="mailto: <?php echo $viewmodel['BORROWER_EMAIL_ADDRESS'] ?>"><?php echo $viewmodel['BORROWER_EMAIL_ADDRESS'] ?></a>
    <br/><br/>
    2) Once you guys meet, <?php echo $viewmodel['BORROWER_FIRST_NAME'] ?> will check out your item. Once satisfied, <?php echo $viewmodel['BORROWER_FIRST_NAME'] ?> will confirm to qhojo via text message.
    <br/><br/>
    3) We'll pass on this confirmation to you via text message. <b>Only hand the item over once you've received this confirmation from us.</b>
    At this point, the rental period has started and <?php echo $viewmodel['BORROWER_FIRST_NAME'] ?> will be responsible to bring your item back after the agreed upon duration. 
    <br/><br/>
    <div class="subheading">
        Still confused?
    </div>   
    <br/>
    Check out our <a href="/document/howitworks/">how-it-works guide</a>.
</div>