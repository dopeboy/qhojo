$(document).ready(function()
{    
    $('[rel=tooltip]').tooltip();  
    
    if ($.cookie('seenSearch'))
    {
        $('input#query').popover('hide');
        $.cookie('seenSearch', true);    
    }
    
    else 
    {
        $('input#query').popover('toggle');        
        $.cookie('seenSearch', true);   
    }

    // lose focus
    $('input#query').blur(function() 
    {
        $('input#query').popover('destroy');
    });
    
    function InOut( elem )
    {
        if (elem.next()[0] == null)
        {
            elem.delay().fadeIn(2000);
        }

        else
        {
            elem.delay().fadeIn(2000).delay(2000).fadeOut
            (
                1000,
                function()
                { 

                            InOut( elem.next()); 
                }
            );          
        }
    }

    $(function()
    {
        InOut( $('#wanted-items item:first'));
    });
});
