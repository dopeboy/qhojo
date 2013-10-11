<title>Qhojo - Submit Invite Code</title>

<link rel="stylesheet" href="/css/invite/submit.css">

<div class="sheet" id="invite" style="">
    <form id="form-signin" class="form-submit" action="/invite/submit/null/1" method="post">
        <legend>Enter Invite Code</legend>	
        
        <div class="control-group">
            <div class="controls">
                 <input type="text" class="input-block-level" placeholder="Invite Code" id="code" name="code">  
            </div>
        </div>        

        <div class="control-group">
            <div class="controls">
            <button type="submit" class="btn btn-large btn-primary" id="submit-button">Submit</button>
            </div>
        </div>
        
        <div id="request-code">
            Don't have a code? <a href="/invite/request">Request one</a>
        </div>        
    </form>    
</div>

<script src="/js/invite/submit.js"></script>


