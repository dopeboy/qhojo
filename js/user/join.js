$(document).ready(function(){

    $("form#join").data('validate_options',
    {
        errorElement: "span",
        errorClass: "text-error",
        rules:
        {
            firstname:"required",
            lastname:"required",
            zipcode: 
            {
                required:true
            },                                  
            email:
            {
                required:true,
                email: true
            },
            password:
            {
                required:true,
                minlength: 8
            }
        }
    });
});

function customResponseHandler(responseText)  
{ 
     window.location = responseText.URL;
} 