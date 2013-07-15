<head>

</head>

<title>qhojo - confirm network</title>

<div id="masterdiv">
    <?php if ($this->state == 1): ?>
    <div id="mainheading">
        Network Affiliation Confirmed
    </div>    
    <hr/>
    Thanks <?php echo $viewmodel['FIRST_NAME'] ?>. Your affiliation with the <?php echo $viewmodel['NAME'] ?> network is confirmed.
    <br/><br/>
    You will see the logo below appear on your profile page:
    <br/>
    <img src='/img/network/<?php echo $viewmodel['ICON_IMAGE'] ?>'>
    
    <?php else:  ?>
    <div id="mainheading">
        Error
    </div>
    <hr/>
    There was an error with confirming the network affiliation.
    <?php endif; ?>
</div>