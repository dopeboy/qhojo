<head>
	<script type="text/javascript" src="/js/item/request.js"></script>
           <link rel="stylesheet" href="/css/item/request.css" />
           <script type="text/javascript" src="/js/jquery.validate.min.js"> </script>
                    <script type="text/javascript" src="/js/jquery-ui.js"> </script>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
</head>

<div id="masterdiv">
    <?php if ($this->state == 0) { ?>  
    
        <title>qhojo - Request <?php echo $viewmodel[0]['TITLE'];?></title>
    
        <form id="myForm" action="/item/request/<?php echo $viewmodel[0]['ITEM_ID'] ?>/1" method="post" style="padding:0px; margin: 0px">
            <div id="mainheading">
                Rental Request - <a href="/item/index/<?php echo $viewmodel[0]['ITEM_ID'];?>"><?php echo $viewmodel[0]['TITLE'];?></a>
            </div> 
            <hr/>
            <div class="subheading">
                Rental Details
            </div> 
            <br/>
            <div style="text-align:center">
                <div id="pricelender" style="display:inline-block;text-align: left">

                    <div id="price" style="background-color: rgba(207,207,207, .5);height:270px;padding: 15px;  width: 274px; float: left">
                      <div>
                            rental rate
                            <div style="font-size: 250%">$<span id="rate"><?php echo $viewmodel[0]['RATE']?></span> / day</div>
                        </div>
                        <br/>
                        <div>
                            deposit
                            <div style="font-size: 250%">$<?php echo $viewmodel[0]['DEPOSIT']?></div>
                        </div>       
                    </div> 

                    <div id="lender" style="width: 274px; margin-left: 60px; padding: 15px;  vertical-align:middle;background-color: rgba(207,207,207, .5);height:270px;float:left">

                      lender<br/>
                        <img id="profilepicture" src="<?php echo $viewmodel[0]['LENDER_PICTURE_FILENAME'] == null ? "/img/stock_user_profile.jpg" : "/uploads/user/" . $viewmodel[0]['LENDER_PICTURE_FILENAME']?>"><br/>
                        <div style="font-size:250%">
                            <a href="/user/index/<?php echo $viewmodel[0]['LENDER_ID']; ?>"><?php echo $viewmodel[0]['LENDER_FIRST_NAME']; ?></a>
                            <?php foreach ($viewmodel[2] as $network) {  ?>

                             <img src="/img/network/<?php echo $network['ICON_IMAGE'] ?>" title="<?php echo $viewmodel[0]['LENDER_FIRST_NAME']; ?> is a member of the <?php echo $network['NETWORK_NAME'] ?> network."></a>

                             <?php } ?>                              
                        </div>
                        <div id="lender_feedback" style="">
                            <?php if ($viewmodel[1][0] == null) { ?> <i>No feedback</i> <?php } else { ?>
                                <input name="rating2" type="radio" class="star" value="1" <?php if ($viewmodel[2][0] == 1) { ?> checked="checked" <?php } ?>/>
                                <input name="rating2" type="radio" class="star" value="2" <?php if ($viewmodel[2][0] == 2) { ?> checked="checked" <?php } ?>/>
                                <input name="rating2" type="radio" class="star" value="3" <?php if ($viewmodel[2][0] == 3) { ?> checked="checked" <?php } ?>/>
                                <input name="rating2" type="radio" class="star" value="4" <?php if ($viewmodel[2][0] == 4) { ?> checked="checked" <?php } ?>/>
                                <input name="rating2" type="radio" class="star" value="5" <?php if ($viewmodel[2][0] == 5) { ?> checked="checked" <?php } ?>/>   
                                <?php } ?>
                        </div>    
                    </div>
               </div>    
            </div>
            <br/>
            <br/>
        
                <div id="duration" style="">     
                    <div class="subheading" style="">
                        Duration
                    </div>
                    <br/>
                    <div style="width:100%; ">
                        How many days do you want to rent the item for?
                        <br/><br/>
                        <div style="background-color: rgba(207,207,207, .5);margin: 0px auto; width: 30%; text-align:center;padding:5px">
                            <div style=" max-width:100%;">
                                <select id="duration" name="duration">
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>                                  
                                  <option value="7">7</option>                                  
                                </select>&nbsp;&nbsp;&nbsp;days
                            </div>
                        </div>
                    </div>
                </div>
            <br/>
            <br/>
                <div id="message" style="">
                    <div class="subheading" style="">
                        Message
                    </div>     
                    <br/>
                    Tell <?php echo $viewmodel[0]['LENDER_FIRST_NAME']; ?> a little bit about yourself and why you want to rent the item:
                    <br/>
                    <br/>
                    <div style="background-color: rgba(207,207,207, .5);margin: 0px auto; width: 55%; padding:15px">
                        <div style=" max-width:100%; ">
                            Hi <?php echo $viewmodel[0]['LENDER_FIRST_NAME']; ?>,
                            <br/>
                            <textarea name="message" cols="65" rows="5"></textarea>
                            <br/>
                            -<?php echo $_SESSION['firstname']  ?>
                        </div>        
                    </div>
                </div>
    
            
            <br/>
            

            <br/>
            <div class="subheading" style="">
                Agreement
            </div>       
            <br/>
            <div style=" padding: 15px; background-color: rgba(207,207,207, .5); width: 400px; margin: 0px auto">
                due after item is returned
                <div style="font-size:150%">
                    $<span id="total">21</span> (rental fee x rent duration)
                </div>
                <br/>
                due if and only if item isn't returned within rental duration
                <div style="font-size:150%">
                    $<?php echo $viewmodel[0]['DEPOSIT']?>
                </div>                         
            </div>
                <br/>
               
                
                <input type="checkbox" name="agreement">If the rental request is approved, I agree to (1) pay the fees outlined above and (2) to the <a href="/document/legal">terms and conditions</a> of qhojo inc.            
                
            <br/><br/>
       
            <div style="margin:0px auto; max-width:100%; display:table;">
                <input id="submitbutton" type="submit" value="submit">
                <img id="loading" style="display:none" src="/img/ajax-loader.gif">
            </div>
           
        </form>
    
    <?php  } else if ($this->state == 2) { ?>
        <title>qhojo - Request Submitted <?php echo $viewmodel[0]['TITLE'];?></title>
        
    <div id="mainheading">
        Rental Request Submitted
    </div> 
    <hr/>
    <?php echo $_SESSION['firstname']  ?>, we've passed on your rental request for <a href="/item/index/<?php echo $viewmodel[0]['ITEM_ID'];?>"><?php echo $viewmodel[0]['TITLE'];?></a> to <?php echo $viewmodel[0]['LENDER_FIRST_NAME'];?>.
    <br/><br/>
    If <?php echo $viewmodel[0]['LENDER_FIRST_NAME'];?> approves your rental request, you'll get an email from us explaining what you guys have to do next.
    <?php } ?>
</div>