<head>
    <script type="text/javascript" src="/js/jquery.form.js"></script> 
    <script type="text/javascript" src="/js/user/signup.js"></script>
    <link type="text/css" rel="stylesheet" href="/css/user/login.css">
    <script src="/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/jquery-ui.js"> </script>
    <script type="text/javascript" src="/js/jquery.validate.min.js"> </script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
</head>

<?php if ($this->state == 0):  ?>

<title>qhojo - sign up</title>

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
                            Location:
                    </td>
                    <td>
                        <select id ="location" name="locationid" style="height: 30px; " class="required dropdown-menu">
                            <option></option>
                            <?php foreach ($viewmodel[0] as $location) { ?>
                            <option value="<?php echo $location['ID']; ?>"><?php echo $location['NEIGHBORHOOD'] . ',' . $location['BOROUGH']; ?></option>
                            <?php } ?>
                        </select>
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
                            <input class="required textbox" type="password" name="password" minlength="5" />                          
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

<title>qhojo - additional fields signup</title>

<div id="masterdiv">
    <div id="mainheading">Additional Fields</div>
    <hr/>
    <div class="subcontent">
        Before you post or reserve an item, we'll need to know a couple more things about you (like how to pay you, how to text you, and what you look like). Fill out the fields below to continue:
        <br/><br/>
        <form id="additionalform" action="/user/signup/null/5" method="post" style="margin: 0">
            <table>  
                <tr style="height: 25px">
                    <td>
                            Billing Information (required):
                    </td>
                    <td>
                        <div id="billing" style="">
                            <?php if ($this->state == 4 && $this->id != null) { ?> Complete <input type="hidden" name="token" value="<?php echo $this->id;?>"/><?php } else { ?> <a id="expresscheckout" href="/user/signup/null/3">Make Paypal Billing Agreement</a>&nbsp;<img id="firstloader" style="display:none" src="/img/ajax-loader.gif"><?php } ?>                            
                        </div>
                    </td>
                </tr>     
                <tr style="height: 25px">
                    <td>
                            Phone Number (required):
                    </td>
                    <td>
                            <input class="required textbox" type="text" id="phonenumber" name="phonenumber" />                          
                    </td>
                </tr>
                <tr style="height: 25px">
                    <td>
                            Profile Picture (required):
                    </td>
                    <td>
                        
                        <div id="picture" style="">
                            
                        </div>
                        <button id="add-pictures" value="">Upload Picture</button>                          
                    </td>
                </tr>
                <tr style="height: 25px">
                    <td>Network (recommended):</td>
                    <td>
                        <select id="networklist" name="networkid">
                            <option email="" value="">Select a network</option>
                            <option email="" value="">---------</option>
                            <?php foreach($viewmodel as $network) { ?>
                            <option email="<?php echo $network['EMAIL_EXTENSION'] ?>" value="<?php echo $network['ID'] ?>"><?php echo $network['NAME'] ?></option>
                            <?php } ?>
                        </select>
                        <input class="textbox" type="text" id="networkemail" name="networkemail" style="display:none"/><span id="atsymbol" style="display:none">@</span><span id="networkemailextension" style="display:none"></span>
                    </td>
                </tr>
                <tr style="">
                    <td colspan="2">
                           <input id="extrasubmitbutton" type="submit" value="Submit" style="margin-right:0.5em; margin-top: 0.8em" /><img id="secondloader" style="display:none" src="/img/ajax-loader.gif">
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