$(document).ready(function()
{
    var $state = window.location.pathname.split('/')[4];
    
    if ($state == 100)
    {
        $('#error-banner').fadeIn("slow");
        $('span#error-message').text("Invalid invite code. Try again.");
    }
    
});

function customResponseHandler(responseText)  
{
     window.location = responseText.URL;
} 