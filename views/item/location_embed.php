<div id="boroughs">
    <?php foreach ($viewmodel["BOROUGHS"] as $key=>$borough) {?> 
    <?php if ($this->state == 2 && $borough["ID"]==$this->id) { echo "<b>"; } ?>
    <a class="borough" href="/item/search/<?php echo $borough["ID"]; ?>/2" boroughid="<?php echo $borough["ID"]; ?>"><?php echo $borough["SHORT_NAME"] . '</a>' . ($this->state == 2 && $borough["ID"]==$this->id ? "</b>" : "") . ($key == count($viewmodel["BOROUGHS"])-1 ? "" : ' | ');  ?>
    <?php } ?>
</div>

<?php foreach ($viewmodel["BOROUGHS"] as $key=>$borough) { $str = ""; ?>
    <div class="neighborhoods" boroughid="<?php echo $borough["ID"]; ?>" style="display:none">
        <?php foreach ($viewmodel["NEIGHBORHOODS"] as $key=>$neighborhood ) 
              { 
                    if ($borough["ID"] == $neighborhood["BOROUGH_ID"])
                    {
                         $str .= "<a class=\"neighborhood\" neighborhoodid=\"" . $neighborhood["ID"] . "\" href=\"/item/search/" . $neighborhood["ID"] . "/1\">" . $neighborhood["FULL_NAME"] . "</a> | ";
                    }
              }

              echo substr($str, 0, -2);
         ?>
    </div>
<?php } ?>