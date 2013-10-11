<?php
    global $results_per_page;
    $current_page = !empty($this->urlvalues['page']) ? $this->urlvalues['page'] : 1; 
    $pages = ceil($viewmodel["ITEMS_COUNT"]/$results_per_page);
    $query = !empty($this->urlvalues['query']) ? $this->urlvalues['query'] : ''; 
    $location = !empty($this->urlvalues['location']) ? $this->urlvalues['location'] : ''; 
    $user_id = !empty($this->urlvalues['user_id']) ? $this->urlvalues['user_id'] : ''; 
    
    if ($user_id != null): 
        $title = $viewmodel["USER_FIRST_NAME"] . "'s Items"; 
    elseif ($query == null && $location == null && $user_id == null):
        $title = "All Items (" . $viewmodel["ITEMS_COUNT"] . ")";
    else:
        $title = "Search Results (" . $viewmodel["ITEMS_COUNT"] . ")";
    
    endif;
?>

<link rel="stylesheet" href="/css/item/search.css">

<title>Qhojo - <?php echo $title; ?></title>

<?php if (empty($user_id)) { ?>

<div class="sheet">
    <form class="form-search">
        <div class="row-fluid">
            <div class="span5">
                <input id="query" type="text" class="input-block-level" placeholder="Model, manufacturer, type, etc" name="query" style=""  value="<?php echo $query; ?>" data-toggle="tooltip" data-original-title="Start Here" data-content="What are you looking for?" data-placement="bottom" data-trigger="focus">
            </div>

            <div class="span2">
                <input id="location" type="text" class="input-block-level" placeholder="City or Zip Code" name="location" style="" value="<?php echo $location; ?>">
            </div>

            <input id="page" type="hidden" class="input-block-level" name="page" value="1" style="">
            
            <div class="span5">
                <button id="search-button" type="submit" class="btn btn-large btn-primary"  style="">
                    <i class="icon-search icon-white"></i> Search
                </button>
            </div>
        </div>
    </form>
</div>

<?php } ?>

<div class="sheet" style="">
    <legend><?php echo $title; ?></legend>
        
    <?php require_once(dirname(dirname(__FILE__)) . '/embeds/card.php'); ?>
        
    <div class="pagination pagination-centered">
        <ul>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || $current_page == 1) { echo "disabled";  } else if ($current_page > 1) { echo "";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=<?php echo $current_page-1; ?>">Prev</a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0) { echo "disabled";  } else if ($current_page == 1) { echo "active";} else {}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=1">1</a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || $pages < 2) { echo "disabled";  } else if ($current_page == 2) { echo "active";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=2">2</a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || $pages < 3) { echo "disabled";  } else if ($current_page == 3) { echo "active";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=3">3</a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || $pages < 4) { echo "disabled";  } else if ($current_page == 4) { echo "active";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=4">4</a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || $pages < 5) { echo "disabled";  } else if ($current_page == 5) { echo "active";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=5">5</a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || ($current_page+1 > $pages)) { echo "disabled";  } else { echo "";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=<?php echo $current_page+1; ?>">Next</a>
            </li>
        </ul>
    </div>        
        
</div>

<script src="/js/jquery.cookie.js"></script>
<script src="/js/item/search.js"></script>


