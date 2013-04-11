<html>

<head>
	<script type="text/javascript" src="/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="/js/maintemplate.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/maintemplate.css" media="screen" />        	
        <script type="text/javascript" src="/js/jquery.form.js"></script> 
</head>

<body>
    
    <div id="container">
        <div id="header">
            <div id="banner">
                <div class="threecol">
                    <div style="text-align:left;margin-top: 10px">
                        
                        <form id="searchform" action="/item/search/" method="get" style="padding:0px; margin: 0px">
                            <input id="search" type="text" name="" value="" style=""/> 
           
                        </form>
                    </div>

                    <div><a href="/item/main"><img src="/img/logo2.png"/></a></div>

                    <div class="headerlinks" style="text-align: right; margin-top: 15px">
                        
                        <a href="/">home</a> | <a href="/user/dashboard">dashboard<?php if (isset($cc) && $cc > 0) { echo '(' . $cc . ')'; } ?></a> | <a href="/item/post/null/0">post</a> | <a href="/document/faq">faq</a> | <?php if ($this->userid == null ) { ?> <a href="/user/login/null/0">login</a> / <a href="/user/signup/null/0">signup</a> <?php } else { ?> <a href="/user/logout">logout</a> <?php } ?>
                    </div>
                </div>
            </div>
        </div>  
        
        <div id="body">
            <?php

            require($viewloc);

            ?>
        </div>

        <div id="footer">
            <div id="footercontent">
                blog | <a href="/document/legal/">legal</a> | about | v<?php global $version; echo $version; ?>
            </div>
        </div>
    </div>

</body>
</html>

