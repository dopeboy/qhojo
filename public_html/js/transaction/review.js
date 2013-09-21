$(document).ready(function() 
{ 
    $("form#review").data('validate_options',
    {
        ignore: "",
        errorElement: "span",
        errorClass: "text-error",
        rules:
        {
            rating:
            {
                number:true,
                required:true
            }
        }
    });
    
    $('.radio').click(function()
    {
        $('#rating').val($(this).val());
    });

}); 

function customResponseHandler(responseText)  
{ 
     window.location = responseText.URL;
} 
         