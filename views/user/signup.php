<head>
    <script type="text/javascript" src="/js/jquery.form.js"></script> 
    <script type="text/javascript" src="/js/user/signup.js"></script>
    <link type="text/css" rel="stylesheet" href="/css/user/login.css">
    <script src="/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/jquery-ui.js"> </script>
    <script type="text/javascript" src="/js/jquery.validate.min.js"> </script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="https://js.balancedpayments.com/v1/balanced.js"></script>
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
                            <option value="<?php echo $location['ID']; ?>"><?php echo $location['NEIGHBORHOOD'] . ',' . $location['BOROUGH_SHORT']; ?></option>
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

<?php elseif ($this->state == 2): ?>

<title>qhojo - additional fields signup</title>

<div id="masterdiv">
    <div id="mainheading">Additional Fields</div>
    <hr/>
    <div class="subcontent">
        Before you borrow or lend an item, we'll need to know a couple more things about you. 
        <br/><br/>
        <form id="extraFormNonBill" action="/user/signup/null/3" method="post" style="margin: 0">
            <table>  
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

<?php elseif ($this->state == 4): ?>

<title>qhojo - borrower billing fields signup</title>

<div id="masterdiv">
    <div id="mainheading">Borrower Billing Fields</div>
    <hr/>
    <div class="subcontent">
        <?php echo $viewmodel["FIRST_NAME"] ?>, before you rent an item, we're gonna need to know how you're going to pay us in the future. Right now, we currently accept payments from all major credit cards. We will <u>always</u> let you know before we charge you.
        <br/><br/>
        <form id="credit-card-form" action="/user/signup/null/5" method="post" style="margin: 0">                 
            <table>  
                <tr style="height: 25px">
                    <td>
                            Card Number:
                    </td>
                    <td>
                        <input type="text"
                       autocomplete="off"
                       placeholder="Card Number"
                       class="cc-number">                
                    </td>
                </tr>      
                <tr style="height: 25px">
                    <td>
                            Expiration (MM/YYYY):
                    </td>
                    <td>
                        <input type="text"
                               autocomplete="off"
                               placeholder="Expiration Month"
                               class="cc-em">
                        <span>/</span>
                        <input type="text"
                               autocomplete="off"
                               placeholder="Expiration Year"
                               class="cc-ey">           
                    </td>
                </tr>   
                <tr style="height: 25px">
                    <td>
                            CSC:
                    </td>
                    <td>
                        <input type="text"
                       autocomplete="off"
                       placeholder="CSC"
                       class="cc-csc">             
                    </td>
                </tr>   
                <tr style="">
                    <td colspan="2">
                        <button type="submit" id="debitsubmitbutton" class="btn">Submit</button><img id="secondloader" style="display:none" src="/img/ajax-loader.gif">
                    </td>
                </tr>                    
            </table>             
        </form>       
    </div>
</div>

<?php elseif ($this->state == 6): ?>

<title>qhojo - lender billing fields signup</title>

<div id="masterdiv">
    <div id="mainheading">Lender Billing Fields</div>
    <hr/>
    <div class="subcontent">
        <?php echo $viewmodel["FIRST_NAME"] ?>, before you post an item, we're gonna need to know how to pay you. Fill in your PayPal details below. If you don't have a PayPal account, create one <a href="https://www.paypal.com/home">here</a>.
        <br/><br/>
        <form id="creditForm" action="/user/signup/null/7" method="post" style="margin: 0">                 
            <table>  
                <tr style="height: 25px">
                    <td>
                            First Name on PayPal Account:
                    </td>
                    <td>
                            <input class="required textbox" type="text" id="paypalfirstname" name="paypalfirstname" style="" autocomplete="off"/>                          
                    </td>
                </tr>                
                <tr style="height: 25px">
                    <td>
                            Last Name on PayPal Account:
                    </td>
                    <td>
                            <input class="required textbox" type="text" id="paypallastname" name="paypallastname" style="" autocomplete="off"/>                          
                    </td>
                </tr>                
                <tr style="height: 25px">
                    <td>
                            PayPal Email Address:
                    </td>
                    <td>
                            <input class="required textbox" type="text" id="paypalemail" name="paypalemail" style="" autocomplete="off"/>                          
                    </td>
                </tr>
                <tr style="">
                    <td colspan="2">
                           <input id="creditsubmitbutton" type="submit" value="Submit" style="margin-right:0.5em; margin-top: 0.8em" /><img id="secondloader" style="display:none" src="/img/ajax-loader.gif">
                    </td>
                </tr>                  
            </table>             
        </form>       
    </div>
</div>

<?php else: ?>

Error

<?php endif; ?>