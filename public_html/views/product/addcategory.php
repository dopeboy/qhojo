<title>Qhojo - Product Admin</title>

<?php 

$name = null;
$target  = 2;

// Edit mode
if ($this->state == 1)
{
    $name = $viewmodel["NAME"];
    $target = 3;
}


?>

<div class="sheet">
    
    <legend>
        <?php if ($this->state == 1) { ?> Edit <?php } else { ?> Add <?php } ?> Category
    </legend>    
    
    <form class="form-submit" id="add-category" autocomplete="off" action="/product/addcategory/<?php echo $this->id == null ? "null" : $this->id; ?>/<?php echo $target;?>" method="post">	
        
        <div class="control-group">
            <input type="text" class="input" placeholder="Name" id="name" name="name" value='<?php echo $name;?>'>
        </div>                       

        <div class="control-group">
            <button type="submit" class="btn btn-large btn-primary" rel="tooltip" title="">Submit</button>
        </div>
                
    </form>  
    
</div>

<script src="/js/product/admin.js"></script>