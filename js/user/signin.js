$(document).ready(function()
{
    var $state = window.location.pathname.split('/')[4];
    
    if ($state == 100)
    {
        $('#error-banner').fadeIn("slow");
        $('span#error-message').text("You have to sign in before you do that.");
    }
});

function customResponseHandler(responseText)  
{ 
     window.location = responseText.URL;
} 