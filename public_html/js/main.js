function getURLParameter(name) 
{
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
    var regexS = "[\\?&]"+name+"=([^&#]*)";  
    var regex = new RegExp( regexS );  
    var results = regex.exec( window.location.href ); 
    if( results == null )    return "";  
    else    return results[1];
}

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
    $('#error-banner').hide();
    $('.alert.alert-error.alert-modal').hide(); // for modals
    
    var $pass = true;

    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
     var formElement = jqForm[0]; 
     
    if (jqForm.data('validate_options') != null)
    {
        jqForm.validate(jqForm.data('validate_options'));
        $pass = jqForm.valid();
    }        

    if ($pass)
        jqForm.find('button[type=submit]').attr('disabled','disabled');
    
    return $pass; 
} 

// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  
{
    // Houston we got a problem
    if (responseText.Error != null)
    {
        if (responseText.Error.ModalID != null)
        {
            $('#alert-error-modal-' + responseText.Error.ModalID).fadeIn("slow");
            $('#error-message-modal-' + responseText.Error.ModalID).text(responseText.Error.Message);
            $form.find('button[type=submit]').removeAttr('disabled');
        }

        else   
        {
            $('#error-banner').fadeIn("slow");
            $('span#error-message').text(responseText.Error.Message);
            $form.find('button[type=submit]').removeAttr('disabled');
            $("html, body").animate({ scrollTop: 0 }, "slow");
        }
    }
    
    else
        customResponseHandler(responseText);
}