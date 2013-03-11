<head>
	<script type="text/javascript" src="/js/user/dashboard.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/user/dashboard.css" media="screen" />
        <script type="text/javascript" src="/js/jquery-ui.js"></script>
        <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" media="screen" />
</head>


<div id="masterdiv">
    <div class="mainheading">
        dashboard
    </div>
    <br/>
    <div class="subheading">
        borrows
    </div>
    <div id="borrows" class="subcontent" style="">
        <a href="#" id="borrowscurrentlink" style="font-weight: bold">current</a> | <a href="javascript:void(0);" id="borrowspastlink">past</a>
        <br/>
        <br/>
        <div id="currentborrows" style=" overflow: hidden;white-space: nowrap;">
            <?php $i=0; $items = $viewmodel['borrows']['current']; ?>
            <?php require('dashboard_embed.php'); ?>
        </div>
        <div id="pastborrows" style="display:none; overflow: hidden;white-space: nowrap;">
            <?php $i=0; $items = $viewmodel['borrows']['past']; ?>
            <?php require('dashboard_embed.php'); ?>
        </div>        
    </div>        
    <br/>
    <div class="subheading">
        loans
    </div>
    <div id="loans" class="subcontent">
        <a href="#" id="loanscurrentlink" style="font-weight: bold">current</a> | <a href="javascript:void(0);" id="loanspastlink">past</a>
        <br/>
        <br/>
        <div id="currentloans" style=" overflow: hidden;white-space: nowrap;">
            <?php $i=0; $items = $viewmodel['loans']['current']; ?>
            <?php require('dashboard_embed.php'); ?>
        </div>
        <div id="pastloans" style="display:none; overflow: hidden;white-space: nowrap;">
            <?php $i=0; $items = $viewmodel['loans']['past']; ?>
            <?php require('dashboard_embed.php'); ?>
        </div>        
    </div>
</div>


 




