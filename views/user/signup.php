<head>
    <script type="text/javascript" src="/js/jquery-1.6.min.js"></script>
    <script type="text/javascript" src="/js/jquery.form.js"></script> 
    <script type="text/javascript" src="/js/user/signup.js"></script>
</head>

<?php if ($this->stateid == 0) { ?>
    
<div id="masterdiv">
    <div class="mainheading">Sign Up</div>
    <div class="subcontent">
        <form id="myForm" action="/user/signup/null/1" method="post">
            <table>                  
                <tr>
                    <td>
                            Firstname:
                    </td>
                    <td>
                            <input type="text" name="firstname" />
                    </td>   
                </tr>
                <tr>
                    <td>
                            Email:
                    </td>
                    <td>
                            <input type="text" name="emailaddress" />
                    </td>
                </tr>
                <tr>
                    <td>
                            Password:
                    </td>
                    <td>
                            <input type="text" name="password" />
                    </td>
                </tr>                        
                <tr  style="">
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