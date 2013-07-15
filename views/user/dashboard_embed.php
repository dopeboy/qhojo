<table style="border: 1px solid; text-align: center;border-collapse:collapse; ">
    <tr id="cardheader" class="cardrow">
        <td class="title" style="color: black">
            Item
        </td>  
        <td class="rate" style="color: black">
            Rate <br/> (per day)
        </td>     
        <td class="lender" style="color: black">
            Lender
        </td>        
        <td class="borrower">
            Borrower
        </td>             
        <td class="state">
            Status
        </td>
        <td class="duration">
            Duration (days)
        </td>           
        <td class="start_date">
            Start Date
        </td>
        <td class="end_date">
            End Date
        </td>
        <td class="actions">
            Actions
        </td>
    </tr>

    
    <?php if (empty($items)) { ?> <tr><td colspan="9"><i>No data</i></td></tr> <?php } ?>
    
    
    <?php foreach($items as $item) { ?>

    <tr class="cardrow" itemid="<?php echo $item['ITEM_ID']; ?> ">
        <td class="title">
             <a href="/item/index/<?php echo $item['ITEM_ID'];?>"><?php echo $item['TITLE'];?></a>
        </td>
        <td class="rate">
            $<?php echo $item['RATE']; ?>
        </td>
        <td class="lender">
            <a href="/user/index/<?php echo $item['LENDER_ID']?>"><?php echo $item['LENDER_FIRST_NAME']; ?></a>
        </td>
        <td class="borrower">
            <a href="/user/index/<?php echo $item['BORROWER_ID']?>"><?php echo $item['BORROWER_FIRST_NAME']; ?></a>
        </td>    
        <td class="state <?php echo $item['ITEM_STATE_DESC']; ?>">
            <?php echo $item['ITEM_STATE_DESC']; ?>
        </td> 
        <td class="duration">
            <span class="duration"><?php echo $item['DURATION']; ?></span>
        </td>
        <td class="start_date">
            <?php echo $item['START_DATE'] != null ? date('m/d/Y',strtotime($item['START_DATE']))  : ""; ?>
        </td>
       <td class="end_date">
            <?php echo $item['END_DATE'] != null ? date('m/d/Y',strtotime($item['END_DATE']))  : ""; ?>
        </td>
        <td class="actions" style="">
            <a href="#" class="menubutton">Menu&#x25BC;</a>
            <ul class="menu" style="width:120px; text-align: left; display:none; position:fixed;font-size:100%;">
              <li class="
                  <?php 
                  if ($item['ITEM_STATE_ID']!=3 || !(($item['LENDER_ID'] == $this->userid && $item['LENDER_TO_BORROWER_STARS'] == null) || 
                           ($item['BORROWER_ID'] == $this->userid && $item['BORROWER_TO_LENDER_STARS'] == null)))
                  {
                        echo "ui-state-disabled";
                  }              
                  ?>
                  "><a href="/item/feedback/<?php echo $item['ITEM_ID'];?>/<?php echo $item['LENDER_ID'] == $this->userid ? "0" : "1" ?>">Give Feedback</a></li>
              <li class="
                  <?php 
                  if ($item['ITEM_STATE_ID']!=0 || $item['LENDER_ID'] != $this->userid)
                  {
                        echo "ui-state-disabled";
                  }              
                  ?>                  
                  "><a href="/item/delete/<?php echo $item['ITEM_ID'];?>/0">Delete Listing</a></li>
            </ul>                
        </td>
    </tr>

    <?php $i++; ?>
    <?php } ?>     
</table>