<head>
    <script type="text/javascript" src="/js/jquery.form.js"></script> 
    <script type="text/javascript" src="/js/user/addcard.js"></script> 
    <link type="text/css" rel="stylesheet" href="/css/user/login.css">
    <script src="/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/jquery-ui.js"> </script>
    <script type="text/javascript" src="/js/jquery.validate.min.js"> </script>
    <link rel="stylesheet" href="/css/jquery-ui.css" />
    <script type="text/javascript" src="https://js.balancedpayments.com/v1/balanced.js"></script>
</head>

<title>qhojo - add credit card</title>

<div id="masterdiv">
    <div id="mainheading">Add a credit card</div>
    <hr/>
    <div class="subcontent">
        <input id="userid" type="hidden" value="<?php echo $viewmodel["ID"]; ?>">
        <?php echo $viewmodel["FIRST_NAME"] ?>, fill out the fields below to add your credit card. With it, you will be able to start renting items. We currently accept payments from all major credit cards. We will <u>always</u> let you know before we charge you.
        <br/><br/>
        <div style="display:inline-block; width: 100%">
            <div style="float:left; width:65%">
                <form id="credit-card-form" action="/user/signup/null/5" method="post" style="margin: 0">                 
                    <table>  
                        <tr style="height: 25px">
                            <td>
                                <b>Card Number:</b>
                            </td>
                            <td>
                                <input type="text"
                               autocomplete="off"
                               placeholder="Card Number"
                               class="cc-number textbox"
                               >                
                            </td>
                        </tr>      
                        <tr style="height: 25px">
                            <td>
                                <b>Expiration (MM/YYYY):</b>
                            </td>
                            <td>
                                <input type="text"
                                       autocomplete="off"
                                       placeholder="Expiration Month"
                                       class="cc-em textbox"
                                       >
                                <span>/</span>
                                <input type="text"
                                       autocomplete="off"
                                       placeholder="Expiration Year"
                                       class="cc-ey textbox">           
                            </td>
                        </tr>   
                        <tr style="height: 25px">
                            <td>
                                    <b>Card Security Code:</b>
                            </td>
                            <td>
                                <input type="text"
                               autocomplete="off"
                               placeholder="CSC"
                               class="cc-csc textbox">             
                            </td>
                        </tr>   
                        <tr>
                            <td colspan="2">
                                <img src="/img/cc_logos.png" style="margin-top: 5px; margin-bottom: 5px">
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
            <div style="float: left; width: 35%">
                <div style="float: left;margin-right:10px; margin-top: 10px">
                    <!-- Begin DigiCert/ClickID site seal HTML and JavaScript -->
                    <div id="DigiCertClickID_DGkXoYAj" data-language="en_US" style="">
                            <a href="http://www.digicert.com/ssl-certificate.htm"></a>
                    </div>
                    <script type="text/javascript">
                    var __dcid = __dcid || [];__dcid.push(["DigiCertClickID_DGkXoYAj", "13", "m", "black", "DGkXoYAj"]);(function(){var cid=document.createElement("script");cid.async=true;cid.src="//seal.digicert.com/seals/cascade/seal.min.js";var s = document.getElementsByTagName("script");var ls = s[(s.length - 1)];ls.parentNode.insertBefore(cid, ls.nextSibling);}());
                    </script>
                    <!-- End DigiCert/ClickID site seal HTML and JavaScript -->
                </div>
                <div style="float: left; margin-left: 10px; margin-top: 10px">
                    <a href="http://www.balancedpayments.com" target="_blank"><img src="/img/balanced-logo-print.png"></a>
                </div>
            </div>
        </div>
    </div>
</div>