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
    
        <link href="/css/font-awesome.css" rel="stylesheet">
        <link href='/css/lobster.css' rel='stylesheet' type='text/css'>
        
        <script src="/js/vendor/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
        
        <script src="/js/vendor/bootstrap.min.js"></script>
        <script src="/js/jquery.form.js"></script> 
        
        <script src="/js/main.js"></script>
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
             
                        <div class="nav-collapse collapse custom">
                            <ul class="nav">
                                <li class=""><a href="/" style="font-family: 'Lobster', cursive;font-size: 26px">Qhojo</a></li>
                                <?php if (User::isUserSignedIn()) { ?>
                                    <li class=""><a href="/item/search.php">Borrow</a></li>
                                    <li><a href="/item/post">Lend</a></li>                                    
                                    <li><a href="/document/howitworks">How it works</a></li>
                                <?php } ?>
                            </ul>
                            <ul class="nav pull-right">
                                <?php if (User::isUserSignedIn()) { ?>
                                
                                <li>
                                    <a role="menuitem" tabindex="-1" href="/user/dashboard">Dashboard</a>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">
                                      <?php echo $_SESSION["USER"]["NAME"];?>
                                      <b class="caret"></b>
                                    </a>
                                    
                                    <ul id="menu1" class="dropdown-menu" role="menu" aria-labelledby="drop4">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/user/index/<?php echo $_SESSION["USER"]["USER_ID"];?>">Profile</a></li>
                                        <li role="presentation" class="divider"></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/user/signout">Sign out</a></li>
                                    </ul>
                                </li>
                                  
                                <?php } else { ?>
                                    <li><a href="/invite/submit">Join</a></li>
                                    <li><a href="/user/signin">Sign in</a></li>
                                <?php } ?>
                            </ul>
                        </div><!--/.nav-collapse -->
                    </div>
                </div>
            </div>

            <div class="container">
                <div id="error-banner" class="alert alert-error">
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
                    <?php if (User::isUserSignedIn()) { ?>
                    <li><a href="/document/about">about</a></li>
                    <li><a href="http://qhojo.wordpress.com/">blog</a></li>
                    
                        <li><a href="/document/contact">contact</a></li>
                        <li><a href="/document/legal">legal</a></li>
                   
                    <li class="disabled"><a href="javascript:void(0);">v2.0.0</a></li>
                    <li class="disabled"><a href="javascript:void(0);">© 2013 Qhojo LLC
                    <li class="disabled">
                        <div style="" class="fb-like" data-href="https://www.facebook.com/pages/Qhojo/131352593731804" data-width="250" data-layout="button_count" data-show-faces="true" data-send="false"></div>
                    </li>
                    <li class="disabled">
                        <div id="twitter-share-button" style="">
                            <a href="https://twitter.com/share" class="twitter-share-button" data-url="https://qhojo.com" data-text="I &lt;3 @qhojonyc" data-via="qhojonyc" data-dnt="true">Tweet</a>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>                        
                        </div>
                    </li>
                     <?php } ?>
              </ul>
            </div>
        </div>

    </body>

    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-40056123-1']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>
    
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>    

    <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>

        
</html>
