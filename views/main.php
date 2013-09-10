<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <style>
        </style>
        <link rel="stylesheet" href="/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="/css/main.css">
    
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
        
        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
        <script src="/js/vendor/bootstrap.min.js"></script>
        <script src="/js/jquery.form.js"></script> 
        
        <script src="/js/main.js"></script>

        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
        <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        
        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->
        <div id="wrap">
            <div class="navbar navbar-inverse navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <a class="brand" href="/" style="padding: 0px"><img src="/img/logo.gif"></a>
                        <div class="nav-collapse collapse custom">
                            <ul class="nav">
                              
                                <li class=""><a href="/item/search.php">Borrow</a></li>
                                <li><a href="/item/post">Lend</a></li>
                            </ul>
                            <ul class="nav pull-right">
                                <li><a class="hiwlink" href="/#borrow">How to Borrow</a></li>
                                <li><a class="hiwlink" href="/#lend">How to Lend</a></li>
                                <?php if (!empty($_SESSION["USER"]["USER_ID"])) { ?>
                                <li class="dropdown">

                                    <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">

                                      <?php echo $_SESSION["USER"]["NAME"];?>
                                      <b class="caret"></b>
                                    </a>
                                    
                                    <ul id="menu1" class="dropdown-menu" role="menu" aria-labelledby="drop4">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/user/index/<?php echo $_SESSION["USER"]["USER_ID"];?>">Profile</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/user/dashboard">Dashboard</a></li>
                                        <li role="presentation" class="divider"></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/user/signout">SIgn out</a></li>
                                    </ul>
                                </li>
                                  
                                <?php } else { ?>
                                    <li><a href="/user/join">Join</a></li>
                                    <li><a href="/user/signin">Sign in</a></li>
                                <?php } ?>
                            </ul>
                        </div><!--/.nav-collapse -->
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="alert alert-error">
                    <strong>Error: </strong>
                    <span id="error-message"></span>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php require($viewloc); ?>
            </div> <!-- /container -->

            <div id="push"></div>
        </div> 

        <div id="footer">
            <div class="container">
                <ul class="nav nav-pills">
                    <li class="">
                      <a href="#">about</a>
                    </li>
                    <li><a href="#">blog</a></li>
                    <li><a href="#">legal</a></li>
                    <li><a href="#">contact</a></li>
                    <li class="disabled"><a href="">v0.2.2-8</a></li>
                    <li class="disabled"><a href="#">© 2013 qhojo LLC</a></li>
              </ul>
            </div>
        </div>

    </body>
</html>