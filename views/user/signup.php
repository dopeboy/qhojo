<head>
    <script type="text/javascript" src="/js/jquery.form.js"></script> 
    <script type="text/javascript" src="/js/user/signup.js"></script>
    <link type="text/css" rel="stylesheet" href="/css/user/login.css">
    <script src="/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/jquery-ui.js"> </script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
</head>

<?php if ($this->state == 0):  ?>
    
<div id="masterdiv">
    <div id="mainheading">Sign Up</div>
    <hr/>
    <div class="subcontent">
        <form id="myForm" action="/user/signup/null/1" method="post">
            <table>                  
                <tr>
                    <td>
                            Email:
                    </td>
                    <td>
                            <input class="textbox" type="text" name="emailaddress" />
                    </td>
                </tr>
                <tr>
                    <td>
                            Password:
                    </td>
                    <td>
                            <input class="textbox" type="password" name="password" />                          
                    </td>
                </tr>                   
                <tr style="">
                    <td colspan="2">
                           <input type="submit" value="Submit" style="margin-right:0.5em; margin-top: 0.8em" />
                    </td>
                </tr>    
                <tr style="">
                    <td colspan="2">
                        <br/>
                        Already have an account? Sign in <a href="/user/login/">here</a>
                    </td>
                </tr>                           
            </table>                    
        </form>       
    </div>
</div>

<?php elseif ($this->state == 2): ?>
    
<div id="masterdiv">
    <div id="mainheading">Additional Fields</div>
    <hr/>
    <div class="subcontent">
        Before you post or reserve an item, we'll need to know a couple more things about you. Fill out the fields below to continue:
        <br/><br/>
        <form id="additionalform" action="/user/signup/null/3" method="post">
            <table>                  
                <tr>
                    <td>
                            First Name:
                    </td>
                    <td>
                            <input class="textbox" type="text" name="firstname" />
                    </td>
                </tr>
                <tr>
                    <td>
                            Phone Number:
                    </td>
                    <td>
                            <input class="textbox" type="text" id="phonenumber" name="phonenumber" />                          
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