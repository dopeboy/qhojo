<div id="tags">
    <?php foreach ($viewmodel["TAGS"] as $key=>$tag) {?> 
        <a href="<?php echo $tag['ID']?>">#<?php echo $tag['NAME']?></a>
   <?php } ?>
</div>