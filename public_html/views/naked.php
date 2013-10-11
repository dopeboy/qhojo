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
    <link rel="stylesheet" href="/css/naked.css">
    <link href='/css/lobster.css' rel='stylesheet' type='text/css'>
</head>
    
<body>

<!--[if lt IE 7]>
    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->

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
                     </ul>
                     <ul class="nav pull-right">
                        <li><a href="/user/signin">Sign in</a></li>
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

</body>
    
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
<script src="/js/vendor/bootstrap.min.js"></script>
<script src="/js/jquery.form.js"></script> 

<script src="/js/main.js"></script>

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

<script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        
</html>
