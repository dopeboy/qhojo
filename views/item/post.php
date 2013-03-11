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
     
            <div id="bb" style="display:table-cell;width:50%;font-size:350%;">
                <input id="title" name="title" class="editable" type="text" value="insert title here" style="font-size:100%;margin: 0; padding: 0;">
                <span id ="gg" class="noneditable" style="display:none"></span>
                <a class="editPencil" href="javascript:void(0);" style="display:none"><img src ="/img/edit.png" ></a>
            </div>
            
            <div style="display:table-cell;width:50%;text-align:right">
                <input type="submit" value="Reserve" disabled>
                <input type="submit" value="Contact Lender" disabled>
            </div>
        
        
    </div>
    
   <hr style="margin:0"/>
   <br/>
   
   
   <div id="mid" style="display:table; width: 100%">
        <div id="itempictures" style="display:table-cell; width:60%; background-color: rgba(207,207,207, .5); height:400px; text-align:center">
            <div style="height:80%;width:100%;">
				<br/><br/>
				<input id ="uploadedfile1" name="file1" type="file" />
				<br/><br/>
				<input id ="uploadedfile2" name="file2" type="file" />
				<br/><br/>
				<input id ="uploadedfile3" name="file3" type="file" />
				<br/><br/>    
            </div>
            <div id="thumbs" style=" height:20%; width:100%; background-color: rgba(207,207,207, .7);">

            </div>
        </div>
       
        <input id="fileupload" type="file" name="files[]" data-url="/item/index/" multiple style="display:none">
        

        <div id="pricelender" style="display:table-cell; vertical-align: top;  width: 35%; padding-left:5%">
            <div style="background-color: rgba(207,207,207, .5);display:table; width: 100%">
                
                <div id="price" style="width:55%;display:table-cell;">
                    <div>
                        rental rate
                        <div style="font-size: 250%">
                          
                            <input id="rate" name="rate" class="editable" type="text" value="" style="width:50%;font-size:100%;margin: 0; padding: 0;">
                            <span class="noneditable" style="display:none"></span>
                            <a class="editPencil" href="javascript:void(0);" style="display:none"><img src ="/img/edit.png" ></a>                            
                        </div>
                    </div>
                    <br/>
                    <div>
                        deposit
                        <div style="font-size: 250%">
                            <input id="deposit" name="deposit" class="editable" type="text" value="" style="width:50%;font-size:100%;margin: 0; padding: 0;">
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
                <div id="lender" style="width:45%;display:table-cell; vertical-align:middle">
                    lender<br/>
                    <img src="/uploads/user/<?php echo $viewmodel[0]['PROFILE_PICTURE_FILENAME']; ?>"><br/>
                    <div style="font-size:250%">
                        <a href="/user/index/<?php echo $viewmodel[0]['ID']; ?>"><?php echo $viewmodel[0]['FIRST_NAME']; ?></a>
                    </div>
                    <div id="lender_feedback" style="">
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
            <textarea id="description" name="description"  class="editable" type="text" rows="5" value="$" style="width:100%;font-size:100%;margin: 0; padding: 0;"></textarea>
            <span class="noneditable" style="display:none"></span>
            <a class="editPencil" href="javascript:void(0);" style="display:none"><img src ="/img/edit.png" ></a>   
       </div>
   </div>
    
   <br/>
   
   <div id="location">
       <div class="header" style="font-size:250%">
           Location
       </div>
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
   <input type="submit" value="submit">
   </form>
   
    <?php } ?>
    <?php if ($this->state == 2) { ?>
    <div class="header" style="font-size:250%">
        Your post has been submitted.
    </div>
    <div class="subcontent">
        Click <a href="/item/index/<?php echo $viewmodel['ID'];?>">here</a> to see your post.
    </div>
    <?php } ?>
</div>



