        <?php $i=0; ?>
        <?php foreach($viewmodel["ITEMS"] as $item) { ?>

            <div class="card" style="<?php if ($i%4==0) { ?> margin-left:4px; <?php } ?><?php if ($i<4) { ?> margin-top:6px; <?php } ?>">
                <div class="top">
                    <a href="/item/index/<?php echo $item['ITEM_ID']; ?>" class="fill-div">
                        <img src="<?php echo $item['ITEM_PICTURE_FILENAME']== null ? "/img/stock.png" : "/uploads/item/" . $item['ITEM_PICTURE_FILENAME']; ?>" style="max-width:100%; max-height:100%;">
                    </a>
                </div>
                <div class="ruler">
                    <hr style="display: none;"/>
                </div>
                <div class="bottom">
                    <span class="title">
                        <a href="/item/index/<?php echo $item['ITEM_ID']; ?>">
                            <?php echo substr($item['TITLE'],0,21); if (strlen($item['TITLE']) > 21) { echo "..."; } ?>
                        </a>
                    </span>
                    <br/>
                    location: <span class="location"><a href="/item/search/<?php echo $item['LOCATION_ID']?>/1"><?php echo $item['NEIGHBORHOOD'] . ',' . $item['BOROUGH_SHORT']; ?></a></span><br/>
                    rate: <span class="rate">$<?php echo $item['RATE']; ?> /day</span><br/>
                    lender: <span class="lender"><a href="/user/index/<?php echo $item['LENDER_ID']?>"><?php echo $item['LENDER_FIRST_NAME']; ?></a></span>
                </div>        
            </div>                     

        <?php $i++; ?>
        <?php } ?>