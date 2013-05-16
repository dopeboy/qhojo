<head>
	<script type="text/javascript" src="/js/item/index.js"></script>
        <script type="text/javascript" src="/js/jquery.rating.pack.js"></script>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATBCUDSJrOMyO4sm1-r8ooIjByWnZaYeA&sensor=false">
    </script>          
    <link rel="stylesheet" href="/css/item/index.css" />
     <link rel="stylesheet" type="text/css" href="/css/jquery.rating.css" media="screen" />   
         <script type="text/javascript" src="/js/jquery-ui.js"> </script>
  <link rel="stylesheet" href="/css/jquery-ui.css" />
      
              
</head>

<title>qhojo - <?php echo $viewmodel[0]['TITLE'] ?></title>

<div id="masterdiv">
    <div id="topbar" style="width:100%;display:table;">
        <div id="mainheading" style="display:table-cell;"><?php echo $viewmodel[0]['TITLE'] ?></div>
        <div style="display:table-cell;width:50%;text-align:right">
            <form id="f" action="/item/request/<?php echo $viewmodel[0]['ITEM_ID'] ?>/0" method="get" style="padding:0px; margin: 0px">
                 <input type="submit" style="width: 90px" value="Rent" <?php if($viewmodel[0]['LENDER_ID'] == $this->userid || $viewmodel[0]['ITEM_STATE_ID'] != 0 || $viewmodel[3]) { ?> disabled <?php } ?>>
                 <?php if ($this->userid == null) { ?>
                 <input type="submit" value="Contact Lender" onclick="" disabled>
                 <?php } else { ?>
                 <input type="submit" value="Contact Lender" onclick="location.href='mailto:<?php echo $viewmodel[0]['LENDER_EMAIL_ADDRESS'] ?>?Subject=Hi';return false"  <?php if($viewmodel[0]['LENDER_ID'] == $this->userid) { ?> disabled <?php } ?>>
                 <?php } ?>
            </form>
        </div>
    </div>
   <hr style=""/>

   <div id="mid" style="display:table; width:984px; height:580px">
        <div id="itempictures" style="display:table-cell; text-align:center;height:100%;overflow: hidden;position:relative; width: 640px;">
      
            <div id="picture" style="height: 530px; width: 640px; vertical-align: middle; display: table-cell; text-align: center">
                <?php $file = $viewmodel[1][0];?>
                <img id ="largeimage" src="<?php echo $file['FILENAME']== null ? "/img/stock.png" : "/uploads/item/" . $file['FILENAME'];?>" style="max-height: 500px; max-width: 640px" >
            </div>
            
            <div id="thumbs" style="position: absolute; bottom:0;  height:50px; width:100%; background-color: rgba(207,207,207, .5);">
            <?php foreach ($viewmodel[1] as $pic) {  ?>

            <img class src="<?php echo $pic['FILENAME']== null ? "/img/stock.png" : "/uploads/item/" . $pic['FILENAME']; ?>" style="height:50px"></a>

            <?php } ?>
            </div>
       
        </div>

        <div id="pricelender" style="display:table-cell; vertical-align: top;  width: 304px;padding-left: 40px; height: 100%">

                <div id="price" style="background-color: rgba(207,207,207, .5);height:230px;padding: 15px">
                  <div>
                        rental rate
                        <div style="font-size: 250%">$<?php echo $viewmodel[0]['RATE']?> / day</div>
                    </div>
                    <br/>
                    <div>
                        deposit
                        <div style="font-size: 250%">$<?php echo $viewmodel[0]['DEPOSIT']?></div>
                    </div>       
                </div>       
          
            
            <div id="lender" style="margin-top: 20px; padding: 15px;  vertical-align:middle;background-color: rgba(207,207,207, .5);height:270px;">
                
              lender<br/>
               <img id="profilepicture" src="<?php echo $viewmodel[0]['LENDER_PICTURE_FILENAME'] == null ? "/img/stock_user_profile.jpg" : "/uploads/user/" . $viewmodel[0]['LENDER_PICTURE_FILENAME']?>"><br/>
                <div style="font-size:250%">
                    <a href="/user/index/<?php echo $viewmodel[0]['LENDER_ID']; ?>"><?php echo $viewmodel[0]['LENDER_FIRST_NAME']; ?></a>
                    <?php foreach ($viewmodel[4] as $network) {  ?>

                    <img src="/img/network/<?php echo $network['ICON_IMAGE'] ?>" title="<?php echo $viewmodel[0]['LENDER_FIRST_NAME']; ?> is a member of the <?php echo $network['NETWORK_NAME'] ?> network."></a>

                    <?php } ?>
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
<br/>    
   <div id="description">
       <div class="subheading">
           Description
       </div>
       <br/>
       <div id="content">
            <?php echo $viewmodel[0]['DESCRIPTION'] ?>
       </div>
   </div>
    
   <br/>
   
   <div id="location">
       <div class="subheading" >
           Location
       </div>
       <br/>
       <div id="address" style="">
        <?php echo $viewmodel[0]['NEIGHBORHOOD'] . ',' . $viewmodel[0]['BOROUGH_FULL']; ?>
         <br/><br/>
         <div id="map_canvas" style="width:100%; height:300px"></div>
       </div>
   </div>
    
</div>

		


</html>

