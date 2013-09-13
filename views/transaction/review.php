<?php 
                              
global $item_picture_path, $user_picture_path; 
$transaction = reset($viewmodel); // first element

$current = 0;
$lender_view = $_SESSION["USER"]["USER_ID"] == $transaction["LENDER_ID"] ? 1 : 0;
$awaiting_review = 1;  

?>


<div class="sheet" id="review">
    
    <?php if ($this->state == 0) { ?>
    <legend>
        Review - <a href="/item/index/<?php echo $transaction['ITEM_ID'] ?>"><?php echo $transaction["TITLE"] ?></a>
    </legend>

    <div id="" class="">    
        <table id="" class="table table-bordered review-transaction-table" >
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
    
    <h3>Rate this transaction</h3>
    <form class="form-submit" id="review" action="/transaction/review/<?php echo $this->id ?>/1" method="post">
        <div id="rate-transaction" style="">
            <div class="btn-group" data-toggle="buttons-radio">  
              <button id="btn-one" type="button" data-toggle="button" name="option" value="0" class="btn btn-primary radio"><i class="icon-thumbs-up"></i></button>
              <button id="btn-two" type="button" data-toggle="button" name="option" value="1" class="btn btn-primary radio"><i class="icon-thumbs-down"></i></button>
            </div>
            <input type="hidden" name="rating" id="rating" value="" />            
        </div>

        <h3>Tell us more</h3>
        <div id="comment-transaction" style="margin-bottom: 20px">
            <textarea class="required" style="width: 500px" name="comments" id="comments" placeholder="How was your interaction with the <?php echo $lender_view == 0 ? "lender" : "borrower"?>?"></textarea>
        </div>

        <button type="submit" class="btn btn-large btn-primary" rel="tooltip" title="first tooltip">Submit Feedback</button>
    </form>
    
    <?php } else if ($this->state == 2) { ?>
    <legend>
        Review Submitted
    </legend>    
    <div>
        Thanks <?php echo $_SESSION["USER"]["FIRST_NAME"] ?>! You have reviewed your recent transaction regarding item <a href="/item/index/<?php echo $transaction["ITEM_ID"] ?>"><?php echo $transaction["TITLE"] ?></a> successfully. You're all done! 
    </div>
    <?php } ?>
</div>
    

<link rel="stylesheet" href="/css/transaction/review.css">
<script src="/js/transaction/review.js"></script>