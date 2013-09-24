<?php 

global $item_picture_path, $item_thumb_subdir; 

if (empty($items) || count($items) == 0): ?> <tr id='no-items'><td colspan="5"><i>No items</i></td></tr>

<?php 

else: foreach($items as $key=>$item) 
{     
?>

<tr id="<?php echo $item['ITEM_ID']; ?>">
    <td>
        <div class='media'>
            <a class="pull-left" href="/item/index/<?php echo $item['ITEM_ID']; ?>">
                <img src="<?php echo $item_picture_path . $item['ITEM_ID'] . $item_thumb_subdir . "/" . $item['ITEM_PICTURE_FILENAME'] ?>" class="thumbnail">
            </a>
            <div class="media-body">
                    <h4>
                        <a href="/item/index/<?php echo $item['ITEM_ID']; ?>" class="media-heading"><?php echo $item['TITLE']; ?></a>
                    </h4>
                    <div>
                        $<?php echo $item['RATE']; ?> / day
                    </div>
                    <div>
                       $<?php echo $item['DEPOSIT']; ?> hold amount            
                    </div>
            </div>    
        </div>
    </td>
    <td>
        <?php echo $item['LOCATION']; ?>
    </td>
    <td>
        <?php $response_time = $item['RESPONSE_TIME_IN_MINUTES']; 
        require(dirname(dirname(__FILE__)) . '/response_time.php'); ?>
    </td>
    <td>
        <?php echo $item['VIEWS']; ?>
    </td>
    
    <td>
        <div class="btn-group">            
                <button class="btn">Menu</button>
                <button class="btn dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" style="">
                    <?php if ($active == 1) { ?>
                        <li><a data-toggle="modal" tabindex="-1" href="#deactivate-<?php echo $item['ITEM_ID']; ?>">Deactivate</a></li>
                    <?php } 
                    else { ?>
                        <li><a data-toggle="modal" tabindex="-1" href="#activate-<?php echo $item['ITEM_ID']; ?>">Activate</a></li>
                    <?php } ?>
                </ul>
        </div>       
    </td>
    
    <div id="deactivate-<?php echo $item['ITEM_ID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div id="alert-error-modal-<?php echo $item['ITEM_ID']; ?>" class="alert alert-error alert-modal" style="">
            <strong>Error: </strong>
            <span id="error-message-modal-<?php echo $item['ITEM_ID']; ?>"></span>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>        
        <div class="modal-header text-center">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>Deactivate <a href="/item/index/<?php echo $item['ITEM_ID']; ?>"><?php echo $item['TITLE']; ?></a></h3>
        </div>

        <div id="mb-<?php echo $item['ITEM_ID']; ?>" class="modal-body text-left">
            <p>Are you sure you want to deactivate your item?</p>
        </div>
        <div class="modal-footer">
            <form id="deactivate" class="form-submit" action="/item/deactivate/<?php echo $item['ITEM_ID']; ?>/0" method="post">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button type="submit" class="btn btn-primary">Yes</button>
            </form>
        </div>
    </div>  

    <div id="activate-<?php echo $item['ITEM_ID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div id="alert-error-modal-<?php echo $item['ITEM_ID']; ?>" class="alert alert-error alert-modal" style="">
            <strong>Error: </strong>
            <span id="error-message-modal-<?php echo $item['ITEM_ID']; ?>"></span>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>            
        <div class="modal-header text-center">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>Activate <a href="/item/index/<?php echo $item['ITEM_ID']; ?>"><?php echo $item['TITLE']; ?></a></h3>
        </div>

        <div id="mb-<?php echo $item['ITEM_ID']; ?>" class="modal-body text-left">
            <p>Are you sure you want to activate your item?</p>
        </div>
        <div class="modal-footer">
            <form id="activate" class="form-submit" action="/item/activate/<?php echo $item['ITEM_ID']; ?>/0" method="post">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button type="submit" class="btn btn-primary">Yes</button>
            </form>
        </div>
    </div>  

</tr>

<?php } endif; ?>

