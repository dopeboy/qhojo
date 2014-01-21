<title>Qhojo - Sign in</title>

<link rel="stylesheet" href="/css/user/signin.css">

<div class="sheet" id="signin" style="">
    <form id="form-signin" class="form-submit" action="/user/signin/null/1" method="post">
        <legend>Sign in</legend>	
        
        <div class="control-group">
            <div class="controls">
                 <input type="text" class="input-block-level" placeholder="Email address" id="email" name="email">  
            </div>
        </div>   
        
        <div class="control-group">
            <div class="controls">
                   <input type="password" class="input-block-level" placeholder="Password" id="passwd" name="password">
            </div>
        </div>        

        <div class="control-group">
            <label class="control-label" for="input01"></label>
            <div class="controls">
            <button type="submit" class="btn btn-large btn-primary" id="submit-button" rel="tooltip">Sign in</button>
            </div>
        </div>
        
    </form>    
    
    <div id="join">
        Not a member? <a href="/user/join/">Join here</a>
    </div>
</div>

<script src="/js/user/signin.js"></script>


