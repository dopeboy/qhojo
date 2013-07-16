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
    
    $('.rentlink').click(function(e)
    {
        e.preventDefault();
        $('#searchcontainer').show("slow");

        $(document.body).animate(
        {
            'scrollTop':   $('#searchcontainer').offset().top - $('.navbar-fixed-top').height() - 50
        }, 
        'slow');
        $('#searchbar').focus();
    });

});
