<?php 

global $item_picture_path, $user_picture_path; 
if (empty($requests) || count($requests) == 0): ?> <tr><td colspan="5"><i>No requests</i></td></tr>

<?php 

else: foreach($requests as $key=>$request) 
{     
?>

<tr>
    <td>
        <div class="media">
            <a class="pull-left" href="/item/index/<?php echo $request['ITEM_ID']; ?>">
                <img src="<?php echo $item_picture_path . $request['ITEM_ID'] . "/" . $request['ITEM_PICTURE_FILENAME'] ?>" class="thumbnail">
            </a>
            <div class="media-body">
                <h4>
                    <a href="/item/index/<?php echo $request['ITEM_ID']; ?>" class="media-heading"><?php echo $request['TITLE']; ?></a>
                </h4>
                <div>
                    $<?php echo $request['RATE']; ?> / day
                </div>
                <div>
                    <?php echo $request["REQ"]['START_DATE']; ?> - <?php echo $request["REQ"]['END_DATE']; ?>
                </div>
                <div>
                    <span class="label label-info"><?php echo $request["FINAL_STATE_NAME"];?></span>
                </div>
            </div>
        </div>
    </td>
    <td>$<?php echo $request["REQ"]['TOTAL']; ?></td>
    <td><a href="/user/index/<?php echo $request['BORROWER_ID']; ?>"><?php echo $request['BORROWER_NAME']; ?></a></td>
    <td><?php echo $request["REQ"]['MESSAGE']; ?>
                        <div style="margin-top: 10px">
                        <?php $d = new DateTime($request["REQ"]['RECEIVED_DATE']); echo "<em>Received " . $d->format('m/d g:i A') . "</em>"; ?>
                    </div>
    
    </td>
    <td>
        <div class="btn-group">
            <button class="btn">Menu</button>
            <button class="btn dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" style="">
                <li><a tabindex="-1" href="#">Accept</a></li>
                <li><a data-toggle="modal" tabindex="-1" href="#reject-<?php echo $request['TRANSACTION_ID']; ?>">Reject</a></li>
            </ul>
            <div id="reject-<?php echo $request['TRANSACTION_ID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Reject <?php echo $request['BORROWER_FIRST_NAME']; ?>'s request - <a href="/item/index/<?php echo $request['ITEM_ID']; ?>"><?php echo $request['TITLE']; ?></a></h3>
                </div>
                <div id="mb-<?php echo $request['TRANSACTION_ID']; ?>" class="modal-body">
                    <label>
                        <p>Specify a reason for rejecting this request:</p>
                    </label>
                    
                    <form id="form-<?php echo $request['TRANSACTION_ID']; ?>" >
                        <div class="reject-options" style="">
                            <?php foreach($viewmodel["REJECT_OPTIONS"] as $key=>$option) { ?>

                            <label class="radio">
                                <input type="radio" name="<?php echo $request['TRANSACTION_ID']; ?>" id="<?php echo $request['TRANSACTION_ID'] . '_' . $option['REJECT_OPTION_ID']; ?>" value="<?php echo $option['REJECT_OPTION_ID']; ?>" >
                                <?php echo $option['REJECT_DESCRIPTION']; ?>
                            </label>                                        

                            <?php } ?>
                        </div>    
                        <textarea id="txtarea-<?php echo $request['TRANSACTION_ID']; ?>" name="sdfsdf" rows="3" placeholder="Optional: Fill out a message here to specify more detail." style=""></textarea>   
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>                            
        </div>
    </td>
</tr>

<?php } endif; ?>

