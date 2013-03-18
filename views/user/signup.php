<head>
    <script type="text/javascript" src="/js/jquery-1.6.min.js"></script>
    <script type="text/javascript" src="/js/jquery.form.js"></script> 
    <script type="text/javascript" src="/js/user/signup.js"></script>
    <link type="text/css" rel="stylesheet" href="/css/user/login.css">

</head>

<?php if ($this->state == 0) { ?>
    
<div id="masterdiv">
    <div id="mainheading">Sign Up</div>
    <hr/>
    <div class="subcontent">
        <form id="myForm" action="/user/signup/null/1" method="post">
            <table>                  
                <tr>
                    <td>
                            Firstname:
                    </td>
                    <td>
                            <input class="textbox" type="text" name="firstname" />
                    </td>   
                </tr>
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
                            <input class="textbox" type="text" name="password" />
                    </td>
                </tr>
                <tr>
                    <td>
                            Phone number:
                    </td>
                    <td>
                            <input class="textbox" type="text" name="phonenumber" />
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

<?php } ?>