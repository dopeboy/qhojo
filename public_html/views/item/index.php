<title>Qhojo - Item</title>

<?php 
global $item_picture_path;
global $user_picture_path; 
global $item_thumb_subdir; 
global $user_thumb_subdir;
global $stock_user_tn;

$disable_borrow = false;
$disable_contact = false;

if ((isset($_SESSION["USER"]["USER_ID"]) && $_SESSION["USER"]["USER_ID"] ==  $viewmodel['ITEM']["LENDER_ID"]) || $viewmodel['ALREADY_REQUESTED'])
    $disable_borrow = true;

if ((isset($_SESSION["USER"]["USER_ID"]) && $_SESSION["USER"]["USER_ID"] ==  $viewmodel['ITEM']["LENDER_ID"]))
    $disable_contact = true;
?>

<link rel="stylesheet" href="/css/item/index.css">

<div class="sheet">
    <legend><?php echo $viewmodel['ITEM']['TITLE']?></legend>
    <div class="row-fluid">
        <div class="span9">
            <div class="row-fluid" id="visuals">
                <div class="span3 text-center section" id="item-thumbs">
                    <?php foreach ($viewmodel['ITEM_PICTURES'] as $key=>$picture) { ?>
                        <img class="thumbnail" full="<?php echo $item_picture_path . $viewmodel['ITEM']['ITEM_ID'] . '/' . $picture['FILENAME']?>" src="<?php echo $item_picture_path . $viewmodel['ITEM']['ITEM_ID'] . $item_thumb_subdir . '/' . $picture['FILENAME']?>">                    
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
                    <?php $show_item = 0; require_once(dirname(dirname(__FILE__)) . '/embeds/review.php'); ?>            
                 </div> 
            </div>
        </div>
        
        <div class="span3 side-panel">    
            <div class="section split action">
                <h2 class="text-center" id="rental-rate">$<?php echo $viewmodel['ITEM']['RATE']?> / day</h2>
                    <a href="<?php if ($disable_borrow) { echo "javascript:void(0)"; } else { echo "/transaction/request/" . $viewmodel['ITEM']['ITEM_ID']; }?>" id="rentlink" class="btn btn-success btn-large btn-block <?php if ($disable_borrow) { ?>disabled grayed-out<?php } ?>" type="button" >Borrow</a>    
            </div>
       
            <div class="section split text-center" id="lender">
                <a href="/user/index/<?php echo $viewmodel['ITEM']['LENDER_ID']?>">
                    <img id="lender-picture" class="img-circle" src="<?php echo $viewmodel['ITEM']['PROFILE_PICTURE_FILENAME'] == null ? $stock_user_tn : $user_picture_path . $viewmodel['ITEM']['LENDER_ID'] . $user_thumb_subdir . '/' . $viewmodel['ITEM']['PROFILE_PICTURE_FILENAME']; ?>">
                </a>
                <h2 class="" style="">
                    <a href="/user/index/<?php echo $viewmodel['ITEM']['LENDER_ID']?>"><?php echo $viewmodel['ITEM']['LENDER_NAME']; ?></a>
                </h2>
                    
                <?php 
                    $receipient_full_name = $viewmodel['ITEM']['LENDER_NAME'];
                    $receipient_first_name = $viewmodel['ITEM']['LENDER_FIRST_NAME'];
                    $sender_first_name = $_SESSION["USER"]["FIRST_NAME"];
                    $title = "Message {$receipient_full_name} about {$viewmodel['ITEM']['TITLE']}";
                    $sender_user_id =  $_SESSION["USER"]["USER_ID"];
                    $receipient_user_id =  $viewmodel['ITEM']['LENDER_ID'];
                    $entity_type = 'ITEM';
                    $entity_id = $viewmodel['ITEM']['ITEM_ID'];
                ?>
                
                <?php if (!empty($_SESSION["USER"]["USER_ID"])):  ?>
                    <button id="contact-btn" data-toggle="modal" href="<?php if ($disable_contact) { echo "javascript:void(0)"; } else { ?>#contact-modal-<?php echo $entity_id;} ?>" class="btn btn-primary btn-large btn-block <?php if ($disable_contact) {echo "disabled";} ?>" type="button" >Contact</button> 
                <?php else:  ?>
                    <button id="contact-btn-not-signedin" href="" class="btn btn-primary btn-large btn-block" type="button" >Contact</button> 
                <?php endif; ?>
                
                <?php require(dirname(dirname(__FILE__)) . '/embeds/contact.php'); ?> 
                    
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

<script src="/js/item/index.js"></script>

