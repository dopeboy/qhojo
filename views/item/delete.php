<head>
	<script type="text/javascript" src="/js/item/delete.js"></script>
        <script type="text/javascript" src="/js/jquery.rating.pack.js"></script>
<!--         <link rel="stylesheet" type="text/css" href="/css/item/feedback.css" media="screen" />  -->
         <link rel="stylesheet" type="text/css" href="/css/jquery.rating.css" media="screen" />  
</head>

<div id="masterdiv">

    <?php if ($this->state == 0) { ?>
    <title>qhojo - Delete Confirmation</title>
    <div id="mainheading">
        Delete Confirmation - <a href="/item/index/<?php echo $viewmodel['ITEM_ID'];?>"><?php echo $viewmodel['TITLE'];?></a>
    </div>
    <hr/>
    <form id="myForm" action="/item/delete/<?php echo $viewmodel['ITEM_ID'];?>/1">
    Are you sure you want to delete your item?
    <br/><br/>
    <input type="submit" value="yes">
    </form>
    
    <?php } else if ($this->state == 2) { ?>
    <title>qhojo - Deleted</title>
    <div id="mainheading">
        Item Deleted
    </div>    
    <hr/>
    Your item has been deleted.
    <?php } ?>
    
</div>