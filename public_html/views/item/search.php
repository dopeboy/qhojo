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
        $title = "All Items";
    else:
        $title = "Search Results (" . $viewmodel["ITEMS_COUNT"] . ")";
    
    endif;
?>

<link rel="stylesheet" href="/css/item/search.css">

<title>Qhojo - <?php echo $title; ?></title>

<?php if (empty($user_id)) { ?>

<div class="alert alert-info">
    <strong>Help out: </strong>
    Members have been searching for
    <span id="wanted-items" class="text-center" style="">
        <item >Canon 24-70mm F/4.0L</item>
        <item >Canon 50mm F/1.2L</item>
        <item >Nikon 18-300mm F/3.5</item>
        <item >Canon 5D Mark II</item>
        <item >Nikon D800</item>
        <item >Nikon 17-55mm F/2.8G</item>
    </span>. 
    <a href="/item/post" style="color: indianred">Lend it now</a> to help the community and make some cash.
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div class="sheet">
    <form class="form-search">
        <div class="row-fluid">
            <div class="span5">
                <input id="query" type="text" class="input-block-level" placeholder="Model, manufacturer, type, etc" name="query" style=""  value="<?php echo $query; ?>" data-toggle="tooltip" data-original-title="Start Here" data-content="What are you looking for?" data-placement="bottom" data-trigger="focus">
            </div>

            <div class="span2">
                <input id="location" type="text" class="input-block-level" placeholder="City or Zip Code" name="location" style="" value="<?php echo urldecode($location); ?>">
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
        
    <?php 
        require_once(dirname(dirname(__FILE__)) . '/embeds/card.php'); 
// 1-4 => 1-5
// 5-8 => 5-9
// 9-12 => 9-13        
        
        if ($current_page%4 == 0)
            $start = $current_page-3;
        else
            $start = (int)($current_page/4)*4 +1;
        
        if ($current_page%4 == 0)
            $end = $current_page+1;
        else
            $end = (int)($current_page/4)*4 +5;   
        
        
        //$end = ((int)($current_page / 5)+1)*5;
        
    ?>
        
    <div class="pagination pagination-centered">
        <ul>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || $current_page == 1) { echo "disabled";  } else if ($current_page > 1) { echo "";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=<?php echo $current_page-1; ?>">Prev</a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0) { echo "disabled";  } else if ($current_page == range($start, $end)[0]) { echo "active";} else {}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=<?php echo range($start, $end)[0]; ?>"><?php echo range($start, $end)[0]; ?></a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || $pages < range($start, $end)[1]) { echo "disabled";  } else if ($current_page == range($start, $end)[1]) { echo "active";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=<?php echo range($start, $end)[1]; ?>"><?php echo range($start, $end)[1]; ?></a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || $pages < range($start, $end)[2]) { echo "disabled";  } else if ($current_page == range($start, $end)[2]) { echo "active";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=<?php echo range($start, $end)[2]; ?>"><?php echo range($start, $end)[2]; ?></a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || $pages < range($start, $end)[3]) { echo "disabled";  } else if ($current_page == range($start, $end)[3]) { echo "active";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=<?php echo range($start, $end)[3]; ?>"><?php echo range($start, $end)[3]; ?></a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || $pages < range($start, $end)[4]) { echo "disabled";  } else if ($current_page == range($start, $end)[4]) { echo "active";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=<?php echo range($start, $end)[4]; ?>"><?php echo range($start, $end)[4]; ?></a>
            </li>
            <li class="<?php if ($viewmodel["ITEMS_COUNT"] == 0 || ($current_page+1 > $pages)) { echo "disabled";  } else { echo "";}?>">
                <a href="?query=<?php echo $query;?>&user_id=<?php echo $user_id;?>&location=<?php echo $location;?>&page=<?php echo $current_page+1; ?>">Next</a>
            </li>
        </ul>
    </div>        
        
</div>

<script src="/js/jquery.cookie.js"></script>
<script src="/js/item/search.js"></script>


