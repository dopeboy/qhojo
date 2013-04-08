
<head>
    <script type="text/javascript" src="/js/user/index.js"></script>
        <script type="text/javascript" src="/js/jquery.rating.pack.js"></script>
        
         <link rel="stylesheet" type="text/css" href="/css/jquery.rating.css" media="screen" />          
         <link rel="stylesheet" type="text/css" href="/css/user/index.css" media="screen" />
</head>

<div id="masterdiv">
    <div id="mainheading">
        <?php echo $viewmodel["USER"]["FIRST_NAME"] . " " . $viewmodel["USER"]["LAST_NAME"]?>
        
    </div>
    <hr/>
    <img id="profilepicture" src="<?php echo $viewmodel['USER']['PROFILE_PICTURE_FILENAME'] == null ? "/img/stock_user_profile.jpg" : "/uploads/user/" . $viewmodel['USER']['PROFILE_PICTURE_FILENAME']?>"><br/>
    <br/>
    <div style="display:inline-block">
        <div style="float: left">
            <div class="subheading">
                Average feedback received as a lender
            </div>
            <div class="subcontent">
                <?php if ($viewmodel["LENDER_FEEDBACK"] == null) { echo "<i>None</i>"; } else { ?>
                    <input name="rating" type="radio" class="star" value="1" <?php if ($viewmodel["LENDER_FEEDBACK"] == 1) { ?> checked="checked" <?php } ?>/>
                    <input name="rating" type="radio" class="star" value="2" <?php if ($viewmodel["LENDER_FEEDBACK"] == 2) { ?> checked="checked" <?php } ?>/>
                    <input name="rating" type="radio" class="star" value="3" <?php if ($viewmodel["LENDER_FEEDBACK"] == 3) { ?> checked="checked" <?php } ?>/>
                    <input name="rating" type="radio" class="star" value="4" <?php if ($viewmodel["LENDER_FEEDBACK"] == 4) { ?> checked="checked" <?php } ?>/>
                    <input name="rating" type="radio" class="star" value="5" <?php if ($viewmodel["LENDER_FEEDBACK"] == 5) { ?> checked="checked" <?php } ?>/>
                <?php } ?>
            </div>            
        </div>
        <div style="float: left; margin-left: 30px">
            <div class="subheading">
                Average feedback received as a borrower
            </div>
            <div class="subcontent">
                <?php if ($viewmodel["BORROWER_FEEDBACK"] == null) { echo "<i>None</i>"; } else { ?>
                    <input name="rating2" type="radio" class="star" value="1" <?php if ($viewmodel["BORROWER_FEEDBACK"] == 1) { ?> checked="checked" <?php } ?>/>
                    <input name="rating2" type="radio" class="star" value="2" <?php if ($viewmodel["BORROWER_FEEDBACK"] == 2) { ?> checked="checked" <?php } ?>/>
                    <input name="rating2" type="radio" class="star" value="3" <?php if ($viewmodel["BORROWER_FEEDBACK"] == 3) { ?> checked="checked" <?php } ?>/>
                    <input name="rating2" type="radio" class="star" value="4" <?php if ($viewmodel["BORROWER_FEEDBACK"] == 4) { ?> checked="checked" <?php } ?>/>
                    <input name="rating2" type="radio" class="star" value="5" <?php if ($viewmodel["BORROWER_FEEDBACK"] == 5) { ?> checked="checked" <?php } ?>/>        
                <?php } ?>
            </div>            
        </div>
    </div>    
    <br/>
    <br/>
    <div class="subheading">
        Comments received as a lender
    </div>
    <br/>
    <table style="border: 1px solid; text-align: center;border-collapse:collapse; width:100%">
        <tbody>
            <tr id="cardheader" class="cardrow">
                <td class="title">Item</td>
                <td class="borrower">Borrower</td>
                <td class="comments">Comments</td>
                <td class="rating">Rating</td>
            </tr>
            
            <?php if ($viewmodel["COMMENTS_AS_LENDER"] == null) { echo "<tr><td colspan=\"4\"><i>No data</i></td></tr>"; } else { ?>        
            
            <?php foreach($viewmodel["COMMENTS_AS_LENDER"] as $item) { ?>

            <tr class="cardrow">
                <td class="title"><a href="/item/index/<?php echo $item['ITEM_ID'];?>"><?php echo $item['TITLE'];?></a></td>
                <td class="borrower"><a href="/user/index/<?php echo $item['BORROWER_ID']?>"><?php echo $item['BORROWER_FIRST_NAME']; ?></a></td>
                <td class="comments data"><?php echo $item['BORROWER_TO_LENDER_COMMENTS']; ?></td>
                <td class="rating">
               
                <?php if ($item["BORROWER_TO_LENDER_STARS"] == null) { echo "<i>None</i>"; } else { ?>
                    <input name="rating3" type="radio" class="star" value="1" <?php if ($item["BORROWER_TO_LENDER_STARS"] == 1) { ?> checked="checked" <?php } ?>/>
                    <input name="rating3" type="radio" class="star" value="2" <?php if ($item["BORROWER_TO_LENDER_STARS"] == 2) { ?> checked="checked" <?php } ?>/>
                    <input name="rating3" type="radio" class="star" value="3" <?php if ($item["BORROWER_TO_LENDER_STARS"] == 3) { ?> checked="checked" <?php } ?>/>
                    <input name="rating3" type="radio" class="star" value="4" <?php if ($item["BORROWER_TO_LENDER_STARS"] == 4) { ?> checked="checked" <?php } ?>/>
                    <input name="rating3" type="radio" class="star" value="5" <?php if ($item["BORROWER_TO_LENDER_STARS"] == 5) { ?> checked="checked" <?php } ?>/>        
                <?php } ?>                    
                </td>                
            </tr>
            <?php } } ?>   
        </tbody>
    </table> 
    <br/>
    <br/>
    <div class="subheading">
        Comments received as a borrower
    </div>    
    <br/>
    <table style="border: 1px solid; text-align: center;border-collapse:collapse; width:100%">
        <tbody>
            <tr id="cardheader" class="cardrow">
                <td class="title">Item</td>
                <td class="borrower">Lender</td>
                <td class="comments">Comments</td>
                <td class="rating">Rating</td>
            </tr>
            
            <?php if ($viewmodel["COMMENTS_AS_BORROWER"] == null) { echo "<tr><td colspan=\"4\"><i>No data</i></td></tr>"; } else { ?>        
            
            <?php foreach($viewmodel["COMMENTS_AS_BORROWER"] as $item) { ?>

            <tr class="cardrow">
                <td class="title"><a href="/item/index/<?php echo $item['ITEM_ID'];?>"><?php echo $item['TITLE'];?></a></td>
                <td class="borrower"><a href="/user/index/<?php echo $item['LENDER_ID']?>"><?php echo $item['LENDER_FIRST_NAME']; ?></a></td>
                <td class="comments data"><?php echo $item['LENDER_TO_BORROWER_COMMENTS']; ?></td>
                <td class="rating">
               
                <?php if ($item["LENDER_TO_BORROWER_STARS"] == null) { echo "<i>None</i>"; } else { ?>
                    <input name="rating3" type="radio" class="star" value="1" <?php if ($item["LENDER_TO_BORROWER_STARS"] == 1) { ?> checked="checked" <?php } ?>/>
                    <input name="rating3" type="radio" class="star" value="2" <?php if ($item["LENDER_TO_BORROWER_STARS"] == 2) { ?> checked="checked" <?php } ?>/>
                    <input name="rating3" type="radio" class="star" value="3" <?php if ($item["LENDER_TO_BORROWER_STARS"] == 3) { ?> checked="checked" <?php } ?>/>
                    <input name="rating3" type="radio" class="star" value="4" <?php if ($item["LENDER_TO_BORROWER_STARS"] == 4) { ?> checked="checked" <?php } ?>/>
                    <input name="rating3" type="radio" class="star" value="5" <?php if ($item["LENDER_TO_BORROWER_STARS"] == 5) { ?> checked="checked" <?php } ?>/>        
                <?php } ?>                    
                </td>
            </tr>
            <?php } } ?>   
        </tbody>
    </table>   
</div>

