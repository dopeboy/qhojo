<head>
	<script type="text/javascript" src="/js/item/feedback.js"></script>
        <script type="text/javascript" src="/js/jquery.rating.pack.js"></script>
<!--         <link rel="stylesheet" type="text/css" href="/css/item/feedback.css" media="screen" />  -->
         <link rel="stylesheet" type="text/css" href="/css/jquery.rating.css" media="screen" />  
</head>

<div id="masterdiv">
    
    <?php if ($this->state == 0) { ?>
    <div class="mainheading">
        Feedback
    </div>
    Item: <?php echo $viewmodel['TITLE'] ?>
    <br/>
    <br/>
    <div class="subheading" style="">
        Overall Experience
    </div>
    <form id="myForm" action="/item/feedback/<?php echo $viewmodel['ITEM_ID']; ?>/1" method="POST">
        <?php if ($viewmodel['ITEM_STATE_ID'] == 3) { ?>
            <input id="zerostub" name="rating" type="hidden" value="0" selected style="display:none"/>
            <input name="rating" type="radio" class="star" value="1"/>
            <input name="rating" type="radio" class="star" value="2"/>
            <input name="rating" type="radio" class="star" value="3"/>
            <input name="rating" type="radio" class="star" value="4"/>
            <input name="rating" type="radio" class="star" value="5"/>
            <input name="itemid" type="hidden" value="<?php echo $viewmodel['ITEM_ID']; ?>"/>
        <?php } ?>
        <br/>
        <br/>
        <input type="submit" value="Submit">
    </form>
    <?php } ?>
    
    <?php if ($this->state == 2) { ?>
    <div class="mainheading">
        Thanks!
    </div>
    Item: <?php echo $viewmodel['TITLE'] ?>
    <br/>
    <br/>
    <div class="subheading" style="">
        Your feedback has been submitted.
    </div>  
    <?php } ?>
    
</div>