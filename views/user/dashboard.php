<head>
	<script type="text/javascript" src="/js/user/dashboard.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/user/dashboard.css" media="screen" />
        <script type="text/javascript" src="/js/jquery-ui.js"></script>
        <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" media="screen" />
</head>


<div id="masterdiv">
    <div id="mainheading">
        dashboard
    </div>
    <hr/>
    <a href="#" id="requestslink" style="font-weight: bold; text-decoration: underline">requests</a> | <a href="javascript:void(0);" id="borrowslink">borrows</a> | <a href="javascript:void(0);" id="loanslink">loans</a>
    <br/><br/>
    <div id="requests" class="subcontent"  style="">
        <div class="subheading">sent to me</div><br/>
        <div id="currentrequests" style=" ">
            <?php $i=0; $items = $viewmodel['requests']; ?>
            
            <table id="requestable" style="border: 1px solid; text-align: center;border-collapse:collapse; ">
                <tr id="cardheader" class="cardrow">
                    <td class="title" style="color: black">
                        Item
                    </td>  
                    <td class="rate" style="color: black">
                        Rate <br/> (per day)
                    </td>           
                    <td class="requester">
                        Requester
                    </td>             
                    <td class="duration">
                        Duration (days)
                    </td>           
                    <td class="message">
                        Message
                    </td>
                    <td class="actions">
                        Actions
                    </td>
                </tr>
            
                <?php if (empty($items)) { ?> <tr><td colspan="10"><i>No data</i></td></tr> <?php } ?>

                <?php foreach($items as $item) { ?>

                <tr class="cardrow" itemid="<?php echo $item['ITEM_ID']; ?> ">
                    <td class="title">
                         <a href="/item/index/<?php echo $item['ITEM_ID'];?>"><?php echo $item['TITLE'];?></a>
                    </td>
                    <td class="rate">
                        $<?php echo $item['RATE']; ?>
                    </td>
                    <td class="borrower">
                        <a href="/user/index/<?php echo $item['REQUESTER_ID']?>"><?php echo $item['REQUESTER_FIRST_NAME'];?></a>
                    </td>    
                    <td class="duration">
                        <span class="duration"><?php echo $item['DURATION']; ?></span>
                    </td>
                     <td class="message">
                        <?php echo $item['MESSAGE']; ?>
                    </td>
                    <td class="actions" style="">
                        <a href="#" class="menubutton">Menu&#x25BC;</a>
                        <ul class="menu" style="width:80px; text-align: left; display:none; position:fixed;font-size:100%;">
                          <li class=""><a class="accept" href="/item/accept/<?php echo $item['REQUEST_ID'];?>/">Accept</a></li>
                          <li class=""><a class="ignore" href="/item/ignore/<?php echo $item['REQUEST_ID'];?>/">Ignore</a></li>
                          <li class=""><a class="contact" href="mailto: <?php echo $item['REQUESTER_EMAIL_ADDRESS'];?>/">Contact</a></li>
                        </ul>                
                    </td>
                </tr>
            <?php $i++; ?>
            <?php } ?>               
            </table>                
        </div>
    </div>   
    
    <div id="borrows" class="subcontent" style="display: none;">
        <div class="subheading">current</div><br/>
        <div id="currentborrows" style=" ">
            <?php $i=0; $items = $viewmodel['borrows']['current']; ?>
            <?php require('dashboard_embed.php'); ?>
        </div>
        <br/>
        <div class="subheading">past</div><br/>
        <div id="pastborrows" style="">
            <?php $i=0; $items = $viewmodel['borrows']['past']; ?>
            <?php require('dashboard_embed.php'); ?>
        </div>        
    </div>     

    <div id="loans" class="subcontent" style="display:none">
        <div class="subheading">current</div><br/>
        <div id="currentloans" style="">
            <?php $i=0; $items = $viewmodel['loans']['current']; ?>
            <?php require('dashboard_embed.php'); ?>
        </div>
        <br/>
        <div class="subheading">past</div><br/>        
        <div id="pastloans" style="">
            <?php $i=0; $items = $viewmodel['loans']['past']; ?>
            <?php require('dashboard_embed.php'); ?>
        </div>        
    </div>
    
<!--    <div class="subheading">
        borrows
    </div>
       
    <br/>
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
    </div>-->
    

    
</div>


 




