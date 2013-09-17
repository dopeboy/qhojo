<?php 
                              
global $item_picture_path, $user_picture_path; 
$transaction = $viewmodel["TRANSACTION"];

$current = 0;
$lender_view = $_SESSION["USER"]["USER_ID"] == $transaction["LENDER_ID"] ? 1 : 0;
$awaiting_review = 1;  

?>

<link rel="stylesheet" href="/css/transaction/reportdamage.css">

<div class="sheet" id="reportdamage">
    
    <?php if ($this->state == 0) { ?>
    <legend>
        Report Damage - <a href="/item/index/<?php echo $transaction['ITEM_ID'] ?>"><?php echo $transaction["TITLE"] ?></a>
    </legend>

    <div id="" class="">    
        <table id="" class="table table-bordered reportdamage-transaction-table" >
            <thead>
                <tr>
                    <th class="item">Item</th>
                    <th class="total">Total</th>
                    <th class="user"><?php echo $lender_view == 1 ? "Lender" : "Borrower"?></th>
                    <th class="status">Status</th>
                </tr>
            </thead>
            
            <tbody>
                <tr>
                    <td>
                        <?php require(dirname(dirname(__FILE__)) . '/embeds/dashboard/subembeds/item.php'); ?> 
                    </td>
                    <td>
                        <?php require(dirname(dirname(__FILE__)) . '/embeds/dashboard/subembeds/total.php'); ?> 
                    </td>
                    <td>
                        <?php require(dirname(dirname(__FILE__)) . '/embeds/dashboard/subembeds/user.php'); ?> 
                    </td>
                    <td>
                        <?php require(dirname(dirname(__FILE__)) . '/embeds/dashboard/subembeds/status.php'); ?> 
                     </td>
                 </tr>
            </tbody>
        </table>       
    </div>
    
    <form class="form-submit" id="reportdamage" action="/transaction/reportdamage/<?php echo $this->id ?>/1" method="post">
        <h3>Rate the damage</h3>
        <div id="rate-damage" style="">
            <?php foreach($viewmodel["DAMAGE_OPTIONS"] as $key=>$option) { ?>
            <div class="radio">
                <input class="required" type="radio" name="damage-option" id="<?php echo $transaction['TRANSACTION_ID'] . '_' . $option['DAMAGE_OPTION_ID']; ?>" value="<?php echo $option['DAMAGE_OPTION_ID']; ?>" >
                <?php echo $option['DAMAGE_DESCRIPTION']; ?>
            </div>                                        
            <?php } ?>   
        </div>

        <h3>Details</h3>
        <div id="comment-damage" style="">
            <textarea class="required" style="width: 500px" name="comments" id="comments" placeholder="Use this area to go into detail about the damage."></textarea>
        </div>
         
        <h3>Pictures</h3>
        <div id="pictures-damage">
            Click the following button to attach pictures of the damaged item to this report: <button data-toggle="modal" tabindex="-1" href="#upload-damage" id="upload-picture-btn" class="btn btn-success " type="button" style="">Upload Pictures</button>
            <br/>
            <em>You have attached <span id="num-upload-pictures">0</span> picture(s) to this report.</em>
        </div>
        
        <button id="" type="submit" class="btn btn-large btn-primary" rel="tooltip" title="">Submit Report</button>
        
    </form>
    
    <input type="hidden" id="transaction_id" name="transaction_id" value="<?php echo $transaction["TRANSACTION_ID"]; ?>">
    
    <div id="upload-damage" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
        <div class="modal-header">
            <button id="close" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Upload Pictures</h3>
        </div>
        <div class="modal-body text-left" style="">
            <?php require(dirname(dirname(__FILE__)) . '/embeds/picture_upload.php'); ?> 
        </div>
        <div class="modal-footer">
            <button id="done" data-dismiss="modal" class="btn btn-primary">Done</button>
        </div>
    </div>   
    
    <?php } else if ($this->state == 2) { ?>
    <legend>
        Review Submitted
    </legend>    
    <div>
        Thanks <?php echo $_SESSION["USER"]["FIRST_NAME"] ?>! You have reviewed your recent transaction regarding item <a href="/item/index/<?php echo $transaction["ITEM_ID"] ?>"><?php echo $transaction["TITLE"] ?></a> successfully. You're all done! 
    </div>
    <?php } ?>
</div>
    


<script src="/js/transaction/reportdamage.js"></script>