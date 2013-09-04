<?php global $item_picture_path;global $user_picture_path;?>


<div class="sheet">
    <legend><?php echo $viewmodel['ITEM']['TITLE']?></legend>
    <div class="row-fluid">
        <div class="span9">
            <div class="row-fluid" id="visuals">
                <div class="span3 text-center section" id="item-thumbs">
                    <?php foreach ($viewmodel['ITEM_PICTURES'] as $key=>$picture) { ?>
                        <img class="thumbnail" src="<?php echo $item_picture_path . $viewmodel['ITEM']['ITEM_ID'] . '/' . $picture['FILENAME']?>">                    
                    <?php } ?>
                </div>

                <div class="span9 text-center" id="item-picture">
                    <img id="largeimage" src="<?php echo $item_picture_path . $viewmodel['ITEM']['ITEM_ID'] . '/' . $viewmodel['ITEM']['ITEM_PICTURE_FILENAME']?>">                    
                </div>       
            </div>
  
            <div id="details">
                <div class="subsection" id="description">
                    <h3>Description</h3>
                    <p>
                       <?php echo $viewmodel['ITEM']['DESCRIPTION'] ?> 
                    </p>
                </div>

                <div class="subsection" id="hold-policy">
                    <h3>Hold Policy</h3>
                    <p>
                        A $<?php echo $viewmodel['ITEM']['DEPOSIT']?> hold will be placed on the borrower's credit card at the start of the rental period.
                    </p>
                 </div>                      

                <div class="subsection" id="reviews">
                    <h3>Reviews</h3>
                    <?php require_once(dirname(dirname(__FILE__)) . '/embeds/review.php'); ?>            
                 </div> 
            </div>
        </div>
        
        <div class="span3 side-panel">    
            <div class="section split action">
                <h2 class="text-center" id="rental-rate">$<?php echo $viewmodel['ITEM']['RATE']?> / day</h2>
                <form action="/item/request/<?php echo $viewmodel['ITEM']['ITEM_ID']?>" method="get">
                    <button id="rentlink" class="btn btn-success btn-large btn-block <?php if ((isset($_SESSION["USER"]["USER_ID"]) && $_SESSION["USER"]["USER_ID"] ==  $viewmodel['ITEM']["LENDER_ID"]) || $viewmodel['ALREADY_REQUESTED']) {?>disabled<?php } ?>" type="submit" >Borrow</button>    
                </form>                    
            </div>
       
            <div class="section split text-center" id="lender">
                <a href="/user/index/<?php echo $viewmodel['ITEM']['LENDER_ID']?>">
                    <img id="lender-picture" class="img-circle" src="<?php echo $user_picture_path . $viewmodel['ITEM']['LENDER_ID'] . "/" . $viewmodel['ITEM']['PROFILE_PICTURE_FILENAME'] ?>">
                </a>
                <h2 class="" style="">
                    <a href="/user/index/<?php echo $viewmodel['ITEM']['LENDER_ID']?>"><?php echo $viewmodel['ITEM']['LENDER_NAME']; ?></a>
                </h2>
                
                <button id="contact-btn" class="btn btn-primary btn-large btn-block" type="button" >Contact</button> 
                <?php 
                    $receipient_full_name = $viewmodel['ITEM']['LENDER_NAME'];
                    $receipient_first_name = $viewmodel['ITEM']['LENDER_FIRST_NAME'];        
                ?>
                
                <div id="contact-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Message <?php echo $receipient_full_name; ?></h3>
                    </div>
                    <div class="modal-body text-left" style="">
                        <p>
                        Hey <?php echo $receipient_first_name; ?>,
                        </p>
                        <div>
                            <textarea rows="3" placeholder="Put your question here." style=""></textarea>
                            <p><?php echo !empty($this->userid) ? "-FILL IN" : "-FILL IN" ?></p>            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                        <button class="btn btn-primary">Send Message</button>
                    </div>
                </div>                
            </div>
            
            <div class="section split map">
                <input type="hidden" id="location" name="location" value="<?php echo $viewmodel['ITEM']['ZIPCODE']?>"/>
                <div id="map_canvas" style=""></div>
            </div>            
        </div>
    </div>    
</div>

<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATBCUDSJrOMyO4sm1-r8ooIjByWnZaYeA&sensor=false">
</script>   
    

<!--
<div class="sheet">
    <legend>Canon 7D</legend>
    <div class="row-fluid">
        <div class="span9">
            <div class="row-fluid visuals">
                <div class="span3 text-center section preview">
                     <img class="thumbnail" src="/img/7d_small.png">
                     <img class="thumbnail" src="/img/7d_small2.png" >
                </div>

                <div class="span9 text-center item-picture">
                    <img src="/img/7d_big.png">
                </div>       
            </div>
  
            <div class="details">
                <div class="subsection description">
                    <h3>Description</h3>
                    <p>
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.                 
                    </p>
                </div>

                <div class="hold-policy subsection">
                    <h3>Hold Policy</h3>
                    <p>
                        A $200 hold will be placed on your credit card at the start of the rental period.
                    </p>
                 </div>                      

                <div class="reviews subsection">
                    <h3>Reviews</h3>

                     <div class="row-fluid review" style="">
                        <div class="span1">
                            <img src="/img/guido.jpg" class="img-circle">
                        </div>
                        <div class="span11">
                            <div class="row-fluid review-top" style="">
                                <div class="pull-left">
                                    <i class="icon-thumbs-up"></i> by <a href="#">Guido L.</a>
                                </div>
                                <div class="pull-right" style="">
                                    3 days ago
                                </div>                            
                            </div>

                            <div style="">
                                I liked this camera. Manish is a great guy.
                            </div>                        
                        </div>
                    </div>

                    <div class="row-fluid review">
                        <div class="span1">
                            <img src="/img/sarohini.jpg" class="img-circle">
                        </div>
                        <div class="span11">
                            <div class="row-fluid review-top" style="">
                                <div class="pull-left">
                                    <i class="icon-thumbs-down"></i> by <a href="#">Sarohini C.</a>
                                </div>
                                <div class="pull-right" style="">
                                    7 days ago
                                </div>                            
                            </div>

                            <div>
                               Not good experience. Manish smells.
                            </div>                        
                        </div>                        
                    </div>

                 </div> 

            </div>
        </div>
        
        <div class="span3 side-panel">    
            <div class="section split action">
                <h2 class="text-center rental-rate">$25 / day</h2>
                <form action="/item/request/" method="get">
                    <button class="rentlink btn btn-success btn-large btn-block" type="submit" >Borrow</button>    
                </form>                    
            </div>
       
            <div class="section split lender text-center">
                <a href="/user/index/"><img src="/img/me_big.png" class="img-circle lender-picture" style=""></a>
                <h2 class="" style="padding: 10px 0px;"><a href="/user/index/">Manish S.</a></h2>
                 <button class="rentlink btn btn-primary btn-large btn-block" type="button" >Contact</button>    
            </div>
            
            <div class="section split map">
                <img src="/img/google_map.png">
            </div>            
        </div>
    </div>
</div>
-->

<link rel="stylesheet" href="/css/item/index.css">
<script src="/js/item/index.js"></script>

