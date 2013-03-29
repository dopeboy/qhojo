<head>
    <script type="text/javascript" src="/js/jquery-1.6.min.js"></script>
    <script type="text/javascript" src="/js/jquery.form.js"></script> 
    <script type="text/javascript" src="/js/user/login.js"></script>
    <link type="text/css" rel="stylesheet" href="/css/user/login.css">
</head>

<div id="masterdiv">
    <div id="mainheading">Login</div>
    <hr/>
    <?php if ($this->state == 1) { ?>
    <div class="error">
        <li>Invalid username / password</li>
    </div>
    <?php } else if ($this->state >= 2) { ?>
    <div class="error">
        <li>You're gonna need to login for this page.</li>
    </div>    
    <?php } ?>
    <div class="subcontent">
        <form id="myForm" action="/user/verify" method="post">
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
                           <input type="submit" value="Login" style="margin-right:0.5em; margin-top: 0.8em" />
                    </td>
                </tr>
                <tr style="">
                    <td colspan="2">
                        <br/>
                        No account? Sign up <a href="/user/signup/null/0">here</a>
                    </td>
                </tr>                        
            </table>
        </form>        
    </div>
</div>


