<head>
	<script type="text/javascript" src="/js/item/post.js"> </script>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATBCUDSJrOMyO4sm1-r8ooIjByWnZaYeA&sensor=false">
    </script>      
    

</head>

<div id="masterdiv">
    <?php if ($this->state == 0) { ?>
    
    <form id="myForm" method="post" action="/item/post/null/1">
        
    <div id="topbar" style="width:100%;display:table;">
        <div id="mainheading" style="display:table-cell;">
                <input id="title" name="title" class="editable" type="text" value="insert title here" style="font-size:100%; border: 1px solid #aaaaaa;">
                <span id ="gg" class="noneditable" style="display:none"></span>
                <a class="editPencil" href="javascript:void(0);" style="display:none"><img src ="/img/edit.png" ></a>        
        </div>
            <div style="display:table-cell;width:50%;text-align:right">
                <input type="submit" value="Reserve" disabled>
                <input type="submit" value="Contact Lender" style="" disabled>
            </div>
    </div>
    
   <hr style=""/>
 
   
   <div id="mid" style="display:table; width:984px; height:580px">
        <div id="itempictures" style="display:table-cell; text-align:center;height:100%;overflow: hidden;position:relative; width: 640px; ">
      
            <div id="picture" style="height: 530px; width: 640px; vertical-align: middle; display: table-cell; text-align: center">
                <input id ="uploadedfile1" name="file1" type="file" />
            </div>
                                
            <div id="thumbs" style="position: absolute; bottom:0;  height:50px; width:100%; background-color: rgba(207,207,207, .5);">

            </div>
       
        </div>

        <div id="pricelender" style="display:table-cell; vertical-align: top;  width: 304px;padding-left: 40px; height: 100%">

                <div id="price" style="background-color: rgba(207,207,207, .5);height:230px;padding: 15px">
                  <div>
                        rental rate
                        <div style="font-size: 250%">
                            $<input id="rate" name="rate" class="editable" type="text" value="" style="width:50%;font-size:100%;margin: 0; padding: 0;">
                            <span class="noneditable" style="display:none; margin: 0; "></span>
                            <a class="editPencil" href="javascript:void(0);" style="display:none"><img src ="/img/edit.png" ></a>                            
                        </div>
                    </div>
                    <br/>
                    <div>
                        deposit
                        <div style="font-size: 250%">
                            $<input id="deposit" name="deposit" class="editable" type="text" value="" style="width:50%;font-size:100%;margin: 0; padding: 0;">
                            <span class="noneditable" style="display:none"></span>
                            <a class="editPencil" href="javascript:void(0);" style="display:none"><img src ="/img/edit.png" ></a>     
                        </div>
                    </div>
                    <br/>
                    <div>   
                        reservation fee
                        <div style="font-size: 250%">$1</div>
                    </div>          
                </div>       
          
            
            <div id="lender" style="margin-top: 20px; padding: 15px;  vertical-align:middle;background-color: rgba(207,207,207, .5);height:270px;">
                
              lender<br/>
                <img src="/uploads/user/<?php echo $viewmodel[0]['PROFILE_PICTURE_FILENAME']; ?>"><br/>
                <div style="font-size:250%">
                    <a href="/user/index/<?php echo $viewmodel[0]['ID']; ?>"><?php echo $viewmodel[0]['FIRST_NAME']; ?></a>
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
       <div class="subheading" style="">
           Description
       </div>
       <br/>
       <div id="content">
            <textarea id="description" name="description"  class="editable" type="text" rows="5" value="$" style="width:100%;font-size:100%;margin: 0; padding: 0;"></textarea>
            <span class="noneditable" style="display:none"></span>
            <a class="editPencil" href="javascript:void(0);" style="display:none"><img src ="/img/edit.png" ></a>   
       </div>
   </div>
    
   <br/>
   
   <div id="location">
       <div class="subheading">
           Location
       </div>
       <br/>
        <div id="bb" style="">
            <select id ="address" name="locationid" class="editabledropdown">
                <option></option>
                <?php foreach ($viewmodel[1] as $location) { ?>
                <option value="<?php echo $location['ID']; ?>"><?php echo $location['NEIGHBORHOOD'] . ',' . $location['BOROUGH']; ?></option>
                <?php } ?>
            </select>
            <span class="noneditable" style="display:none"></span>
            <a class="editPencildropdown" href="javascript:void(0);" style="display:none"><img src ="/img/edit.png" ></a>
            <div id="map_canvas" style="width:100%; height:300px; display:none"></div>
        </div>
   </div>   
   <br/>
   <br/>
   <div style="text-align:center">
    <input type="submit" value="submit">
   </div>
   </form>
   
    <?php } ?>
    <?php if ($this->state == 2) { ?>
    <div class="mainheading">
        Your post has been submitted!
    </div>
    <div class="subcontent">
        Click <a href="/item/index/<?php echo $viewmodel['ID'];?>">here</a> to see your post.
    </div>
    <?php } ?>
</div>



