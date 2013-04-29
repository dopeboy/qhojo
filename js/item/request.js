$(document).ready(function() 
{ 
     $( document ).tooltip();

            $('#myForm').validate( {
    rules: {
        "agreement": {
            required: true,
            minlength: 1
        },
        errorPlacement: function(error, element) { }       
  }
    });
    
    jQuery.extend(jQuery.validator.messages, {
    required: ""
});

    $('#total').text($('#rate').text()*$('#duration option:selected').text()); 
   
    $("#duration").change(function()
    {
        $('#total').text($('#rate').text()*$('#duration option:selected').text()); 
    });
 
    var options = { 
         beforeSubmit:  check,
        success:       showResponse  // post-submit callback 
    }; 
 
    // bind form using 'ajaxForm' 
    $('#myForm').ajaxForm(options); 
    

    
     function check(formData, jqForm, options) 
 { 
    $('#loading').show();
    $('#submitbutton').prop("disabled", true);
    
    
    return true;
 }
 
// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  
{ 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 
 
    if (responseText == 0)
        window.location = "2";
    else if (responseText == 1)
        alert("Error making reservation!");
  
 
 }    
}); 
 
