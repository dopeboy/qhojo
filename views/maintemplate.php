<html>

<head>
	<script type="text/javascript" src="/js/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="/js/maintemplate.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/maintemplate.css" media="screen" />        	
        <script type="text/javascript" src="/js/jquery.form.js"></script> 
</head>

<body>
    
    <div id="container">
        <div id="header">
            <div id="banner">
                <div class="threecol">
                    <div style="text-align:left;margin-top: 13px">
                        
                        <form id="searchform" action="/item/search/" method="get" style="padding:0px; margin: 0px">
                            <input id="search" type="text" name="" value="" style="width:200px;"/> 
                            <input type="submit" value="search" />
                        </form>
                    </div>

<!--                    <div>qh&#3232;j&#3232</div>-->
<div><img src="/img/logo2.png"></div>

                    <div style="text-align: right; margin-top: 15px">
                        
                        <a href="/item/main">home</a> | <a href="/user/dashboard">dashboard</a> | <a href="/item/post/null/0">post</a> | <?php if ($this->userid == null ) { ?> <a href="/user/login/null/0">login</a> <?php } else { ?> <a href="/user/logout">logout</a> <?php } ?>
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
                blog | about | v<?php global $version; echo $version; ?>
            </div>
        </div>
    </div>

</body>
</html>

