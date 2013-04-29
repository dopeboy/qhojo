<head>
  
    <link type="text/css" rel="stylesheet" href="/css/user/login.css">
    <script type="text/javascript" src="/js/jquery.validate.min.js"> </script>
      <script type="text/javascript" src="/js/user/edit.js"></script>
         <script type="text/javascript" src="/js/jquery-ui.js"> </script>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" /> 
</head>

<title>qhojo - edit user profile</title>

<div id="masterdiv">
    <?php if ($this->state == 0): ?>
    <div id="mainheading">
        Edit User Profile - <?php echo $viewmodel["USER"]["FIRST_NAME"]; ?>
    </div>    
    <hr/>
    <form id="myForm" method="POST" action="/user/edit/<?php echo $viewmodel["USER"]["ID"]; ?>/1" style="margin: 0">
        <div class="subheading">
            Networks
        </div>
        <div class="subcontent">
            <?php if ($viewmodel["CURRENT_NETWORKS"] == null) { echo "<i>No networks</i>"; } else { ?>        

            <?php foreach($viewmodel["CURRENT_NETWORKS"] as $network) { ?>       

                <img src="/img/network/<?php echo $network['ICON_IMAGE'] ?>" title="<?php echo $viewmodel["USER"]["FIRST_NAME"]; ?> is a member of the <?php echo $network['NETWORK_NAME'] ?> network."></a>

            <?php } } ?> 
                <br/><br/>
            <?php if ($viewmodel["OUTSIDE_NETWORKS"] == null) { echo "<i>No networks to add</i>"; } else { ?>        

                Add a network: 
                <select id="networklist" name="networkid">
                    <?php foreach($viewmodel["OUTSIDE_NETWORKS"] as $network) { ?>       

                    <option email="" value="">Select a network</option>
                    <option email="" value="">---------</option>
                    <?php foreach($viewmodel["OUTSIDE_NETWORKS"] as $network) { ?>
                    <option email="<?php echo $network['EMAIL_EXTENSION'] ?>" value="<?php echo $network['ID'] ?>"><?php echo $network['NAME'] ?></option>
                    <?php } ?>
                
                </select>

                <span id="networkdynamic" style="display: none">
                    <input class="textbox" type="text" id="networkemail" name="networkemail" /><span id="atsymbol">@</span><span id="networkemailextension"></span>      
               </span>

            <?php } } ?> 
                <br/><br/>
                <input id="submit" type="submit" value="submit" disabled>
        </form>      
    </div>
    <?php elseif ($this->state == 2):  ?>
    <div id="mainheading">
        User Profile Submitted
    </div>    
    <hr/>
    Your profile has been changed. If you added a new network, check your network's email address and follow the instructions in the message.
    <?php else: ?>
    <div id="mainheading">
        Error
    </div>
    <hr/>
    There was an error with editing your user profile.
    <?php endif; ?>
</div>