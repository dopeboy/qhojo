<head>
    <link type="text/css" rel="stylesheet" href="/css/user/login.css">
    <script type="text/javascript" src="/js/jquery.validate.min.js"> </script>
      <script type="text/javascript" src="/js/user/edit.js"></script>
<!--      <script type="text/javascript" src="/js/jquery.jeditable.mini.js"></script>-->
         <script type="text/javascript" src="/js/jquery-ui.js"> </script>
        <link rel="stylesheet" href="/css/jquery-ui.css" /> 
       <link rel="stylesheet" type="text/css" href="/css/user/index.css" media="screen" />
</head>

<title>qhojo - edit user profile</title>

<div id="masterdiv">
    <input id="userid" type="hidden" value="<?php echo $viewmodel["USER"]["ID"]; ?>">
    <?php if ($this->state == 0): ?>
    <div id="mainheading">
        Edit User Profile
    </div>    
    <hr/>
    
        <div class="subheading">
            Name
        </div>
        <div class="subcontent">
            <?php echo $viewmodel["USER"]["FIRST_NAME"]; ?>
        </div>
        <br/>
        
       <div class="subheading">
            Profile Picture
        </div>
        <div class="subcontent" style="">
            <div id="picture">
                <img id="profilepicture" src="<?php echo $viewmodel['USER']['PROFILE_PICTURE_FILENAME'] == null ? "/img/stock_user_profile.jpg" : "/uploads/user/" . $viewmodel['USER']['PROFILE_PICTURE_FILENAME']?>">
                <a id="add-pictures" href="#"><img src="/img/edit.png" style=""/></a>
                
            </div>

            <button id="submitPictureButton" value="" style="display:none; margin-top: 10px; margin-bottom: 20px">Upload Picture</button>
            <img class="loading" src="/img/ajax-loader.gif" style="display:none">
            <img class="checkmark" src="/img/checkmark.png" style="opacity: 0; ">
        </div>
        
        <div id="dialog-form" title="Upload User Pictures" style="overflow: hidden">
          <iframe id="uploaderframe" src="" style="width: 100%; height: 100%;" frameborder="0"></iframe>
        </div>
        
        <div class="subheading">
            Contact Information
        </div>
        <div class="subcontent">
            <div id="1">
                Email: <span class="noneditable"><?php echo $viewmodel["USER"]["EMAIL_ADDRESS"]; ?></span><input class="editable" type="text" value="<?php echo $viewmodel["USER"]["EMAIL_ADDRESS"]; ?>" style="display:none"><img class="checkmark" src="/img/checkmark.png" style="opacity: 0"><input class="submitButton" type="button" value="submit" style="display:none;" /><a class="editButton" href="#"><img src="/img/edit.png" style=""/></a><input class="cancelButton" type="button" value="cancel" style="display:none" /><img class="loading" src="/img/ajax-loader.gif" style="display:none">
            </div>

            Phone Number: <?php echo $viewmodel["USER"]["PHONE_NUMBER"] == null ? '<i>None</i>' :  $viewmodel["USER"]["PHONE_NUMBER"]?><br/>
            Location: <span class="noneditable"><?php echo $viewmodel["USER"]["NEIGHBORHOOD"] . ','. $viewmodel["USER"]["BOROUGH_FULL"]; ?></span>
            
            <select class ="editable" name="locationid" style="display:none">
                <?php foreach ($viewmodel["LOCATIONS"] as $location) { ?>
                <option value="<?php echo $location['ID']; ?>" <?php echo $viewmodel["USER"]["LOCATION_ID"] == $location['ID'] ? 'selected' : '';?>><?php echo $location['NEIGHBORHOOD'] . ',' . $location['BOROUGH_FULL']; ?></option>
                <?php } ?>
            </select>            
            
            <img class="checkmark" src="/img/checkmark.png" style="opacity: 0"><input class="submitButton Location" type="button" value="submit" style="display:none;" /><a class="editButton" href="#"><img src="/img/edit.png" style=""/></a><input class="cancelButton" type="button" value="cancel" style="display:none" /><img class="loading" src="/img/ajax-loader.gif" style="display:none">
        </div>
        <br/>        
        
        <div class="subheading">
            Payment Information
        </div>
        <div class="subcontent">
            <div id="2">
               PayPal First Name: <span class="noneditable ppFirstName"><?php echo $viewmodel["USER"]["PAYPAL_FIRST_NAME"] == null ? '<i>None</i>' : $viewmodel["USER"]["PAYPAL_FIRST_NAME"]?></span><input class="editable ppFirstName" type="text" value="<?php echo $viewmodel["USER"]["PAYPAL_FIRST_NAME"]; ?>" style="display:none"><img class="checkmark" src="/img/checkmark.png" style="opacity: 0">
               <br/>
               PayPal Last Name: <span class="noneditable ppLastName"><?php echo $viewmodel["USER"]["PAYPAL_LAST_NAME"] == null ? '<i>None</i>' : $viewmodel["USER"]["PAYPAL_LAST_NAME"]?></span><input class="editable ppLastName" type="text" value="<?php echo $viewmodel["USER"]["PAYPAL_LAST_NAME"]; ?>" style="display:none"><img class="checkmark" src="/img/checkmark.png" style="opacity: 0">
               <br/>
               PayPal Email: <span class="noneditable ppEmailAddress"><?php echo $viewmodel["USER"]["PAYPAL_EMAIL_ADDRESS"] == null ? '<i>None</i>' : $viewmodel["USER"]["PAYPAL_EMAIL_ADDRESS"] ?></span><input class="editable ppEmailAddress" type="text" value="<?php echo $viewmodel["USER"]["PAYPAL_EMAIL_ADDRESS"]; ?>" style="display:none"><img class="checkmark" src="/img/checkmark.png" style="opacity: 0"><input class="submitButton PP" type="button" value="submit" style="display:none;" /><a class="editButton" href="#"><img src="/img/edit.png" style=""/></a><input class="cancelButton" type="button" value="cancel" style="display:none" /><img class="loading" src="/img/ajax-loader.gif" style="display:none">
            </div>
            <br/>
            
            Credit Card Last Four: <?php echo $viewmodel["CREDITCARD"] == null ? '<i>None</i>' : $viewmodel["CREDITCARD"]->last_four?><br/>
            Credit Card Type: <?php echo $viewmodel["CREDITCARD"] == null ? '<i>None</i>' : $viewmodel["CREDITCARD"]->card_type?><br/>
            Credit Card Expiration: <?php echo $viewmodel["CREDITCARD"] == null ? '<i>None</i>' : $viewmodel["CREDITCARD"]->expiration_month . '/' . $viewmodel["CREDITCARD"]->expiration_year ?><br/>
            
        </div>
        <br/>    
        
        <div class="subheading">
            Networks
        </div>
        <div class="subcontent">
            <div id="3">
                <div class="currentNetworks">
                    
                    <?php if ($viewmodel["CURRENT_NETWORKS"] == null) { echo "<i>No networks</i>"; } else { ?>        

                    <?php foreach($viewmodel["CURRENT_NETWORKS"] as $network) { ?>       
                        <img src="/img/network/<?php echo $network['ICON_IMAGE'] ?>" title="<?php echo $viewmodel["USER"]["FIRST_NAME"]; ?> is a member of the <?php echo $network['NETWORK_NAME'] ?> network.">
                    <?php } } ?>                

                    <a class="editNetworksButton" href="#"><img src="/img/edit.png" style=""/></a>
                </div>
                
                
                
                <div class="editNetworks" style="display:none">
                    
                <?php if ($viewmodel["OUTSIDE_NETWORKS"] == null) { echo "<i>No networks to add</i>"; } else { ?>        

                    Add a network: 
                    <select id="networklist" name="networkid">
                        <option email="" value="">Select a network</option>
                        <option email="" value="">---------</option>    
                        
                            <?php foreach($viewmodel["OUTSIDE_NETWORKS"] as $network) { ?>       
                                <option email="<?php echo $network['EMAIL_EXTENSION'] ?>" value="<?php echo $network['ID'] ?>"><?php echo $network['NAME'] ?></option>      
                            <?php } ?>
                    </select>

                    <div id="networkdynamic" style="display: none">
                        <br/>
                        <input class="textbox" type="text" id="networkemail" name="networkemail" /><span id="atsymbol">@</span><span id="networkemailextension"></span>
                        <input class="submitButton network" type="button" value="submit" style="" /><input class="cancelButton network" type="button" value="cancel" style="" />
                        <img class="loading" src="/img/ajax-loader.gif" style="display:none">
                   </div>


                <?php } ?>  
                </div>
                
                <div id="networkRequestSent" style="display:none">
                    Network request email sent. <img class="checkmark" src="/img/checkmark.png" style="">
                </div>                    
            </div>
        </div>
         
    <?php elseif ($this->state == 2):  ?>
    <div id="mainheading">
        User Profile Submitted
    </div>    
    <hr/>
    Your profile has been changed. If you added a new network, check your network's email address and follow the instructions in the message.
    <?php else: ?>
    <div id="mainheading">
        Error
    </div>
    <hr/>
    There was an error with editing your user profile.
    <?php endif; ?>
</div>