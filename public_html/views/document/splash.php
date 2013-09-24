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
    <link rel="stylesheet" href="/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" href="/css/document/splash.css">
</head>
    
<body>

<!--[if lt IE 7]>
    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->
<div id="wrap">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">
                <div class="sheet" id="sign-in" style="">
                    <legend>Sign in</legend>
                    <form class="form-horizontal form-submit" action="/user/signin/null/1" method="post">
                        <div class="control-group">
                          <div class="controls">
                            <input class="input-block-level" type="text" id="email" name="email" placeholder="Email">
                          </div>
                        </div>
                        <div class="control-group">

                          <div class="controls">
                            <input class="input-block-level" type="password" id="passwd" name="password" placeholder="Password">
                          </div>
                        </div>
                        <div class="control-group">
                          <div class="controls">
                            <button type="submit" class="btn btn-large btn-primary">Sign in</button>
                          </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="sheet" id="invite" style="">
                    <legend>Enter Invite Code</legend>
                     <form class="form-horizontal form-submit" action="/invite/submit/null/1" method="post">
                        <div class="control-group">
                          <div class="controls">
                            <input class="input-block-level" type="text" id="code" name="code" placeholder="Invite Code">
                          </div>
                        </div>
                        <div class="control-group">
                          <div class="controls">
                            <button type="submit" class="btn btn-large btn-primary">Submit</button>
                          </div>
                        </div>
                    </form>
                    
                    <div id="request-code">
                        Don't have a code? <a href="/invite/request">Request one</a>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div id="push"></div>
</div>

<div id="footer">
    <div class="container">
    <ul class="nav nav-pills">
        <li class="not-disabled"><a href="/document/about">about</a></li>
            <li class="not-disabled"><a href="http://qhojo.wordpress.com/">blog</a></li>
            <li class="disabled"><a href="javascript:void(0);">v2.0.0</a></li>
            <li class="disabled"><a href="javascript:void(0);">© 2013 Qhojo LLC</a></li>
      </ul>    
        </div>
</div>

</body>
    
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
<script src="/js/vendor/bootstrap.min.js"></script>
<script src="/js/jquery.form.js"></script> 

<script src="/js/document/splash.js"></script>

<script>
    var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src='//www.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
</script>

<script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        
</html>
