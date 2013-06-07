<?php if ($_COOKIE['iwashere'] != "yes") { setcookie("iwashere", "yes", time()+315360000);  }?>

<html>

<head>
	<script type="text/javascript" src="/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="/js/maintemplate.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/maintemplate.css" media="screen" />        	
        <script type="text/javascript" src="/js/jquery.form.js"></script> 
        <?php require("analytics.php"); ?>
</head>

<body>
    
    <div id="container">
        <div id="header">
            <?php if ($_COOKIE['iwashere'] != "yes") { ?>
            <div id="notify" style="background: rgba(255,0,0, .4); height:40px; text-align: center; line-height:2.5em">
                <b>First time visitor? Check out our <a href="/document/howitworks/">how-it-works guide</a>.</b>
            </div>
            <?php } ?>
            <div id="banner">
                <div class="threecol">
                    <div style="text-align:left;margin-top: 10px">
                        
                        <form id="searchform" action="" method="get" style="padding:0px; margin: 0px">
                            <input id="search" type="text" name="" value="" style=""/> 
                        </form>
                    </div>

                    <div><a href="/"><img src="/img/logo4.png"/></a></div>

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
                <a href="/document/about/">about</a> | <a href="/document/howitworks/">how it works</a> | <a href="http://qhojo.wordpress.com/">blog</a> | <a href="/document/legal/">legal</a> | <a href="/document/contact/">contact</a> | <?php global $version; echo $version; ?> |  &#169; 2013 qhojo LLC <a href="https://twitter.com/qhojonyc"><img src="/img/twitter-bird-light-bgs.png" style="margin-left: 10px"></a>
            </div>
        </div>
    </div>

</body>
</html>

