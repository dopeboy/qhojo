<title>Qhojo - Join</title>

<link rel="stylesheet" href="/css/user/join.css">

<div class="sheet " id="join" style="">
    <form class="form-submit" id="join" autocomplete="off" action="/user/join/null/1" method="post">
        <legend>Join</legend>	

        <div class="control-group">
            <div class="controls">
                <input type="text" class="input-block-level" placeholder="First Name" id="firstname" name="firstname">
            </div>
        </div>                           

        <div class="control-group">
            <div class="controls">
                <input type="text" class="input-block-level" placeholder="Last Name" id="lastname" name="lastname">
            </div>
        </div>                       

        <div class="control-group">
            <div class="controls">
                <input type="text" class="input-block-level" placeholder="Zip Code" id="zipcode" name="zipcode">    
            </div>
        </div>      

        <div class="control-group">
            <div class="controls">
                 <input type="text" class="input-block-level" placeholder="Email Address" id="email" name="email">    
            </div>
        </div>                  

        <div class="control-group">
            <div class="controls">
                 <input type="password" class="input-block-level" placeholder="Password" id="password" name="password"> 
            </div>
        </div>                  

        <div class="control-group">
            <label class="control-label" for="input01"></label>
            <div class="controls">
                <button type="submit" class="btn btn-large btn-primary" rel="tooltip" title="">Create My Account</button>
            </div>
        </div>
        
        <input type="hidden" class="" placeholder="" id="invite-id" name="invite-id" value="<?php echo $this->id; ?>"> 
        
    </form>    
</div>

<script src="/js/user/join.js"></script>

