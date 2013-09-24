<link rel="stylesheet" href="/css/invite/request.css">

<?php if ($this->state == 0 || $this->state == null) { ?>

<div class="sheet" id="request-invite" style="">
    <form class="form-submit" id="request-invite" autocomplete="off" action="/invite/request/null/1" method="post">
        <legend>Request Invite</legend>	

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
                 <input type="text" class="input-block-level" placeholder="Email Address" id="email" name="email">    
            </div>
        </div>                         

        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-large btn-primary" rel="tooltip" title="">Submit</button>
            </div>
        </div>
    </form>     
</div>

<?php } else if ($this->state == 2) { ?>

<div class="sheet" id="invite-submitted" style="">
    <legend>Request Submitted</legend>
    Your request for an invitation code has been submitted. We'll reach out to you shortly.
</div>

<?php } ?>

<script src="/js/invite/request.js"></script>

