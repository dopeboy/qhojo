
<head>
	<script type="text/javascript" src="/js/item/search.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/item/main.css" media="screen" />  
</head>

<title>qhojo - Search results for "<?php echo $this->id ?>"</title>

<div id="masterdiv" style="">
    <div style="display: inline-block; width:100%; font-size: small; ">
        <div id="mainheading" style="float: left; width: 55%">
            <?php if ($this->state == 0): ?>Search results for "<?php echo $this->id ?>"
            <?php elseif ($this->state == 1): ?> All items in <?php echo $viewmodel["LOCATION"]["NEIGHBORHOOD"] . "," . $viewmodel["LOCATION"]["BOROUGH_FULL"]; ?>
            <?php elseif ($this->state == 2): ?> All items in <?php echo $viewmodel["BOROUGH"]["FULL_NAME"]; endif; ?>
        </div>
        <div style="float: left; width: 45%; text-align: right">            
            <?php require('location_embed.php'); ?>
        </div>        
    </div>
    <hr/>
    <div class="subcontent" style="padding-top: 0px;   overflow: hidden;white-space: nowrap;">
            
    <?php require('card_embed.php'); ?>
 
    </div>
</div>
