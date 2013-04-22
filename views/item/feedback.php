<head>
	<script type="text/javascript" src="/js/item/feedback.js"></script>
        <script type="text/javascript" src="/js/jquery.rating.pack.js"></script>
         <link rel="stylesheet" type="text/css" href="/css/item/feedback.css" media="screen" />  
         <link rel="stylesheet" type="text/css" href="/css/jquery.rating.css" media="screen" />  
</head>

<title>qhojo - Submit Feedback</title>

<div id="masterdiv">
    
    <?php if ($this->state == 0 || $this->state == 1) { ?>
    
    <div id="mainheading">
        Feedback - <a href="/item/index/<?php echo $viewmodel['ITEM_ID'];?>"><?php echo $viewmodel['TITLE'];?></a>
    </div>
    <hr/>
    
    <?php } ?>
    
    <?php if ($this->state == 1) { ?>
    
    <div class="subheading" style="">
        Lender
    </div><br/>
    <img id="profilepicture" src="<?php echo $viewmodel['LENDER_PICTURE_FILENAME'] == null ? "/img/stock_user_profile.jpg" : "/uploads/user/" . $viewmodel['LENDER_PICTURE_FILENAME']?>">
    <div style="font-size:250%">
        <a href="/user/index/<?php echo $viewmodel['LENDER_ID']; ?>"><?php echo $viewmodel['LENDER_FIRST_NAME']; ?></a>
    </div>
    <?php } else if ($this->state == 0) { ?>
    <div class="subheading" style="">
        Borrower
    </div><br/>
    <img id="profilepicture" src="<?php echo $viewmodel['BORROWER_PICTURE_FILENAME'] == null ? "/img/stock_user_profile.jpg" : "/uploads/user/" . $viewmodel['BORROWER_PICTURE_FILENAME']?>">
    <div style="font-size:250%">
        <a href="/user/index/<?php echo $viewmodel['BORROWER_ID']; ?>"><?php echo $viewmodel['BORROWER_FIRST_NAME']; ?></a>
    </div>
    <?php } ?>
    
    <?php if ($this->state == 0 || $this->state == 1) { ?>
    
    <br/>
    <div class="subheading" style="">
        Overall Experience
    </div>
    <br/>
    <form id="myForm" action="/item/feedback/<?php echo $viewmodel['ITEM_ID']; ?>/2" method="POST" style="margin: 0">
        <?php if ($viewmodel['ITEM_STATE_ID'] == 3) { ?>
            <input id="zerostub" name="rating" type="hidden" value="0" selected style="display:none"/>
            <input name="rating" type="radio" class="star" value="1"/>
            <input name="rating" type="radio" class="star" value="2"/>
            <input name="rating" type="radio" class="star" value="3"/>
            <input name="rating" type="radio" class="star" value="4"/>
            <input name="rating" type="radio" class="star" value="5"/>
            <input name="itemid" type="hidden" value="<?php echo $viewmodel['ITEM_ID']; ?>"/>
        <?php } ?>
        <br/><br/>
        <div class="subheading" style="">
            Additional Comments
        </div><br/>
        <textarea name="comments" cols="65" rows="5"></textarea><br/><br/>
        <input type="submit" value="Submit">
    </form>
    
    <?php } ?>
    
    <?php if ($this->state == 3) { ?>
    <div id="mainheading">
        Thanks!
    </div>
    <hr/>
    Your feedback for item, <a href="/item/index/<?php echo $viewmodel['ITEM_ID'];?>"><?php echo $viewmodel['TITLE'];?></a>, has been submitted.
    <?php } ?>
    
</div>