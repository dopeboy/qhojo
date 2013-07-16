<?php if ($_COOKIE['iwashere'] != "yes") { setcookie("iwashere", "yes", time()+315360000);  }?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="/css/maintemplate.css">
        <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="/js/vendor/bootstrap.min.js"></script>
        <script src="/js/maintemplate.js"></script>

        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>        
    </head>
    
    <body>

        <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <div id="wrap">
             
            <div class="navbar navbar-inverse navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <a class="brand" href="/">Qhojo</a>
                        <div class="nav-collapse collapse">
                            <ul class="nav">
                                <li><a class="rentlink" href="#">Rent</a></li>
                                <li><a href="#">Post</a></li>
                            </ul>
                            <ul class="nav pull-right">
                                <li><a class="hiwlink" href="/#rent">How to Rent</a></li>
                                <li><a class="hiwlink" href="/#post">How to Post</a></li>
                                <li class=""><a href="/user/join">Join</a></li>
                                <li class=""><a href="/user/login">Login</a></li>
                            </ul>
                        </div><!--/.nav-collapse -->
                    </div>
                </div>
            </div>
        
        
            <div id="parent" class="container">
                <div class="subcontainer" id="searchcontainer" style="">
                    <div class="row">
                        <div class="span12">
                            <form id="searchform" class="form-search" style="">
                                <input id="searchbar" type="text" class="span6 search-query btn-large" placeholder="Search by camera model, manufacturer, type or zipcode." style="">
                                <button type="submit" class="btn btn-large">
                                        <i class="icon-search"></i>
                                        Search
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <?php
                    require($viewloc);
                ?>                

            </div> <!-- /container -->
            
            <div id="push"></div>
        </div>
        
        <div id="footer">
            <div class="container">
                <p class=""style="margin: 20px 0;">about | blog | legal | contact | v0.2.2-8 | © 2013 qhojo LLC</p>
            </div>
        </div>
       
        
    </body>
</html>