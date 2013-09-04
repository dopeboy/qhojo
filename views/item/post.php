<?php global $item_picture_path;global $user_picture_path;?>

<link rel="stylesheet" href="/css/item/index.css">
<link rel="stylesheet" href="/css/item/post.css">
    
<div class="sheet">
    <form class="form-submit" id="post" action="/item/post/" method="post">
        <legend>
            <div class="non-editable" id="title">
                <span id="title" class="value"></span>    
                <a href="javascript:void(0);">
                    <i class="icon-pencil" id="title" style=""></i>
                </a>
            </div>

            <input type="text" class="input-block-level editable" placeholder="Put in the name of the item here. Include the model and manufacturer." id="title" name="title">

        </legend>
        <div class="row-fluid">
            <div class="span9">
                <div class="row-fluid" id="visuals">
                    <div class="span3 text-center section" id="item-thumbs">

                    </div>

                    <div class="span9 text-center" id="item-picture">

                    </div>       
                </div>

                <div id="details">
                    <div class="subsection" id="description">
                        <h3>Description</h3>

                        <textarea id="description" class="editable" placeholder="Enter in some descriptive details about the item here." style=""></textarea>

                        <div class="non-editable">
                            <p>
                                <span id="description" class="value"></span> 
                                <a href="javascript:void(0);">
                                    <i class="icon-pencil icon-2x" id="description" style="margin-left: 10px"></i>
                                </a>                            
                            </p>
                        </div>
                    </div>

                    <div class="subsection" id="hold-policy">
                        <h3>Hold Policy</h3>

                        A $<input type="text" class="input-block-level editable positive-integer" placeholder="Enter item amount" id="hold" name="hold" style="width:151px; ; margin-top: 0px; margin-bottom: 0px"><span id="hold-policy" class="non-editable value"></span>  

                        hold will be placed on the borrower's credit card at the start of the rental period.
                        <a href="javascript:void(0);">
                            <i class="icon-pencil icon-2x" id="hold" style="margin-left: 10px; display:none"></i>
                        </a>  
                    </div>                      

                    <div class="subsection" id="reviews">
                        <h3>Reviews</h3>
                        <i>No reviews yet</i>
                     </div> 
                </div>
            </div>

            <div class="span3 side-panel">    
                <div class="section split action">


                    <h2 class="" id="rental-rate" style="display:inline">$</h2>
                    <input type="text" class="input-block-level editable positive-integer" placeholder="Rate" id="rate" name="rate" style="">
                    <h2  style="display:inline">&nbsp;/ day                                     
                    </h2>

                    <div class="non-editable">
                        <h2 class="text-center" id="rental-rate" style="">$<span id="rental-rate" class="value"></span> / day
                            <a href="javascript:void(0);">
                                <i class="icon-pencil" id="rate" style=""></i>
                            </a>    
                        </h2>
                    </div>

                    <button id="rentlink" class="btn btn-success btn-large btn-block" type="button" >Borrow</button>     
                </div>

                <div class="section split text-center" id="lender">
                    <a href="/user/index/<?php echo $viewmodel['USER']['USER_ID']?>">
                        <img id="lender-picture" class="img-circle" src="<?php echo $user_picture_path . $viewmodel['USER']['USER_ID'] . "/" . $viewmodel['USER']['PROFILE_PICTURE_FILENAME'] ?>">
                    </a>
                    <h2 class="" style="">
                        <a href="/user/index/<?php echo $viewmodel['USER']['USER_ID']?>"><?php echo $viewmodel['USER']['NAME']; ?></a>
                    </h2>

                    <button id="contact-btn" class="btn btn-primary btn-large btn-block" type="button" >Contact</button>         
                </div>

                <div class="section split map">
                    <input type="hidden" id="location" name="location" value="<?php echo $viewmodel['USER']['ZIPCODE']?>"/>

                    <div class="non-editable" style="display: block">    
                        <div id="map_canvas" style=""></div>    
                        <a href="javascript:void(0);">
                            <i class="icon-pencil icon-2x " id="zipcode" style=""></i>
                        </a>  
                    </div>

                    <input type="text" class="input-block-level editable positive-integer" placeholder="Enter zipcode" id="zipcode" name="zipcode" value="<?php echo $viewmodel['USER']['ZIPCODE']?>" style="display:none">

                </div>            
            </div>
        </div>    

        <button id="rentlink" class="btn btn-primary btn-large btn-block" type="submit" style="width: 150px; margin: 20px auto 0px auto;" >Submit</button>     
    </form>
</div>

<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATBCUDSJrOMyO4sm1-r8ooIjByWnZaYeA&sensor=false">
</script> 


<script src="/js/item/post.js"></script>
<script src="/js/jquery.numeric.js"></script>
