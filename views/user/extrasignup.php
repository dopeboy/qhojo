<!-- Profile Picture (100), PP (200), Phone # (300) -->
<link rel="stylesheet" href="/css/user/extrasignup.css">

<div class="sheet " id="user-signup-nav" style="">
    <div class="" id="nav-container" style="">
        <div class="navbar" >
            <div id="" class="navbar-inner" style="">
                <ul class="nav text-center" style="">
                    <li class="<?php if ($this->state == 100) { echo "active"; } ?>" style="width:150px"><a href="javascript:void(0)">Profile Picture</a></li>
                    <li class="<?php if ($this->state == 500) { echo "active"; } ?>" style="width:150px"><a href="javascript:void(0)">Blurb</a></li>
                    <li class="<?php if ($this->state == 200) { echo "active"; } ?>" style="width:150px"><a href="javascript:void(0)">Phone Number</a></li>
                    <li class="<?php if ($this->state == 300) { echo "active"; } ?>" style="width:150px"><a href="javascript:void(0)">PayPal</a></li>              
                    <li class="<?php if ($this->state == 400) { echo "active"; } ?>" style="width:150px"><a href="javascript:void(0)">Credit Card</a></li>       
                </ul>
            </div>
        </div>        
    </div>
    
    <?php if ($this->state == 100) { ?>
    <p><?php echo $_SESSION["USER"]["FIRST_NAME"]?>, before you lend or borrower an item, we need you to fill out a couple more details. Please upload a profile picture of yourself below.</p>
    
    <form class="form-submit" id="profile-picture" action="/user/extrasignup/null/101" method="post">
        <div id="uploaded-profile-picture" class="text-center" style="">
            <div id="add-pictures" style="">
                  <button data-toggle="modal" tabindex="-1" href="#upload-profile-picture" id="upload-picture-btn" class="btn btn-success btn-large" type="button" style="">Upload Profile Picture</button> 
              </div>             
        </div>          

        <button id="profile-picture-submit" class="btn btn-primary btn-large  disabled" type="submit" style="" >Submit</button> 
    </form>
     
    <div id="upload-profile-picture" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
        <div class="modal-header">
            <button id="close" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Upload Profile Picture</h3>
        </div>
        <div class="modal-body text-left" style="">
            <?php require(dirname(dirname(__FILE__)) . '/embeds/picture_upload.php'); ?> 
        </div>
        <div class="modal-footer">
            <button id="done" data-dismiss="modal" class="btn btn-primary">Done</button>
        </div>
    </div>  
     
    <?php } ?>
    
    <?php if ($this->state == 500) { ?>
    <p><?php echo $_SESSION["USER"]["FIRST_NAME"]?>, before you lend or borrower an item, give us a little blurb about yourself. Please fill in the box below.</p>
    
    <form id="blurb" class="form-submit" action="/user/extrasignup/null/501" method="post">
	
        <div class="control-group" style="margin-bottom: 10px">
            <div class="controls">
                <textarea id="blurb" name="blurb" placeholder="Tell us about yourself." style="width:400px;height: 150px"></textarea>
            </div>
        </div>     
        
        <div class="control-group">
            <div class="controls">
                <button id="blurb-submit" class="btn btn-primary btn-large" type="submit" style="" >Submit</button> 
            </div>
        </div>           
    
    </form>   
     
    <?php } ?>
    
    <?php if ($this->state == 200) { 
        $phone = null;
         
        if ($viewmodel["USER"]["PHONE_VERIFICATION_DATESTAMP"] != null) 
        { 
            $phone=substr($viewmodel["USER"]["PHONE_NUMBER"],-10);      
        }
        
        ?>
    <p><?php echo $_SESSION["USER"]["FIRST_NAME"]?>, before you lend or borrower an item, we need to know how to text you the confirmation code. Please enter your phone number below.</p>
    
    
    <form id="phone-verify" class="form-submit" action="/user/extrasignup/null/201" method="post">
        <div id="phone-controls" class="control-group" style="">
            <div class="controls">
                <input type="text" class="phone" placeholder="Phone Number" id="phonenumber" name="phonenumber" value="<?php echo $phone; ?>" style="">
                <button id="phone-verify-btn" class="btn btn-primary btn-large" type="submit" style="" >Verify</button>
            </div>
        </div>   
    </form>   
    
    <form id="phone-verification-code" class="form-submit <?php if ($phone == null) echo "hide-me"?>" action="/user/extrasignup/null/202" method="post" style="">
        <div id="phone-verification-controls" class="control-group" style="">
            <div class="controls">
                <input  data-toggle="popover" data-placement="right" data-trigger="manual" data-toggle="tooltip" title="We just texted you a code. Enter it here." type="text" class="phone" placeholder="Enter Verification Code" id="verificationcode" name="verificationcode" value="" style="">
            </div>
        </div>  
        
        <div class="control-group">
            <div class="controls">
                <button id="phone-verification-code-submit" class="btn btn-primary btn-large" type="submit" style="" >Submit</button> 
            </div>
        </div>        
    </form>
    
     
    <?php } ?>
    
    <?php if ($this->state == 300) { ?>
    <p><?php echo $_SESSION["USER"]["FIRST_NAME"]?>, before you lend or borrower an item, we need to know how to pay you. Please enter your PayPal details below.</p>
    
    <form id="paypal" class="form-submit" action="/user/extrasignup/null/301" method="post">
	
        <div class="control-group">
            <div class="controls">
                <input type="text" class="paypal" placeholder="First Name" id="firstname" name="firstname">
            </div>
        </div>                           

        <div class="control-group">
            <div class="controls">
                <input type="text" class="paypal" placeholder="Last Name" id="lastname" name="lastname">
            </div>
        </div>                       

        <div class="control-group">
            <div class="controls">
                 <input type="text" class="paypal" placeholder="PayPal Email Address" id="email" name="email">    
            </div>
        </div>
        
        <div class="control-group">
            <div class="controls">
                <button id="profile-picture-submit" class="btn btn-primary btn-large " type="submit" style="" >Submit</button> 
            </div>
        </div>
    
    </form>   
     
    <?php } ?>    
    
    <?php if ($this->state == 400) { ?>
    <p style="margin-bottom:20px"><?php echo $_SESSION["USER"]["FIRST_NAME"]?>, before you lend or borrower an item, we need to know how to charge you. Please enter your credit card details below. We will always ask you before charging your card.</p>
    
    <form id="credit-card" class="" action="" method="post">
	<input type="hidden" id="bp-mp-uri" name="bp-mp-uri" value="<?php global $bp_mp_uri; echo $bp_mp_uri; ?>">
        
        <div class="row-fluid">
            <div class="span3">
                <div class="control-group">
                    <div class="controls">
                        <input type="text" class="" placeholder="Credit Card Number" id="cc-number" name="cc-number">
                    </div>
                </div>                           

                <div class="control-group">
                    <div class="controls">
                        <input type="text" class=" " placeholder="Expiration Date (MM/YYYY)" id="expiration-date" name="expiration-date">
                    </div>
                </div>                       

                <div class="control-group">
                    <div class="controls">
                         <input type="text" class=" " placeholder="CSC" id="csc" name="csc">    
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <button id="profile-picture-submit" class="btn btn-primary btn-large " type="submit" style="" >Submit</button> 
                    </div>
                </div>
            </div>
            <div class="span6" style="">      
                <a href="http://www.balancedpayments.com" target="_blank"><img src="/img/bp.png"  style="margin-bottom: 20px"></a>
            </div>
            <div class="span3 text-center" style="">
                <div id="DigiCertClickID_DGkXoYAj" data-language="en_US" style="">
                    <a href="http://www.digicert.com/ssl-certificate.htm"></a>
                </div>
                    
                <script type="text/javascript">
                    var __dcid = __dcid || [];__dcid.push(["DigiCertClickID_DGkXoYAj", "13", "m", "black", "DGkXoYAj"]);(function(){var cid=document.createElement("script");cid.async=true;cid.src="//seal.digicert.com/seals/cascade/seal.min.js";var s = document.getElementsByTagName("script");var ls = s[(s.length - 1)];ls.parentNode.insertBefore(cid, ls.nextSibling);}());
                </script>
            </div>
        </div>
   
    </form>   
     
    <?php } ?>      
</div>




<script type="text/javascript" src="https://js.balancedpayments.com/v1/balanced.js"></script>
<script src="/js/user/extrasignup.js"></script>
<script src="/js/jquery.maskedinput.min.js" type="text/javascript"></script>
