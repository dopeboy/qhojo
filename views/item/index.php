<head>
	<script type="text/javascript" src="/js/item/index.js"></script>
        <script type="text/javascript" src="/js/jquery.rating.pack.js"></script>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATBCUDSJrOMyO4sm1-r8ooIjByWnZaYeA&sensor=false">
    </script>          
    <link rel="stylesheet" href="/css/item/index.css" />
     <link rel="stylesheet" type="text/css" href="/css/jquery.rating.css" media="screen" />   
      
              
</head>

<div id="masterdiv">
    <div id="topbar" style="width:100%;display:table;">
        
            <div style="display:table-cell;width:50%;font-size:350%;">
                <?php echo $viewmodel[0]['TITLE'] ?>
            </div>
            <div style="display:table-cell;width:50%;text-align:right">
                <form id="f" action="/item/reserve/<?php echo $viewmodel[0]['ITEM_ID'] ?>/0" method="get" style="padding:0px; margin: 0px">
                    <input type="submit" value="Reserve" <?php if($viewmodel[0]['LENDER_ID'] == $this->userid || $viewmodel[0]['ITEM_STATE_ID'] != 0) { ?> disabled <?php } ?>>
                    <input type="submit" value="Contact Lender" style="">
                </form>
            </div>
    </div>
   <hr style="margin:0"/>
   <br/>
   
   <div id="mid" style="display:table; width: 100%">
        <div id="itempictures" style="display:table-cell; width:60%; background-color: rgba(207,207,207, .5); height:400px; text-align:center">
                        <?php $file = $viewmodel[1][0];?>
            <img id ="largeimage" src="<?php echo $file['FILENAME']== null ? "/img/stock.png" : "/uploads/item/" . $file['FILENAME']; ?>" style="height:400px">
        
            <div id="thumbs" style="display: block; height:50px; width:100%; background-color: rgba(207,207,207, .7);">
                <?php foreach ($viewmodel[1] as $pic) {  ?>
                
                <img class src="<?php echo $pic['FILENAME']== null ? "/img/stock.png" : "/uploads/item/" . $pic['FILENAME']; ?>" style="height:50px"></a>
                
                <?php } ?>
            </div>
        </div>

        <div id="pricelender" style="display:table-cell; vertical-align: top;  width: 35%; padding-left:5%">
            <div style="background-color: rgba(207,207,207, .5);display:table; width: 100%">
                
                <div id="price" style="width:55%;display:table-cell;">
                    <div>
                        rental rate
                        <div style="font-size: 250%">$<?php echo $viewmodel[0]['RATE']?> / day</div>
                    </div>
                    <br/>
                    <div>
                        deposit
                        <div style="font-size: 250%">$<?php echo $viewmodel[0]['DEPOSIT']?></div>
                    </div>
                    <br/>
                    <div>   
                        reservation fee
                        <div style="font-size: 250%">$1</div>
                    </div>            
                </div>
                <div id="lender" style="width:45%;display:table-cell; vertical-align:middle">
                    lender<br/>
                    <img src="/uploads/user/<?php echo $viewmodel[0]['LENDER_PICTURE_FILENAME']; ?>"><br/>
                    <div style="font-size:250%">
                        <a href="/user/index/<?php echo $viewmodel[0]['LENDER_ID']; ?>"><?php echo $viewmodel[0]['LENDER_FIRST_NAME']; ?></a>
                    </div>
                    <div id="lender_feedback" style="">
                        <?php if ($viewmodel[2][0] == null) { ?> <i>No feedback</i> <?php } else { ?>
                            <input name="rating2" type="radio" class="star" value="1" <?php if ($viewmodel[2][0] == 1) { ?> checked="checked" <?php } ?>/>
                            <input name="rating2" type="radio" class="star" value="2" <?php if ($viewmodel[2][0] == 2) { ?> checked="checked" <?php } ?>/>
                            <input name="rating2" type="radio" class="star" value="3" <?php if ($viewmodel[2][0] == 3) { ?> checked="checked" <?php } ?>/>
                            <input name="rating2" type="radio" class="star" value="4" <?php if ($viewmodel[2][0] == 4) { ?> checked="checked" <?php } ?>/>
                            <input name="rating2" type="radio" class="star" value="5" <?php if ($viewmodel[2][0] == 5) { ?> checked="checked" <?php } ?>/>   
                            <?php } ?>
                    </div>    
                </div>        
            </div>
        </div>
    </div>
<br/>    
   <div id="description">
       <div class="header" style="font-size:250%">
           Description
       </div>
       <div id="content">
            <?php echo $viewmodel[0]['DESCRIPTION'] ?>
       </div>
   </div>
    
   <br/>
   
   <div id="location">
       <div class="header" style="font-size:250%">
           Location
       </div>
       <div id="address" style="">
               <?php echo $viewmodel[0]['NEIGHBORHOOD'] . ',' . $viewmodel[0]['BOROUGH']; ?>
                <div id="map_canvas" style="width:100%; height:300px"></div>
       </div>
   </div>
    
</div>

		


</html>

