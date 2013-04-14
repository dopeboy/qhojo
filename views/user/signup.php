<head>
    <script type="text/javascript" src="/js/jquery.form.js"></script> 
    <script type="text/javascript" src="/js/user/signup.js"></script>
    <link type="text/css" rel="stylesheet" href="/css/user/login.css">
    <script src="/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/jquery-ui.js"> </script>
    <script type="text/javascript" src="/js/jquery.validate.min.js"> </script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
      <script src="https://checkout.stripe.com/v2/checkout.js"></script>
</head>

<?php if ($this->state == 0):  ?>
    
<div id="masterdiv">
    <div id="mainheading">Sign Up</div>
    <hr/>
    <div class="subcontent">
        <form class="cmxform" id="myForm" action="/user/signup/null/1" method="post" style="margin: 0">
            <table>      
                <tr>
                    <td>
                            First name:
                    </td>
                    <td>
                            <input class="required textbox"  id="cname" type="text" name="name" autocomplete="off" minlength="1" />
                    </td>
                </tr>                
                <tr>
                    <td>
                            Email:
                    </td>
                    <td>
                            <input id="cemail" type="text" name="emailaddress" class="required email textbox" autocomplete="off" />
                    </td>
                </tr>
                <tr>
                    <td>
                            Password:
                    </td>
                    <td>
                            <input class="textbox" type="password" name="password" minlength="5" />                          
                    </td>
                </tr>                   
                <tr style="">
                    <td colspan="2">
                        <input type="checkbox" name="terms" style="margin-top: 0.8em; margin-left: 0px"><label id="text">I agree to the <a href="/document/legal">terms and conditions</a> of qhojo inc.</label>
                    </td>
                </tr>                    
                <tr style="">
                    <td colspan="2">
                           <input type="submit" value="Submit" style="margin-right:0.5em; margin-left: 0px; margin-top: 0.8em" />
                    </td>
                </tr>    
                <tr style="">
                    <td colspan="2">
                        <br/>
                        Already have an account? Sign in <a href="/user/login/">here</a>.
                    </td>
                </tr>                           
            </table>                    
        </form>           
    </div>
</div>

<?php elseif ($this->state == 2 || $this->state == 4): ?>
    
<div id="masterdiv">
    <div id="mainheading">Additional Fields</div>
    <hr/>
    <div class="subcontent">
        Before you post or reserve an item, we'll need to know a couple more things about you. Fill out the fields below to continue:
        <br/><br/>
        <form id="additionalform" action="/user/signup/null/5" method="post">
            <table>                  
                <tr>
                    <td>
                            Phone Number:
                    </td>
                    <td>
                            <input class="textbox required" type="text" id="phonenumber" name="phonenumber" />                          
                    </td>
                </tr>
                <tr>
                    <td>
                            Profile Picture:
                    </td>
                    <td>
                        
                        <div id="picture" style="">
                            
                        </div>
                        <button id="add-pictures" value="">Upload Picture</button>                          
                    </td>
                </tr>
                <tr>
                    <td>
                            Billing Information:
                    </td>
                    <td>
                        <div id="picture" style="">
                            
                        </div>
                        <?php if ($this->state == 4 && $this->id != null) { ?> Complete <input type="hidden" name="token" value="<?php echo $this->id;?>"/><?php } else { ?> <a id="expresscheckout" href="/user/signup/null/3">Make billing agreement</a> <?php } ?>
                    </td>
                </tr>                
                <tr style="">
                    <td colspan="2">
                           <input type="submit" value="Submit" style="margin-right:0.5em; margin-top: 0.8em" />
                    </td>
                </tr>                       
            </table>                    
        </form>       
    </div>
</div>

<div id="dialog-form" title="Upload User Pictures" style="overflow: hidden">
  <iframe id="uploaderframe" src="" style="width: 100%; height: 100%;" frameborder="0"/>
</div>

<?php else: ?>

Error

<?php endif; ?>