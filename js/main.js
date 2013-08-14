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
    // If we're coming from another page, disable the immediate scroll to anchor and then smooth scroll to anchor.'
    setTimeout(function() 
    {
        if (location.hash) 
        {
            window.scrollTo(0, 0);

            // Are we coming from a different peage?
            $(document.body).animate(
            {
                'scrollTop':   $(location.hash).offset().top - $('.navbar-fixed-top').height() - 10
            }, 
            'slow');        
        }
    }, 
    1);

});
