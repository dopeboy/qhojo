$(document).ready(function()
{    
    var options = 
    { 
        beforeSubmit:  showRequest,  // pre-submit callback 
        success:       showResponse,  // post-submit callback 

        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 

        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 

    // bind to the form's submit event 
    $('.form-submit').submit(function() 
    { 
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 

        $(this).ajaxSubmit(options); 

        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
});

// pre-submit callback 
function showRequest(formData, jqForm, options) 
{ 
    jqForm.find('button[type=submit]').attr('disabled','disabled');
    return true;
} 

// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  
{ 
    // Houston we got a problem
    if (responseText.Error != null)
    {
        if (responseText.RequestURI != null)
        {
            var parts = responseText.RequestURI.split('/');
            
            // User sign in
            if (parts[2] == "signin")
                window.location.href = "/user/signin/null/200";
            
            // Submit invite code
            else if (parts[2] == "submit")
                window.location.href = "/invite/submit/null/100";
        }
    }
    
    else
        window.location.href = responseText.URL;
}