<link rel="stylesheet" href="/css/embeds/review.css">

<?php global $item_picture_path, $user_picture_path, $user_card_subdir, $stock_user_card;?>
<?php if (!isset($viewmodel["ITEM_REVIEWS"]) || count($viewmodel["ITEM_REVIEWS"]) == 0): ?> <i>No reviews yet</i>

<?php else: foreach($viewmodel["ITEM_REVIEWS"] as $key=>$review) { ?>
    <?php $data = json_decode($review['DATA']); $rating = $data->{"RATING"}; $comment = $data->{"COMMENT"};?>
    <?php $now = new DateTime();$ref = new DateTime($review['REVIEW_DATE']); $diff = $now->diff($ref); 
    
        $diff_days = $diff->d;
        $diff_hours = $diff->h;
        $diff_months = $diff->m;
        $diff_years = $diff->y;
        $diff_weeks = 0;
        $diff_output_string = '';
        
        if ($diff_days > 7 && $diff_months == 0)
            $diff_weeks = ceil($diff_days/7);
            
        if ($diff_years > 0)
            $diff_output_string = $diff_years . ' year' . ($diff_years > 1 ? 's' : '') . ' ago';
        
        else if ($diff_months > 0)
            $diff_output_string = $diff_months . ' month' . ($diff_months > 1 ? 's' : '') . ' ago';
        
        else if ($diff_weeks > 0)
            $diff_output_string = $diff_weeks . ' week' . ($diff_weeks > 1 ? 's' : '') . ' ago';
        
        else if ($diff_days > 0)
            $diff_output_string = $diff_days . ' day' . ($diff_days > 1 ? 's' : '') . ' ago';        
        
        else if ($diff_hours > 0 )
            $diff_output_string = $diff_hours . ' hour' . ($diff_hours > 1 ? 's' : '') . ' ago';        
        
        else
            $diff_output_string = 'couple minutes ago';   
    
    ?>

     <div class="row-fluid review" style="">
        <div class="span1">
            <a href="/user/index/<?php echo $review['REVIEWER_ID']?>">
                <img src="<?php echo $review['REVIEWER_PROFILE_PICTURE'] == null ? $stock_user_card : $user_picture_path . $review['REVIEWER_ID'] . $user_card_subdir . '/' . $review['REVIEWER_PROFILE_PICTURE'] ?>" class="img-circle">
            </a>
        </div>
        <div class="span11">
            <div class="row-fluid review-top" style="">
                <div class="pull-left">
                    <i class="icon-thumbs-<?php echo $rating == 0 ? "up" : "down"?>"></i> by 
                    <?php if ($review["REVIEWER_ACTIVE"] == 1) { ?><a href="/user/index/<?php echo $review['REVIEWER_ID']?>"><?php } ?>
                    <?php echo $review['REVIEWER_NAME'] ?>
                    <?php if ($review["REVIEWER_ACTIVE"] == 1) { ?></a><?php } ?>
                    <?php if ($show_item == 1) { ?>
                        for 
                        <?php if ($review["ITEM_ACTIVE"] == 1) { ?><a href="/item/index/<?php echo $review['ITEM_ID']?>"><?php } ?>
                        <?php echo substr($review['ITEM_TITLE'],0,21); if (strlen($review['ITEM_TITLE']) > 21) { echo "..."; } ?>
                        <?php if ($review["ITEM_ACTIVE"] == 1) { ?></a><?php } ?>    
                    <?php } ?>
                </div>
                <div class="pull-right" style="">
                    <?php echo $diff_output_string; ?>
                </div>                            
            </div>

            <div style="">
                <?php echo $comment;?>
            </div>                        
        </div>
    </div>

<?php } endif; ?>

