
<head>
    <script type="text/javascript" src="/js/user/index.js"></script>
        <script type="text/javascript" src="/js/jquery.rating.pack.js"></script>
        
         <link rel="stylesheet" type="text/css" href="/css/jquery.rating.css" media="screen" />          
</head>

<div id="masterdiv">
    <div class="mainheading">
        <?php echo $viewmodel["USER"]["FIRST_NAME"] . " " . $viewmodel["USER"]["LAST_NAME"]?>
    </div>
    <hr/>
    <div class="subheading">
        Average feedback received as a Lender
    </div>
    <div class="subcontent">
            <input name="rating" type="radio" class="star" value="1" <?php if ($viewmodel["LENDER_FEEDBACK"] == 1) { ?> checked="checked" <?php } ?>/>
            <input name="rating" type="radio" class="star" value="2" <?php if ($viewmodel["LENDER_FEEDBACK"] == 2) { ?> checked="checked" <?php } ?>/>
            <input name="rating" type="radio" class="star" value="3" <?php if ($viewmodel["LENDER_FEEDBACK"] == 3) { ?> checked="checked" <?php } ?>/>
            <input name="rating" type="radio" class="star" value="4" <?php if ($viewmodel["LENDER_FEEDBACK"] == 4) { ?> checked="checked" <?php } ?>/>
            <input name="rating" type="radio" class="star" value="5" <?php if ($viewmodel["LENDER_FEEDBACK"] == 5) { ?> checked="checked" <?php } ?>/>
    </div>
    <br/>
    <br/>
    <div class="subheading">
        Average feedback received as a Borrower
    </div>
    <div class="subcontent">
        <div>
            <input name="rating2" type="radio" class="star" value="1" <?php if ($viewmodel["BORROWER_FEEDBACK"] == 1) { ?> checked="checked" <?php } ?>/>
            <input name="rating2" type="radio" class="star" value="2" <?php if ($viewmodel["BORROWER_FEEDBACK"] == 2) { ?> checked="checked" <?php } ?>/>
            <input name="rating2" type="radio" class="star" value="3" <?php if ($viewmodel["BORROWER_FEEDBACK"] == 3) { ?> checked="checked" <?php } ?>/>
            <input name="rating2" type="radio" class="star" value="4" <?php if ($viewmodel["BORROWER_FEEDBACK"] == 4) { ?> checked="checked" <?php } ?>/>
            <input name="rating2" type="radio" class="star" value="5" <?php if ($viewmodel["BORROWER_FEEDBACK"] == 5) { ?> checked="checked" <?php } ?>/>        
        </div>
    </div>    
</div>

<!--<div id="container" style="width:100%;">
    <div id="vv" style="width:1024px;display: block;margin: 0 auto;">
        <form id="myForm" action="/item/postAction/" method="post" enctype="multipart/form-data">
            <h1><?php echo $viewmodel["USER"]["FIRST_NAME"] . " " . $viewmodel["USER"]["LAST_NAME"]?></h1>
            <hr/>
            <h2>Average feedback received as a Lender</h2>
            <?php echo $viewmodel["LENDER_FEEDBACK"] == null ? "<i>None</i>" : $viewmodel["LENDER_FEEDBACK"] . " / 5";?>
            <h2>Average feedback received as a Borrower</h2>
            <?php echo $viewmodel["BORROWER_FEEDBACK"] == null ? "<i>None</i>" : $viewmodel["BORROWER_FEEDBACK"] . " / 5";?>
        </form>     
    </div>
</div>-->


