
<head>
	<script type="text/javascript" src="/js/item/search.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/item/main.css" media="screen" />  
</head>

<title>qhojo - Search results for "<?php echo $this->id ?>"</title>

<div id="masterdiv" style="">
    <div style="display: inline-block; width:100%; font-size: small; ">
        <div id="mainheadin" style="float: left; width: 55%">
            <div id="boroughs">
                <a id="all" href="/item/search/">all</a> | 
                <?php foreach ($viewmodel["BOROUGHS"] as $key=>$borough) {?> 
                <a class="borough" href="/item/search?borough=<?php echo $borough["ID"]; ?>" boroughid="<?php echo $borough["ID"]; ?>"><?php echo $borough["SHORT_NAME"] . '</a>' . ($key == count($viewmodel["BOROUGHS"])-1 ? "" : ' | ');  ?>
                <?php } ?>
            </div>
            
            <?php foreach ($viewmodel["BOROUGHS"] as $key=>$borough) { $str = ""; ?>
            <div class="neighborhoods" boroughid="<?php echo $borough["ID"]; ?>" style="display:none">
                <?php foreach ($viewmodel["NEIGHBORHOODS"] as $key=>$neighborhood ) 
                      { 
                            if ($borough["ID"] == $neighborhood["BOROUGH_ID"])
                            {
                                 $str .= "<a class=\"neighborhood\" neighborhoodid=\"" . $neighborhood["ID"] . "\" href=\"/item/search?neighborhood=" . $neighborhood["ID"] . "\">" . $neighborhood["FULL_NAME"] . "</a> | ";
                            }
                      }

                      echo substr($str, 0, -2);
                 ?>
            </div>
            <?php } ?>
        </div>
        <div style="float: left; width: 45%; text-align: right">            
            <div id="tags">
                <?php foreach ($viewmodel["TAGS"] as $key=>$tag) { 
                    
                    if (isset($this->urlvalues['borough']))
                    
                    ?> 
                   <a class="tag" tagid="<?php echo $tag["ID"]; ?>" href="/item/search?<?php echo (isset($this->urlvalues['borough']) ? ("borough=" . $this->urlvalues['borough']) : "") . (isset($this->urlvalues['neighborhood']) ? ("&neighborhood=" . $this->urlvalues['neighborhood']) : "") . "&tag=" . $tag['ID']; ?>">#<?php echo $tag['NAME'] . '</a>' . ($key == count($viewmodel["TAGS"])-1 ? "" : ' | ');?>
                <?php } ?>
           </div>
        </div>        
    </div>
    <hr/>
    <div class="subcontent" style="padding-top: 0px;   overflow: hidden;white-space: nowrap;">
            
    <?php require('card_embed.php'); ?>
 
    </div>
</div>
