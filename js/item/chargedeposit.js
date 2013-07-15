$(document).ready(function()
{ 

var options = { 
        success:       showResponse,  // post-submit callback 
        beforeSubmit:  check
    }; 
    // bind form using 'ajaxForm' 
    $('#myForm').ajaxForm(options); 
    
    
 function check(formData, jqForm, options) 
 { 

    $('#loading').show();
    $('#submit').prop("disabled", true);
     
     return true;
 }

// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  
{ 
    if (responseText > 0)
        window.location = "/item/chargedeposit/" + responseText + "/2";
    else
        alert("Error code:" + responseText);
 }      
    
});